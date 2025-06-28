<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArticleRevertedNotification extends Notification
{
    use Queueable;

    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "La tua modifica all'articolo \"{$this->article->title}\" è stata rifiutata e sono stati ripristinati i dati precedenti.",
            'article_id' => $this->article->id,
        ];
    }
}
