<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
Route::get('/', [MainController::class, 'index'])->name('home');

Route::get('/gallery/{id}', [MainController::class, 'gallery'])->name('gallery');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');

Route::prefix('auth')->group(function () {
    Route::get('/signin', [AuthController::class, 'create'])->name('auth.create');
    
    Route::post('/signin', [AuthController::class, 'registration'])->name('auth.registration');
});

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/category/{category}', [ArticleController::class, 'category'])->name('articles.category');
    Route::get('/{article}', [ArticleController::class, 'show'])->name('articles.show');
});

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::get('/category/{category}', [ArticleController::class, 'category'])->name('articles.category');
});