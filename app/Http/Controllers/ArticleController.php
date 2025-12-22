<?php

namespace App\Http\Controllers;

use App\Events\NewArticleEvent;
use App\Models\Article;
use App\Models\User;
use App\Notifications\NewArticleCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Jobs\VeryLongJob;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    public function index()
    {
        // Ключ для кэширования с учетом страницы пагинации
        $page = request()->get('page', 1);
        $cacheKey = 'articles_page_' . $page;
        
        // Кэшируем на 60 минут (помните, что теперь используем database драйвер)
        $articles = Cache::remember($cacheKey, 3600, function () {
            return Article::latest()->paginate(10);
        });

        return view('pages.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check()) {
            abort(403, 'Требуется аутентификация через Sanctum');
        }

        Gate::authorize('create-article');

        $categories = ['politics', 'sports', 'technology', 'entertainment', 'business', 'health'];
        return view('pages.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Валидация данных
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        $validated['user_id'] = $user->id;
        $validated['author'] = $user->name ?? 'Admin';
        
        // Обработка изображения
        if ($request->has('image') && $request->image) {
            $validated['image'] = $request->image;
        } else {
            $validated['image'] = null;
        }
        
        // Создаем уникальный slug
        $slug = Str::slug($validated['title']);
        $count = Article::where('slug', 'LIKE', $slug . '%')->count();
        
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        $validated['slug'] = $slug;
        
        // Создание статьи
        $article = Article::create($validated);
    
    // Отправка события
    broadcast(new NewArticleEvent($article))->toOthers();
    
    // УБРАЛИ отправку уведомлений здесь
    // $readers = User::where('id', '!=', $user->id)->get();
    // foreach ($readers as $reader) {
    //     $reader->notify(new NewArticleCreated($article, $user));
    // }

    // Очищаем кэш главной страницы и пагинации
    $this->clearArticlesCache();

    // Логируем создание статьи
    info('Article created: ' . $article->id . '. Dispatching VeryLongJob to queue.');
    
    // Помещаем задание в очередь - оно отправит уведомления
    VeryLongJob::dispatch($article);
    
    return redirect()->route('articles.index')
        ->with('success', 'Article created successfully! Notifications are being sent in the background.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        // Ключ для кэширования просмотра статьи
        $cacheKey = 'article_' . $article->id . '_with_comments';
        
        // Кэшируем навсегда (или до очистки вручную)
        $cachedArticle = Cache::rememberForever($cacheKey, function () use ($article) {
            $article->increment('views');
            
            // Загружаем комментарии с пользователями
            $article->load(['comments' => function ($query) {
                $query->where('is_approved', true)
                      ->with('user')
                      ->orderBy('created_at', 'desc');
            }]);
            
            return $article;
        });
        
        // Пометить уведомления о данной статье как прочитанные
        if (Auth::check()) {
            $unreadNotifications = Auth::user()->unreadNotifications()
                ->where('data->article_id', $article->id)
                ->get();
            
            foreach ($unreadNotifications as $notification) {
                $notification->markAsRead();
            }
        }
        
        return view('pages.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = ['politics', 'sports', 'technology', 'entertainment', 'business', 'health'];
        return view('pages.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string',
        ]);
        
        if ($request->has('image')) {
            $validated['image'] = $request->image;
        }
        
        $article->update($validated);
        
        // Очищаем кэш статьи и главной страницы
        Cache::forget('article_' . $article->id . '_with_comments');
        $this->clearArticlesCache();

        return redirect()->route('articles.index')
            ->with('success', 'Статья успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        
        // Очищаем кэш статьи и главной страницы
        Cache::forget('article_' . $article->id . '_with_comments');
        $this->clearArticlesCache();
        
        // Также можно очистить весь кэш (опционально)
        // Cache::flush();

        return redirect()->route('articles.index')
            ->with('success', 'Статья успешно удалена!');
    }

    /**
     * Display articles by category.
     */
    public function category($category)
    {
        // Ключ для кэширования категории с учетом пагинации
        $page = request()->get('page', 1);
        $cacheKey = 'articles_category_' . $category . '_page_' . $page;
        
        // Кэшируем на 60 минут
        $articles = Cache::remember($cacheKey, 3600, function () use ($category) {
            return Article::where('category', $category)
                ->latest()
                ->paginate(10);
        });

        return view('pages.articles.category', compact('articles', 'category'));
    }
    
    /**
     * Вспомогательный метод для очистки кэша статей на главной
     */
    private function clearArticlesCache()
    {
        // Получаем общее количество статей для расчета количества страниц
        $totalArticles = Article::count();
        $perPage = 10;
        $totalPages = ceil($totalArticles / $perPage);
        
        // Удаляем кэш для всех возможных страниц
        for ($page = 1; $page <= $totalPages; $page++) {
            Cache::forget('articles_page_' . $page);
        }
        
        // Также удаляем первую страницу на всякий случай
        Cache::forget('articles_page_1');
        
        // Или можно использовать более простой подход - удалить все ключи, начинающиеся с 'articles_page_'
        // Но для database драйвера это сложнее, нужно реализовать свою логику
        
        // Для database драйвера лучше очистить всю таблицу кэша или использовать теги (если поддерживаются)
        // Cache::flush(); // Будет очищать весь кэш, включая другие данные
    }
}