<?php

namespace App\Repositories\Feedback;

use App\Models\Feedback\FeedSetting;
use Cache;

/**
 * Handle Feed Setting
 * Class FacebookRepository
 * @package App\Repositories\Feedback
 */
class FeedSettingRepository
{
    /**
     * Update or create new entry in the system and update cache.
     * @param string $name
     * @param string $email
     * @param string $type
     * @param string $token
     * @param string|null $token_secret
     * @return mixed
     */
    public static function updateOrCreate(string $name, string $email, string $type, string $token, $token_secret = null)
    {
        FeedSetting::updateOrCreate(
            [
                'email' => $email,
                'type' => $type
            ],
            [
                'token' => $token,
                'token_secret' => $token_secret,
                'name' => $name
            ]
        );

        return self::setByType($type);
    }

    /**
     * Get data by type or return from cache.
     * @param string $type
     * @return mixed
     */
    public static function getByType(string $type)
    {
        return Cache::remember('feed-setting:' . $type, 36000, function () use ($type) {
            return FeedSetting::where('type', $type)
                ->first();
        });
    }

    /**
     * Clear cache by type and reset it again.
     * @param string $type
     * @return mixed
     */
    public static function setByType(string $type)
    {
        Cache::forget('feed-setting:' . $type);

        return self::getByType($type);
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FeedSetting::class;
    }
}