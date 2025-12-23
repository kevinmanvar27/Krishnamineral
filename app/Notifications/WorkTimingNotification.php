<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WorkTimingNotification extends Notification
{
    use Queueable;
    
    protected $minutes;
    protected $taskDescription;

    public function __construct($minutes = null, $taskDescription = null)
    {
        $this->minutes = $minutes;
        $this->taskDescription = $taskDescription;
    }

    public function via($notifiable)
    {
        return ['database']; // You can add 'mail', 'broadcast', etc. as needed
    }

    public function toArray($notifiable)
    {
        $taskDescription = $this->taskDescription ?: 'your task';
        $minutes = $this->minutes ?: $notifiable->work_timing_initiate_checking;
        
        return [
            'message' => "Time's up! You've been working on {$taskDescription} for {$minutes} minutes.",
            'title' => 'Task Time Alert',
            'user_id' => $notifiable->id,
            'minutes_threshold' => $minutes,
            'task_description' => $taskDescription,
        ];
    }
}