<?php

namespace App\Repositories\Feedback\Telegram;

use App\Models\Feedback\FeedTelegramDetail;

class TelegramDetailRepository
{
    /**
     * link all feed with ticket
     * @param $feed_ids
     * @param $ticket_id
     */
    public static function linkFeedWithTicket($feed_ids, $ticket_id)
    {
        $ids = explode(';', $feed_ids);

        $feed_whatsapp_id = '';
        foreach($ids as $id) {
            $feedDetail = FeedTelegramDetail::where('id', $id)
                ->update(['is_ticketed' => 1]);

		$fd= FeedTelegramDetail::find($id);

            if($fd && $feed_whatsapp_id === '') {
                $feed_whatsapp_id = $fd->feed_whatsapp_id;
            }
        }

        TelegramRepository::updateActiveStatus($feed_whatsapp_id, 0, null);
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FeedTelegramDetail::class;
    }
}
