<?php

namespace App\Http\Controllers\Feedback\Twitter;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedTwitter;
use App\Models\Feedback\FeedTwitterProfile;
use App\Models\Feedback\FeedSetting;
use App\Repositories\Feedback\Twitter\TwitterProfileRepository;
use App\Repositories\Feedback\Twitter\TwitterRepository;
use Facebook\Exceptions\FacebookSDKException;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class TimelineController extends Controller
{
    private $client;
    private $oauth;

    public function __construct()
    {
        $stack = HandlerStack::create();

        $token = FeedSetting::where('type', 'TW')->first();

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

    public function retrieveMentionTimeline()
    {
        $response = $this->client->get('statuses/mentions_timeline.json');
        $feeds = json_decode($response->getBody()->getContents());

        foreach ($feeds as $feed) {
            $feed_twitter_profile = TwitterProfileRepository::firstOrCreate($feed->user->id_str, $feed);

            if($feed->in_reply_to_status_id_str != null) {
                $this->retriveSingleTweet($feed->in_reply_to_status_id_str);
            }

            TwitterRepository::firstOrCreate($feed->id_str, $feed, $feed_twitter_profile->id);
        }

        return;
    }

    public function retriveSingleTweet($id)
    {
        $response = $this->client->get('statuses/show.json?id='.$id);
        $feed = json_decode($response->getBody()->getContents());

        $feed_twitter_profile = TwitterProfileRepository::firstOrCreate($feed->user->id_str, $feed);

        if($feed->in_reply_to_status_id_str != null) {
            $this->retriveSingleTweet($feed->in_reply_to_status_id_str);
        }

        TwitterRepository::firstOrCreate($feed->id_str, $feed, $feed_twitter_profile->id);
    }
}
