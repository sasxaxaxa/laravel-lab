<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::published()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pages.articles.index', compact('articles'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        // Увеличиваем счетчик просмотров
        $article->incrementViews();
        
        return view('pages.articles.show', compact('article'));
    }

    /**
     * Display articles by category.
     */
    public function category($category)
    {
        $articles = Article::published()
            ->category($category)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pages.articles.category', compact('articles', 'category'));
    }
}