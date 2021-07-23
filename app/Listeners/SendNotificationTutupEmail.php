<?php

namespace App\Listeners;

use App\Events\ComplaintClosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotificationTutupEmail implements ShouldQueue
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
     * @param  SendNotificationTutup  $event
     * @return void
     */
    public function handle(ComplaintClosed $event)
    {
        $mPenutupan = \App\Penutupan::findOrFail($event->penutupan->CA_CASEID);
        
        $mPenutupan->notify(new \App\Notifications\SendNotificationTutup($mPenutupan));
    }
}
