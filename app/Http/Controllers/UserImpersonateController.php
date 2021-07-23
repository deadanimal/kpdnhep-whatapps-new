<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Flash;
use Illuminate\Support\Facades\Auth;
use Response;

/**
 * Class UserImpersonateController
 * @package App\Http\Controllers
 */
class UserImpersonateController extends Controller
{
    /**
     * UserImpersonateController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function impersonate($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            session()->flash('success', 'Pengguna tidak ditemui');

            return redirect(route('adminuser'));
        }

        Auth::user()->impersonate($user);
        return redirect(route('welcome'));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function leave()
    {
        Auth::user()->leaveImpersonation();
        return redirect(route('welcome'));
    }
}
