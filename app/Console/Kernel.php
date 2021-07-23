<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        '\App\Console\Commands\CalculatePreCloseDuration',
        '\App\Console\Commands\CalculateFirstActionDuration',
        '\App\Console\Commands\ComplaintDocnoFokCheck',
        '\App\Console\Commands\cronMaklumatTidakLengkap',
        '\App\Console\Commands\emailnotification',
        '\App\Console\Commands\Emailnotification21day',
        '\App\Console\Commands\fetchEmail',
        '\App\Console\Commands\LogWaboxappStatus',
        '\App\Console\Commands\GenerateExcelCaseInfo',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cronMaklumatTidakLengkap:tukarstatus')->daily();
//        $schedule->command('eaduan:logwaboxappstatus')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('eaduan:complaintdocnofokcheck')->daily();
        $schedule->command('eaduan:emailnotification')->weekdays()->at('01:00');
        $schedule->command('eaduan:emailnotification21day')->weekdays()->at('01:30');

//        $schedule->command('eaduan:fetchEmail')
  //          ->everyFiveMinutes()
//                ->appendOutputTo('storage/logs/scheduler.log')
    //        ->withoutOverlapping(30);
      
  $schedule->command('eaduan:calcprecloseduration')->monthlyOn(1, '03:00');

        $schedule->command('eaduan:calcfirstactionduration')->monthlyOn(1, '04:00');
        $schedule->command('eaduan:generateexcelcaseinfo')->dailyAt('04:00');
        $schedule->command('eaduan:generateexcelcaseinfo')->dailyAt('13:15');
            // ->daily()->at('04:00')->at('13:15');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
