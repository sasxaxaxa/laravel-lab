<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProtectedController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// Главная и публичные маршруты
Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/gallery/{id}', [MainController::class, 'gallery'])->name('gallery');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');

// Статьи (публичная часть)
Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/category/{category}', [ArticleController::class, 'category'])->name('articles.category');
    Route::get('/{article}', [ArticleController::class, 'show'])->name('articles.show');
});

// Регистрация и вход (только для гостей)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ВСЕ защищенные маршруты ДОЛЖНЫ быть под auth:sanctum
// Выход тоже должен быть защищен Sanctum
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Комментарии - защищаем Sanctum
Route::middleware('auth:sanctum')->prefix('comments')->group(function () {
    Route::post('/articles/{article}', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    
    // Отдельная группа с дополнительной проверкой прав
    Route::middleware('can:manage-comments')->group(function () {
        Route::get('/pending', [CommentController::class, 'pending'])->name('comments.pending');
    });
});

// Все остальные защищенные маршруты под Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Статьи (защищенные операции)
    Route::prefix('articles')->group(function () {
        Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('/', [ArticleController::class, 'store'])->name('articles.store');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    });
    
    // Защищенная панель (dashboard)
    Route::prefix('protected')->group(function () {
        Route::get('/dashboard', [ProtectedController::class, 'dashboard'])->name('protected.dashboard');
        Route::get('/tokens', [ProtectedController::class, 'tokens'])->name('protected.tokens');
        Route::post('/create-token', [ProtectedController::class, 'createToken'])->name('protected.createToken');
        Route::delete('/revoke-token/{tokenId}', [ProtectedController::class, 'revokeToken'])->name('protected.revokeToken');
    });
});


// Временно добавьте в routes/web.php
Route::get('/debug/sanctum', function(Request $request) {
    return [
        'is_authenticated' => auth()->check(),
        'user_id' => auth()->id(),
        'user' => auth()->user(),
        'sanctum_tokens' => auth()->user()?->tokens ?? 'нет пользователя',
        'session_token' => session('sanctum_token'),
        'headers' => $request->headers->all(),
    ];
})->middleware('auth:sanctum');