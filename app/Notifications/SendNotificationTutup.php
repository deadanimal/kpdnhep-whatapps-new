<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendNotificationTutup extends Notification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    use Queueable;
    public $penutupan;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Penutupan $mPenutupan)
    {
        $this->penutupan = $mPenutupan;
    }
    
    public function toMail($notifiable)
    {
        $mEmail = Email::where(['email_type' => '07'])->first();
        $contentEmail = $mEmail->header.'<br>'.$mEmail->body.'<br>'.$mEmail->footer;
        
        return (new MailMessage)
                    ->subject('eAduanV2 : '.$mEmail->title)
                    ->line('No Aduan : '.$this->penutupan->CA_CASEID)
                    ->line($contentEmail)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
