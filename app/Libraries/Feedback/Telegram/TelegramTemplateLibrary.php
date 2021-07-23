<?php

namespace App\Libraries\Feedback\Telegram;

use App\Libraries\Feedback\FeedbackTemplateLibrary;
use App\Repositories\Feedback\Telegram\TelegramRepository;

class TelegramTemplateLibrary
{
    /**
     * To prepare template and send to receiver
     * @param $telegram_user
     * @param $telegram_detail
     * @param $is_edited_message
     * @param $message
     * @param $is_first_time
     */
    public static function prepareTemplateAndSendToReceiver($telegram_user, $telegram_detail, $is_edited_message, $message, $is_first_time)
    {
        if (isset($telegram_detail['is_input']) && $telegram_detail['is_input'] === 1 && $is_edited_message === 0) {
            $template = TelegramTemplateLibrary::checkTemplate($message, $is_first_time);
            if ($template !== '') {
                TelegramTemplateLibrary::sendTemplate($telegram_user['user_id'], $template, '', '', false);
            }
        }
    }
    /**
     * @param array $message
     * @param bool $is_first_time
     * @return mixed|string
     */
    public static function checkTemplate($message, $is_first_time = false)
    {
        return FeedbackTemplateLibrary::checkTemplate($message['text'] ?? '-', $is_first_time);
    }

    /**
     * @param string $receiver_unique_id
     * @param string $template
     * @param string $data
     * @param string $message
     * @param bool $is_reply
     * @return mixed|string
     */
    public static function sendTemplate($receiver_unique_id, $template, $data = '', $message = '', $is_reply = false)
    {
        if ($is_reply === false) {
            $message = FeedbackTemplateLibrary::prepareTemplate($template, $data);
        }

        return TelegramRepository::sendMessageToReceiver($receiver_unique_id, $message);
    }
}
