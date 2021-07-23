<?php

namespace App\Http\Controllers\Feedback\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedFacebook;
use App\Models\Feedback\FeedFacebookDetail;
use App\Models\Feedback\FeedFacebookProfile;
use App\Models\Feedback\FeedSetting;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FeedController extends Controller
{
    private $api;

    public function __construct(Facebook $fb)
    {
        $this->middleware(function ($request, $next) use ($fb) {
            $fb->setDefaultAccessToken(FeedSetting::where('type', 'FB')->first()->token);
            $this->api = $fb;
            return $next($request);
        });
    }

    public function retrievePageFeed()
    {
        $now = Carbon::now();
        $fields = 'comments{created_time,message,from},name,message,created_time,updated_time';
        try {
            $feeds = $this->api->get('/338278063655196/feed?fields=' . $fields)
                //&until='.$now->timestamp.'&since=1542677306')
                ->getGraphEdge()->asArray();

            dump($feeds);

            // get item
            foreach ($feeds as $feed) {
                $fb = FeedFacebook::find($feed['id']);
                if ($fb) {
                    echo 'find feed';

                    $this->savecomment($feed);
                } else {
                    // save feed
                    FeedFacebook::create([
                        'id' => $feed['id'],
//                        'profile_id' => $feed['id'],
                        'message' => $feed['message'],
                        'created_time' => $feed['created_time'],
                        'updated_time' => $feed['updated_time'],
                    ]);

                    $this->savecomment($feed);
                }
            }

        } catch (FacebookSDKException $e) {
            dd($e);
        }

    }

    public function savecomment($feed)
    {
        // save comment
        foreach ($feed['comments'] as $comment) {

            $fb_detail = FeedFacebookDetail::find($comment['id']);

            if ($fb_detail) {
                echo 'find comment';
            } else {

                $fb_profile = null;

                // check profile
                if (isset($comment['from'])) {
                    $fb_profile = FeedFacebookProfile::firstOrCreate(
                        ['id' => $comment['from']['id']],
                        [
                            'id' => $comment['from']['id'],
                            'name' => $comment['from']['name']
                        ]
                    );
                }


                // save comment
                FeedFacebookDetail::create([
                    'id' => $comment['id'],
                    'facebook_id' => $feed['id'],
                    'profile_id' => $fb_profile->id,
                    'message' => $comment['message'],
                    'created_time' => $comment['created_time'],
                ]);
            }
        }

    }
}
