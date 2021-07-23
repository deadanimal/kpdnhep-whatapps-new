<?php

namespace App\Repositories\Feedback\Facebook;

use App\Models\Feedback\FeedSetting;

/**
 * Handle connection with facebook using waboxapp api
 * Class FacebookRepository
 * @package App\Repositories\Feedback\Facebook
 */
class FacebookRepository
{
    /**
     * Fetch all post in the page.
     * @param $fb
     * @param $fields
     * @param $now
     * @return mixed
     */
    public static function fetchPost($fb, $fields, $now)
    {
        $api = self::setDefaultAccessToken($fb);

        return $api->get('/'.config('facebook.app_id').'/feed?fields=' . $fields)
            ->getGraphEdge()
            ->asArray();
    }

    /**
     * Set default access token.
     * @param $fb
     * @return mixed
     */
    public static function setDefaultAccessToken($fb)
    {
        return $fb->setDefaultAccessToken(FeedSetting::where('type', 'FB')->first()->token);
    }
}