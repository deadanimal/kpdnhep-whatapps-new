<?php

namespace App\Repositories\Feedback\Whatsapp;

use Log;

/**
 * Handle connection with whatsapp using waboxapp api
 * Class WaboxappRepository
 * @package App\Repositories\Feedback
 */
class WaboxappRepository
{
    /**
     * To send whatsapp message using waboxapi
     *
     * @param $contact_no
     * @param $message
     * @return false|string
     */
    public static function send($contact_no, $message, $i = 0)
    {
        $msg = 'msg-' . date('Ymdhis');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.waboxapp.com/api/send/chat");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "token=" . config('feedback.waboxapp.token') . "&uid=" . config('feedback.waboxapp.uid') . "&to=" . $contact_no . "&custom_uid=" . $msg . "&text=" . $message);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 25);

        $b = curl_exec($ch);
        $a = curl_getinfo($ch);
        $c = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        // Log::info($b);
        // Log::info($a);
        // Log::info($c);
        // Log::info($i);
        if($c != 200 && $i <= 5) {
            self::send($contact_no, $message, $i+1);
            curl_close($ch);
            return;
        }
        curl_close($ch);
        return json_encode($message);
    }
}
