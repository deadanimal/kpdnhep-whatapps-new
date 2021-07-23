<?php

namespace App\Listeners;

use App\Events\CallCenterSubmit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Branch;

//class EmailToNonBpgk implements ShouldQueue
class EmailToNonBpgk
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
     * @param  CallCenterSubmit  $event
     * @return void
     */
    public function handle(CallCenterSubmit $event)
    {
        $DeptCd = explode(' ', $event->request->CA_CMPLCAT);
        $mBranch = Branch::where(['BR_DEPTCD' => $DeptCd[0],'BR_STATUS' => 1])->first();
        $mUser = User::where(['brn_cd' => $mBranch->BR_BRNCD,'status' => 1])->get()->each(function($mUser) {
            logger()->info($mUser);
            $mUser->notify(new \App\Notifications\EmailToNonBpgk($mUser));
        });
//        $User = User::findOrFail(1);
//        $User->notify(new \App\Notifications\EmailToNonBpgk($User));
//        logger()->info($event->request->CA_CMPLCAT);
//        logger()->info($mBranch);
    }
}
