<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInactiveNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $threshold;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $threshold = 1)
    {
        $this->user = $user;
        $this->threshold = $threshold;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Using database notifications
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'message' => 'User ' . $this->user->name . ' has been inactive for more than ' . $this->threshold . ' minute' . ($this->threshold > 1 ? 's' : '') . '.',
            'type' => 'user_inactive',
            'threshold_minutes' => $this->threshold
        ];
    }
}