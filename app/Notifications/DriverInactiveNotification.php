<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class DriverInactiveNotification extends Notification
{
    use Queueable;
    
    public $incrementing = true;
    protected $keyType = 'int';

    protected $purchase;
    protected $driver;
    protected $threshold;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($purchase, $driver, $threshold = 1)
    {
        $this->purchase = $purchase;
        $this->driver = $driver;
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
            'purchase_id' => $this->purchase->id,
            'driver_id' => $this->driver->id,
            'driver_name' => $this->driver->name,
            'message' => 'Driver ' . $this->driver->name . ' assigned to purchase #' . $this->purchase->id . ' has been inactive for more than ' . $this->threshold . ' minute' . ($this->threshold > 1 ? 's' : '') . '.',
            'type' => 'driver_inactive',
            'threshold' => $this->threshold
        ];
    }
}