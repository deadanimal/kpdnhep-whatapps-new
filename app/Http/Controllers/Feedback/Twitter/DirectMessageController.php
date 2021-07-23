<?php

namespace App\Http\Controllers\Feedback\Twitter;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedSetting;
use App\Models\Feedback\FeedTwitter;
use App\Models\Feedback\FeedTwitterProfile;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class DirectMessageController extends Controller
{
    private $client;
    private $oauth;

    public function __construct()
    {
        $stack = HandlerStack::create();

        $token = FeedSetting::where('type', 'FB')->first();

        $middleware = new Oauth1([
            'consumer_key' => config('services.twitter.consumer_key'),
            'consumer_secret' => config('services.twitter.consumer_secret'),
            'token' => $token->token,
            'token_secret' => $token->token_secret
        ]);

        $stack->push($middleware);

        $this->client = new Client([
            'base_uri' => 'https://api.twitter.com/1.1/',
            'handler' => $stack,
            'auth' => 'oauth'
        ]);
    }

    public function retrieveDirectMessages()
    {
        $response = $this->client->get('direct_messages/events/list.json');
        $feeds = json_decode($response->getBody()->getContents());

        foreach ($feeds as $feed) {
            $tw_profile = FeedTwitterProfile::firstOrCreate(
                ['id' => $feed->user->id_str],
                [
                    'id' => $feed->user->id_str,
                    'name' => $feed->user->name,
                    'screen_name' => $feed->user->screen_name,
                    'avatar_url' => $feed->user->profile_image_url_https,
                ]
            );

            FeedTwitter::firstOrCreate(
                ['id' => $feed->id_str],
                [
                    'id' => $feed->id_str,
                    'profile_id' => $tw_profile->id,
                    'reply_to_id' => $feed->in_reply_to_status_id_str,
                    'message' => $feed->text,
                    'created_time' => $feed->created_at,
                ]
            );
        }
    }
}
