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
use Illuminate\Support\Facades\Notification;

class VeryLongJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function handle()
    {
        try {
            \Log::info('VeryLongJob started for article: ' . $this->article->title);
            
            // Проверяем, есть ли пользователи
            $userCount = User::count();
            
            if ($userCount === 0) {
                \Log::warning('No users found. Creating test user...');
                
                // Создаем тестового пользователя если нет
                $testUser = User::create([
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'password' => bcrypt('password'),
                ]);
                
                \Log::info('Test user created with ID: ' . $testUser->id);
            }
            
            // Получаем всех пользователей кроме автора
            $users = User::where('id', '!=', $this->article->user_id)->get();
            
            \Log::info('Found ' . $users->count() . ' users to notify');
            
            if ($users->count() > 0) {
                // Отправляем уведомления
                foreach ($users as $user) {
                    $user->notify(new NewArticleCreated($this->article));
                }
                \Log::info('Notifications sent successfully');
            }
            
            \Log::info('VeryLongJob completed successfully');
            
        } catch (\Exception $e) {
            \Log::error('VeryLongJob failed: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            throw $e;
        }
    }
    
    public function failed(\Exception $exception)
    {
        \Log::error('VeryLongJob marked as failed: ' . $exception->getMessage());
    }
}