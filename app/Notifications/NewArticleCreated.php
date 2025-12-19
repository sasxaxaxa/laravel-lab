<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewArticleCreated extends Notification
{
    use Queueable;

    public $article;

    /**
     * Create a new notification instance.
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Если нет настроек почты, используйте только database
        return ['database']; // Или ['mail', 'database'] если настроена почта
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новая статья: ' . $this->article->title)
            ->line('Создана новая статья: "' . $this->article->title . '"')
            ->action('Посмотреть статью', url('/articles/' . $this->article->slug))
            ->line('Спасибо за использование нашего приложения!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'author' => $this->article->author,
            'message' => 'Создана новая статья: ' . $this->article->title,
            'url' => '/articles/' . $this->article->slug,
            'created_at' => $this->article->created_at,
        ];
    }
}