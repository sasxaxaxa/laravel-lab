<?php

namespace App\Mail;

use App\Models\Article;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewArticleNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $article;
    public $author;
    public $moderator;

    /**
     * Create a new message instance.
     */
    public function __construct(Article $article, User $author, User $moderator = null)
    {
        $this->article = $article;
        $this->author = $author;
        $this->moderator = $moderator;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Новая статья: ' . $this->article->title,
            from: config('mail.from.address'),
            to: $this->moderator ? $this->moderator->email : config('mail.to_address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.articles.new',
            with: [
                'article' => $this->article,
                'author' => $this->author,
                'moderator' => $this->moderator,
                'adminUrl' => url('/admin/articles'),
                'articleUrl' => route('articles.show', $this->article),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}