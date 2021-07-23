<?php

namespace App\Repositories\Feedback\BotStatus;

use App\Models\Feedback\FeedEmail;
use App\Models\Feedback\FeedEmailDetail;
use Webklex\IMAP\Facades\Client;

class BotStatusSenderRepository
{
    /**
     * Send status to telegram bot
     *
     * @param $type
     */
    public static function send($type)
    {
        switch ($type) {
            case 'whatsapp':
                $message = 'Waboxapp ' . date('Y-m-d H:i:s');
                break;
            default:
                $message = $type . ' ' . date('Y-m-d H:i:s');
        }

        $data = [
            'chat_id' => config('feedback.status.chat_id'),
            'text' => $message,
        ];

        file_get_contents("https://api.telegram.org/bot" . config('feedback.status.bot_token') . "/sendMessage?" . http_build_query($data));

        return;
    }
}
