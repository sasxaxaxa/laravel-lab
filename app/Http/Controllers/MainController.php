<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $articlesData = file_get_contents(public_path('articles.json'));
        $articles = json_decode($articlesData, true);
        
        return view('pages/home', compact('articles'));
    }

    public function gallery($id)
    {
        $articlesData = file_get_contents(public_path('articles.json'));
        $allArticles = json_decode($articlesData, true);
        
        if (!isset($allArticles[$id])) {
            return redirect()->route('home');
        }
        
        $article = $allArticles[$id];
        $article['id'] = $id;
        
        return view('pages/gallery', compact('article'));
    }
}