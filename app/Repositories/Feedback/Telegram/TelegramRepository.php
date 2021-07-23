<?php

namespace App\Repositories\Feedback\Telegram;

use App\Models\Feedback\FeedTelegram;
use Log;
use App\Models\Feedback\FeedWhatsapp;
use Zttp\Zttp;

class TelegramRepository
{
    /**
     * Update feed whatsapp active status
     * @param $id
     * @param $is_active
     * @param null $supporter_id
     */
    public static function updateActiveStatus($id, $is_active, $supporter_id = null)
    {
        FeedWhatsapp::where('id', $id)
            ->update([
                'is_active' => $is_active,
                'supporter_id' => $supporter_id // clean it
            ]);

        return;
    }

    /**
     * Check if the feed is mine or redirect it if something else happen.
     * @param $id
     * @param $redirect
     * @return FeedWhatsapp|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */
    public static function itIsMineOrRedirect($id, $redirect)
    {
        $feedback = FeedTelegram::where('id', $id)
            ->where('supporter_id', auth()->user()->id)
            ->first();

        if (!$feedback) {
            return redirect(route($redirect));
        }

        return $feedback;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FeedWhatsapp::class;
    }

    /**
     * @param $chat_id
     * @param $text
     * @return false|string|void
     */
    public static function sendMessageToReceiver($chat_id, $text)
    {
        $response = Zttp::get('https://api.telegram.org/bot' . config('feedback.telegram_bot_chat.token') . '/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text
        ]);

        Log::debug('telegramRep::sendMessageToReceiver', [$response->json()]);

        return $response->isOk();
    }

    public static function getPhotoByFileId($file_id)
    {
        /**
         * get file_path
         */
        $file = TelegramAPIRepository::getFileDetailsByFileId($file_id);

        /**
         * get binary
         */
        TelegramAPIRepository::getFileBinary($file_id, $file->file_path);
    }
}