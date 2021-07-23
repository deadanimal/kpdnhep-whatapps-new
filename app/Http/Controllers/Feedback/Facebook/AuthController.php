<?php

namespace App\Http\Controllers\Feedback\Facebook;

use App\Http\Controllers\Controller;
use App\Repositories\Feedback\FeedSettingRepository;
use Laravel\Socialite\Facades\Socialite;

/**
 * To manage authentication with Facebook for fetch data via API.
 * Class AuthController
 * @package App\Http\Controllers\Feedback\Facebook
 */
class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')
            ->scopes([
                'pages_show_list',
                'public_profile'
            ])
            ->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderFacebookCallback()
    {
        $auth_user = Socialite::driver('facebook')->user();
        FeedSettingRepository::updateOrCreate($auth_user->name, $auth_user->email, 'FB',$auth_user->token);
        return redirect()->to('/');
    }
}
