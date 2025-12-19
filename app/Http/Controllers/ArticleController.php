<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewArticleNotification;
use App\Models\User;
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
        $articles = Article::latest()
            ->paginate(10);

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
            // Если есть поле author в форме, добавьте:
            // 'author' => 'required|string|max:255',
        ]);
        
        // Используем auth() хелпер
        $validated['user_id'] = auth()->id();
        
        // Добавляем поле author (ВАЖНО!)
        $validated['author'] = auth()->user()->name ?? 'Admin';
        
        // Создаем уникальный slug из заголовка
        $slug = Str::slug($validated['title']);
        $count = Article::where('slug', 'LIKE', $slug . '%')->count();
        
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        $validated['slug'] = $slug;
        
        // Создание статьи
        $article = Article::create($validated);
        
        // Логируем создание статьи
        info('Article created: ' . $article->id . '. Dispatching VeryLongJob to queue.');
        
        // Помещаем задание в очередь
        VeryLongJob::dispatch($article);
        
        return redirect()->route('articles.index')
            ->with('success', 'Article created successfully! Notifications are being sent in the background.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->increment('views');
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
        $validated = $request->validate(
            Article::rules($article->id),
            Article::messages()
        );

        $article->update($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Статья успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Статья успешно удалена!');
    }

    /**
     * Display articles by category.
     */
    public function category($category)
    {
        $articles = Article::where('category', $category)
            ->latest()
            ->paginate(10);

        return view('pages.articles.category', compact('articles', 'category'));
    }
}