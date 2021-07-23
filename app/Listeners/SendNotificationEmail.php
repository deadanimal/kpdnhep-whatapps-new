<?php

namespace App\Listeners;

use App\Events\ComplaintReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Email;

class SendNotificationEmail implements ShouldQueue
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
     * @param  ComplaintReceived  $event
     * @return void
     */
    public function handle(ComplaintReceived $event)
    {
        $mPenugasan = \App\Penugasan::findOrFail($event->penugasan->CA_CASEID);
        
        $mPenugasan->notify(new \App\Notifications\SendNotification($mPenugasan));
    }
}
