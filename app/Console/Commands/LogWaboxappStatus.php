<?php

namespace App\Console\Commands;

use App\Mail\WaboxappDown;
use App\Repositories\Feedback\BotStatus\BotStatusSenderRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class LogWaboxappStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eaduan:logwaboxappstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To log waboxapp status if the status is error';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $endpoint = "https://www.waboxapp.com/api/status/" . config('feedback.waboxapp.uid') . "?token=" . config('feedback.waboxapp.token');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);

        if (!isset($result['success'])) {
            $old = file_get_contents('storage/logs/waboxapp.log');
            $file = fopen('storage/logs/waboxapp.log', 'w');
            fwrite($file, date('Y-m-d H:i:s') . " - " . $result['error'] . "\n" . $old);
            fclose($file);
            echo 'error';

//            Mail::to(config('feedback.waboxapp.alert_1'))
//                ->cc([config('feedback.waboxapp.alert_2'), config('feedback.waboxapp.alert_3'), config('feedback.waboxapp.alert_4')])
//                ->send(new WaboxappDown());

            BotStatusSenderRepository::send('whatsapp');
        } else {
            $old = file_get_contents('storage/logs/waboxapp.log');
            $file = fopen('storage/logs/waboxapp.log', 'w');
            fwrite($file, date('Y-m-d H:i:s') . " - " . 'OK' . "\n" . $old);
            fclose($file);
            echo 'ok';

//            Mail::to(config('feedback.waboxapp.alert_1'))
//                ->cc([config('feedback.waboxapp.alert_2'), config('feedback.waboxapp.alert_3'), config('feedback.waboxapp.alert_4')])
//                ->send(new WaboxappDown());
//
//            BotStatusSenderRepository::send('whatsapp');
        }
    }
}
