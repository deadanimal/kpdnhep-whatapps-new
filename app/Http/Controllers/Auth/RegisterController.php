<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/ref';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest','locale']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    /*protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:sys_users',
            'username' => 'required|string|max:12|unique:sys_users',
            'password' => 'required|string|min:6|confirmed',
            'g-recaptcha-response' => 'required|captcha',
        ]);
    }*/

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:sys_users',
            'tel' => 'required|regex:/(01)[0-9]/|min:10|max:11',
            'username' => 'required|max:12|min:12|unique:sys_users,icnew,1,user_cat',
            'password' => 'required|string|min:6',
        ]);
		
        return User::create([
            'name' => $request['name'],
            'username' => $request['mykad'],
            'name' => $request['name'],
            'icnew' => $request['mykad'],
            'email' => $request['email'],
            'mobile_no' => $request['tel'],
            'email_token' => hash_hmac('sha256', str_random(40), config('app.key')),
            'user_cat' => '2',
            'status' => '0',
            'password' => bcrypt($request['password']),
        ]);
    }
}
