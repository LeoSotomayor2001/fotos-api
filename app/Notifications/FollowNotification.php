<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowNotification extends Notification
{
    use Queueable;

    protected $follower;
    public function __construct(User $follower)
    {
        $this->follower = $follower;
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
            'title' => $this->follower->username . ' ha comenzado a seguirte.',
            'image' => $this->follower->image,
            'url' => '/dashboard/profile/' . $this->follower->username,
        ];
    }
    
}
