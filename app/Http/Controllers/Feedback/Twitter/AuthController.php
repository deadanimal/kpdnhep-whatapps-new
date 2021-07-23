<?php

namespace App\Http\Controllers\Feedback\Twitter;

use App\Http\Controllers\Controller;
use App\Repositories\Feedback\FeedSettingRepository;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return Socialite::driver('twitter')
            ->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback()
    {
        $auth_user = Socialite::driver('twitter')->user();
        FeedSettingRepository::updateOrCreate($auth_user->name, $auth_user->email, 'TW',$auth_user->token, $auth_user->token_secret);
        return redirect()->to('/'); // Redirect to a secure page
    }
}
