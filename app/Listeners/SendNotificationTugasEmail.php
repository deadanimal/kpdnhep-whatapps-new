<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class SendNotificationTugasEmail implements ShouldQueue
{
    use InteractsWithQueue;
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
     * @param  SendNotificationTugas  $event
     * @return void
     */
    public function handle(TaskAssigned $event)
    {
        $mProfilPenyiasat = User::findOrFail($event->profilPenyiasat->id);
        
        $mProfilPenyiasat->notify(new \App\Notifications\SendNotificationTugas($mProfilPenyiasat));
    }
}
