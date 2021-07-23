<?php

namespace App\Repositories\Feedback\Whatsapp;

use App\Models\Feedback\FeedWhatsappDetail;

class WhatsappDetailRepository
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
            $feedDetail = FeedWhatsappDetail::where('id', $id)
                ->update(['is_ticketed' => 1]);

		$fd= FeedWhatsappDetail::find($id);

            if($fd && $feed_whatsapp_id === '') {
                $feed_whatsapp_id = $fd->feed_whatsapp_id;
            }
        }

        WhatsappRepository::updateActiveStatus($feed_whatsapp_id, 0, null);
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FeedWhatsappDetail::class;
    }
}
