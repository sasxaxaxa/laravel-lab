<?php

namespace App\Console\Commands;

use App\Mail\NewArticleNotification;
use App\Models\Article;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'mail:test 
                           {--to= : Email получателя}
                           {--article= : ID статьи}';
    
    protected $description = 'Тестирование отправки email уведомлений';

    public function handle()
    {
        $email = $this->option('to') ?? config('mail.from.address');
        $articleId = $this->option('article');
        
        if ($articleId) {
            $article = Article::find($articleId);
        } else {
            $article = Article::first() ?? Article::factory()->create();
        }
        
        $author = User::first() ?? User::factory()->create();
        
        $this->info("Отправка тестового email на: {$email}");
        $this->info("Статья: {$article->title}");
        $this->info("Автор: {$author->name}");
        
        try {
            Mail::to($email)->send(
                new NewArticleNotification($article, $author)
            );
            
            $this->info('✅ Email успешно отправлен!');
            $this->info('Проверьте почтовый ящик.');
            
        } catch (\Exception $e) {
            $this->error('❌ Ошибка при отправке email: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}