<?php

namespace App\Http\Controllers\Feedback\Facebook;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedSetting;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;

class UserProfileController extends Controller
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

    public function retrieveUserProfile()
    {
        try {
            $params = "first_name,last_name,age_range,gender";

            $user = $this->api->get('/me?fields=' . $params)->getGraphUser();

            dd($user);

        } catch (FacebookSDKException $e) {
            dd($e);
        }
    }

    public function publishToProfile(Request $request)
    {
        try {
            $response = $this->api->post('/me/feed', [
                'message' => $request->message
            ])->getGraphNode()->asArray();
            if ($response['id']) {
                // post created
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }
}
