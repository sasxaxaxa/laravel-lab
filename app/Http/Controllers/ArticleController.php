<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewArticleNotification;
use App\Models\User;


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
        if (!auth()->check()) {
            abort(403, 'Требуется аутентификация');
        }

        $validated = $request->validate(
            Article::rules(),
            Article::messages()
        );

        $validated['user_id'] = auth()->id();
        
        if (empty($validated['author'])) {
            $validated['author'] = auth()->user()->name;
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['is_published'] = $request->has('is_published');

        $article = Article::create($validated);

        try {
            $moderators = User::where('role', 'moderator')
                ->orWhere('role', 'admin')
                ->get();

            if ($moderators->isEmpty()) {
                $moderatorEmail = config('mail.to_address', config('mail.from.address'));
                
                Mail::to($moderatorEmail)->send(
                    new NewArticleNotification($article, auth()->user())
                );
            } else {
                foreach ($moderators as $moderator) {
                    Mail::to($moderator->email)->send(
                        new NewArticleNotification($article, auth()->user(), $moderator)
                    );
                }
            }
            
            \Log::info('Email уведомление отправлено модератору о новой статье', [
                'article_id' => $article->id,
                'article_title' => $article->title,
                'author_id' => auth()->id(),
                'moderators_count' => $moderators->count(),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Ошибка при отправке email уведомления: ' . $e->getMessage(), [
                'article_id' => $article->id,
                'error' => $e->getTraceAsString()
            ]);
        }

        return redirect()->route('articles.show', $article)
            ->with('success', 'Статья успешно создана!' . 
                   (isset($e) ? ' (Уведомление модератору не отправлено)' : ''));
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