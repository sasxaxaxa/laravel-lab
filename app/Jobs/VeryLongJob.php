<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\User;
use App\Notifications\NewArticleCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class VeryLongJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $article;
    public $author;

    public function __construct(Article $article)
{
    $this->article = $article;
    
    // Загружаем пользователя с отношением
    if (!$article->relationLoaded('user')) {
        $article->load('user');
    }
    
    $this->author = $article->user;
    
    // Если нет автора, используем первого пользователя
    if (!$this->author) {
        $this->author = User::first();
    }
}

    public function handle()
    {
        try {
            \Log::info('VeryLongJob started for article: ' . $this->article->title);
            
            // Имитация долгой задачи
            sleep(10);
            
            // Проверяем, есть ли автор у статьи
            if (!$this->article->user) {
                \Log::error('Article has no author! Article ID: ' . $this->article->id);
                
                // Попробуем найти автора
                $this->author = User::find($this->article->user_id);
                
                if (!$this->author) {
                    \Log::error('Author not found for user_id: ' . $this->article->user_id);
                    // Создаем временного автора для теста
                    $this->author = User::first();
                    if (!$this->author) {
                        throw new \Exception('No users found in database');
                    }
                }
            } else {
                $this->author = $this->article->user;
            }
            
            \Log::info('Author: ' . ($this->author ? $this->author->name : 'NULL'));
            
            // Проверяем, есть ли пользователи
            $users = User::where('id', '!=', $this->article->user_id)->get();
            
            \Log::info('Found ' . $users->count() . ' users to notify');
            
            if ($users->count() > 0) {
                // Отправляем уведомления с обновленным конструктором
                foreach ($users as $user) {
                    $user->notify(new NewArticleCreated($this->article, $this->author));
                }
                \Log::info('Notifications sent successfully');
            } else {
                \Log::warning('No users to notify (except author)');
            }
            
            // Очищаем кэш главной страницы после отправки уведомлений
            $this->clearArticlesCache();
            
            // Очищаем кэш самой статьи
            Cache::forget('article_' . $this->article->id . '_with_comments');
            
            \Log::info('VeryLongJob completed successfully. Cache cleared.');
            
        } catch (\Exception $e) {
            \Log::error('VeryLongJob failed: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            throw $e;
        }
    }
    
    /**
     * Вспомогательный метод для очистки кэша статей на главной
     */
    private function clearArticlesCache()
    {
        try {
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
            
            \Log::info('Cleared cache for ' . $totalPages . ' pages');
            
        } catch (\Exception $e) {
            \Log::error('Failed to clear cache: ' . $e->getMessage());
        }
    }
    
    /**
     * Обработка неудачного выполнения задания
     * ВАЖНО: В PHP 8+ используем \Throwable вместо \Exception
     */
    public function failed(\Throwable $exception)
    {
        \Log::error('VeryLongJob marked as failed: ' . $exception->getMessage());
        \Log::error('Exception class: ' . get_class($exception));
        \Log::error('Trace: ' . $exception->getTraceAsString());
        
        // Можно также отправить уведомление администратору о проваленной задаче
        if ($this->author) {
            \Log::error('Job failed for article by author: ' . $this->author->name);
        }
    }
}