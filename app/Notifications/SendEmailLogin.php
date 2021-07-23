<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailLogin extends Notification
{
    use Queueable;
    public $user;
    public $request;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $request)
    {
        $this->username = $user->username;
        $this->password = $request->password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
            $subject = 'Pendaftaran Pengguna eAduan 2.0';
            $line1 = 'Berikut adalah maklumat log masuk';
            $line2 = 'ID Pengguna : '.$this->username;
            // $line3 = 'Kata Laluan : '. $this->password;
        return (new MailMessage)
                    ->subject($subject)
                    ->line($line1)
                    ->line($line2)
                    // ->line($line3)
                    ;
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
            //
        ];
    }
}
