<?php

namespace App\Repositories;

use Auth;

class TelegramBotRepository
{
    /**
     * Send status to telegram bot
     *
     * @param $type
     * @param $message
     * @param $file
     * @param $line
     * @param $request_type
     * @param $url
     * @param $param
     */
    public static function send($type, $message, $file, $line, $request_type, $url, $param)
    {
        $data = [
            'chat_id' => config('feedback.telegram_bot.chat_id'),
            'text' => '[' . config('app.url') . '] ' . ' ' . $type . ' ' . date('Y-m-d H:i:s') . PHP_EOL
                . 'MSG ' . json_encode(mb_substr($message, 0, 3900)) // limit to first 3900 char
                . "FILE " . $file . PHP_EOL // limit to first 3900 char
                . "LN " . $line . PHP_EOL// limit to first 3900 char
                . "URL " . $url . PHP_EOL
                . 'REQ ' . $request_type . PHP_EOL
                . "PAR " . $param . PHP_EOL
                . "UID " . (Auth::user()->id ?? '-') . PHP_EOL,
        ];

        file_get_contents("https://api.telegram.org/bot" . config('feedback.telegram_bot.bot_token') . "/sendMessage?" . http_build_query($data));

        return;
    }

    public static function customMessage($message)
    {
        $data = [
            'chat_id' => config('feedback.telegram_bot.chat_id'),
            'text' => 'MSG ' . json_encode(mb_substr($message, 0, 3900)), // limit to first 3900 char
        ];

        file_get_contents("https://api.telegram.org/bot" . config('feedback.telegram_bot.bot_token') . "/sendMessage?" . http_build_query($data));

        return;
    }
}
