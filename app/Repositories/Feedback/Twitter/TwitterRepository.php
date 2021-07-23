<?php

namespace App\Repositories\Feedback\Twitter;

use App\Models\Feedback\FeedTwitter;

class TwitterRepository
{
    public static function firstOrCreate($id, $data, $feed_twitter_profile_id)
    {
         return FeedTwitter::firstOrCreate(
            ['id' => $id],
            [
                'id' => $id,
                'profile_id' => $feed_twitter_profile_id,
                'reply_to_id' => $data->in_reply_to_status_id_str,
                'message' => $data->text,
                'created_time' => $data->created_at,
            ]
        );
    }
}