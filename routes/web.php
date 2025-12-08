<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProtectedController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MainController::class, 'index'])->name('home');

Route::get('/gallery/{id}', [MainController::class, 'gallery'])->name('gallery');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/category/{category}', [ArticleController::class, 'category'])->name('articles.category');
    Route::get('/{article}', [ArticleController::class, 'show'])->name('articles.show');
});


Route::prefix('auth')->group(function () {
    Route::get('/signin', [AuthController::class, 'showRegisterForm'])->name('auth.create');
    Route::post('/signin', [AuthController::class, 'register'])->name('auth.registration');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::prefix('articles')->group(function () {
        Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('/', [ArticleController::class, 'store'])->name('articles.store');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    });
    
    Route::prefix('protected')->group(function () {
        Route::get('/dashboard', [ProtectedController::class, 'dashboard'])->name('protected.dashboard');
        Route::get('/tokens', [ProtectedController::class, 'tokens'])->name('protected.tokens');
        Route::post('/create-token', [ProtectedController::class, 'createToken'])->name('protected.createToken');
        Route::delete('/revoke-token/{tokenId}', [ProtectedController::class, 'revokeToken'])->name('protected.revokeToken');
    });
});