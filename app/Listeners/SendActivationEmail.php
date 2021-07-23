<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
//use App\Notifications\SendActivationEmail;
use App\User;

class SendActivationEmail implements ShouldQueue
//class SendActivationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $user = User::findOrFail($event->user->id);
//        $user->notify(new \App\Notifications\SendActivationEmail());
        
        $user->notify(new \App\Notifications\SendActivationEmail($user));
//        Mail::send('mails.confirmation', ['name' => 'test'], function($message) use($event) {
//            $message->from('me@gmail.com', 'Christian Nwamba');
//            $message->to($event->user->email);
//            $message->subject('Registration Confirmation From Queue');
//        });
    }
}
