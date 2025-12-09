<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    // В конструктор или отдельный метод
    public function create()
    {
        // Явная проверка через Sanctum
        if (!auth()->check()) {
            abort(403, 'Требуется аутентификация через Sanctum');
        }

        // Дополнительная проверка (если нужно)
        Gate::authorize('create-article');

        $categories = ['politics', 'sports', 'technology', 'entertainment', 'business', 'health'];
        return view('pages.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            Article::rules(),
            Article::messages()
        );

        $validated['user_id'] = auth()->id();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        Article::create($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Статья успешно создана!');
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