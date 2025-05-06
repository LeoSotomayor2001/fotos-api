<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LikeNotification extends Notification
{
    use Queueable;

    protected $likeUser;
    protected $publicacion;
    public function __construct(User $likeUser, Post $publicacion)
    {
        $this->likeUser = $likeUser;
        $this->publicacion = $publicacion;
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


    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->likeUser->id,
            'post_id' => $this->publicacion->id,
            'title' => $this->likeUser->username . ' Ha reaccionado a la publicación: ' . $this->publicacion->title,
            'image' => $this->publicacion->user->image,
            'post_user_id' => $this->publicacion->user_id,
            'post_user_username' => $this->publicacion->user->username,
            'url' => '/dashboard/post/' . $this->publicacion->id,
        ];
    }
}
