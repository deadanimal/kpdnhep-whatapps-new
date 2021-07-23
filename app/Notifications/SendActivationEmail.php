<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendActivationEmail extends Notification
{
    use Queueable;
    public $user;
    public $request;
    public $local;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $request, $local)
    {
        $this->username = $user->username;
        $this->password = $request->password;
        $this->local = $local;
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
        if($this->local == 'ms'){
            $subject = 'Pendaftaran eAduan 2.0';
            $line1 = 'Berikut adalah maklumat log masuk';
            $line2 = 'No. Kad Pengenalan/Passport : '.$this->username;
            // $line3 = 'Kata Laluan : '. $this->password;
            $line4 = 'Terima Kasih kerana menggunakan aplikasi ini.';
            $salutation = '';
        } else {
            $subject = 'eAduan 2.0 Registration ';
            $line1 = 'Your log in details as below';
            $line2 = 'Identification No./ Passport : '.$this->username;
            // $line3 = 'Password : '. $this->password;
            $line4 = 'Thank you for using this application.';
            $salutation = '';
        }
        return (new MailMessage)
                    ->subject($subject)
                    ->line($line1)
                    ->line($line2)
                    // ->line($line3)
//                    ->action('Verify', url('/verify-email',$this->token))
                    ->line($line4)
                    ->salutation($salutation);
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
