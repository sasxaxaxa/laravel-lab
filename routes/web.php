<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProtectedController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
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
    Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::get('/{article}', [ArticleController::class, 'show'])->name('articles.show');
});

// Регистрация и вход (только для гостей)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Выход - защищаем Sanctum
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');

// ВСЕ защищенные маршруты под Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Статьи (защищенные операции)
    Route::prefix('articles')->group(function () {
        Route::post('/', [ArticleController::class, 'store'])->name('articles.store');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    });
    
    // Комментарии
    Route::prefix('comments')->group(function () {
        Route::post('/articles/{article}', [CommentController::class, 'store'])->name('comments.store');
        
        // Модерация комментариев (только для модераторов)
        Route::middleware('can:manage-comments')->group(function () {
            Route::get('/pending', [CommentController::class, 'pending'])->name('comments.pending');
            Route::post('/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
            Route::post('/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
            Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
            Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
        });
    });
    
    // Защищенная панель (dashboard)
    Route::prefix('protected')->group(function () {
        Route::get('/dashboard', [ProtectedController::class, 'dashboard'])->name('protected.dashboard');
        Route::get('/tokens', [ProtectedController::class, 'tokens'])->name('protected.tokens');
        Route::post('/create-token', [ProtectedController::class, 'createToken'])->name('protected.createToken');
        Route::delete('/revoke-token/{tokenId}', [ProtectedController::class, 'revokeToken'])->name('protected.revokeToken');
    });
    
    // Уведомления
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::delete('/', [NotificationController::class, 'destroyAll'])->name('notifications.destroyAll');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
        Route::get('/unread-list', [NotificationController::class, 'unreadList'])->name('notifications.unreadList');
    });
});

// === ТОЛЬКО ДЛЯ ОТЛАДКИ (можно удалить после тестирования) ===

// Отладочный маршрут для Sanctum
Route::get('/debug/sanctum', function() {
    return [
        'is_authenticated' => auth()->check(),
        'user_id' => auth()->id(),
        'user' => auth()->user(),
        'sanctum_tokens' => auth()->user()?->tokens ?? 'нет пользователя',
        'session_token' => session('sanctum_token'),
    ];
})->middleware('auth:sanctum');

// Тестовый маршрут для проверки кэша (можно оставить для демонстрации)
Route::get('/test-cache', function() {
    $cacheKey = 'articles_page_1';
    $hasCache = Cache::has($cacheKey);
    
    return [
        'cache_driver' => config('cache.default'),
        'articles_page_1_cached' => $hasCache,
        'cache_entries_count' => \Illuminate\Support\Facades\DB::table('cache')->count(),
    ];
});

// Тестовый маршрут для проверки уведомлений (можно удалить после теста)
Route::get('/test-notification', function() {
    try {
        $article = \App\Models\Article::first();
        
        if (!$article) {
            return 'Нет статей в базе данных';
        }
        
        if (!$article->user_id) {
            return 'Статья не имеет user_id';
        }
        
        $author = $article->user;
        if (!$author) {
            return 'Автор статьи не найден';
        }
        
        $user = \App\Models\User::where('id', '!=', $article->user_id)->first();
        if (!$user) {
            return 'Нет других пользователей для уведомления';
        }
        
        $user->notify(new \App\Notifications\NewArticleCreated($article, $author));
        
        return 'Уведомление отправлено пользователю: ' . $user->name;
        
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }
});