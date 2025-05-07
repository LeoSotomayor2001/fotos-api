<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;

    protected $publicacion;
    protected $comentario;
    public function __construct(Post $publicacion, Comment $comentario)
    {
        $this->publicacion = $publicacion;
        $this->comentario = $comentario;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->comentario->user->username . ' ha comentado en tu publicación: ' . $this->publicacion->title,
            'image' => $this->comentario->user->image,
            'url' => '/dashboard/post/' . $this->publicacion->id,
        ];
    }
}
