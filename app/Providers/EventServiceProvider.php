<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserRegistered' => [
            'App\Listeners\SendActivationEmail',
        ],
        'App\Events\CallCenterSubmit' => [
            'App\Listeners\EmailToNonBpgk',
        ],
        'App\Events\ComplaintReceived' => [
            'App\Listeners\SendNotificationEmail',
        ],
        'App\Notifications\SendNotification' => [
            'App\Listeners\SendNotificationEmail',
        ],
        'App\Events\TaskAssigned' => [
            'App\Listeners\SendNotificationTugasEmail',
        ],
        'App\Notifications\SendNotificationTugas' => [
            'App\Listeners\SendNotificationTugasEmail',
        ],
        'App\Events\ComplaintClosed' => [
            'App\Listeners\SendNotificationTutupEmail',
        ],
        'App\Notifications\SendNotificationTutup' => [
            'App\Listeners\SendNotificationTutupEmail',
        ],
//        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
//            'SocialiteProviders\\Twitter\\TwitterExtendSocialite@handle',
//        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
