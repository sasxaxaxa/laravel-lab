<?php

namespace App\Notifications;

use App\Models\Article;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewArticleCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $article;
    protected $author;

    public function __construct(Article $article, User $author)
    {
        $this->article = $article;
        $this->author = $author;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новая статья: ' . $this->article->title)
            ->line('Создана новая статья: "' . $this->article->title . '"')
            ->action('Посмотреть статью', url('/articles/' . $this->article->id))
            ->line('Спасибо за использование нашего приложения!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'message' => 'Создана новая статья: ' . $this->article->title,
            'url' => route('articles.show', $this->article->id),
            'created_at' => now()->toDateTimeString(),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}