<?php

namespace App\Repositories\Feedback\Telegram;

use Zttp\Zttp;

/**
 * Handle request to Telegram Bot API server.
 * Reference https://core.telegram.org/bots/api
 * Class TelegramAPIRepository
 * @package App\Repositories\Feedback\Telegram
 */
class TelegramAPIRepository
{
    public static function getFileDetailsByFileId($file_id)
    {
//        $response =  Zttp::get('https://api.telegram.org/bot641281643:AAHjO-GcdlGkZUEtOsr6xlTU9pNloajRm1Y/getFile?file_id=AgACAgUAAxkBAAICvV6w0g7qJvy13r9ZtsIrNVVAwZVnAAJwqjEbvlWJVWsscYr_-gNXh_Mba3QAAwEAAwIAA3kAA_oVAAIZBA');
//
//        return $response->json();

        $response = Zttp::get('https://api.telegram.org/bot' . config('feedback.telegram_bot_chat.token') . '/getFile', [
            'file_id' => $file_id,
        ]);

        return $response->json();
    }

    public static function getFileBinary($file_id, $path)
    {
        return Zttp::get('https://api.telegram.org/bot' . config('feedback.telegram_bot_chat.token') . '/' . $path);
    }

    public static function getAttachmentUrl($attachment_url, $attachment_mime)
    {
        $file = TelegramAPIRepository::getFileDetailsByFileId($attachment_url);
        return 'https://api.telegram.org/file/bot' . config('feedback.telegram_bot_chat.token') . '/' . $file['result']['file_path'];
    }

    public static function isInput($bot_id)
    {
        return $bot_id !== config('feedback.telegram_bot_chat.bot_id') ? true : false;
    }
}
