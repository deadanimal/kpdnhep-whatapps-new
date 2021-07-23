<?php

namespace App\Repositories\Feedback\Twitter;

use App\Models\Feedback\FeedTwitterProfile;

class TwitterProfileRepository
{
    public static function firstOrCreate($id, $data)
    {
        return FeedTwitterProfile::firstOrCreate(
            ['id' => $data->user->id_str],
            [
                'id' => $data->user->id_str,
                'name' => $data->user->name,
                'screen_name' => $data->user->screen_name,
                'avatar_url' => $data->user->profile_image_url_https,
            ]
        );
    }
}