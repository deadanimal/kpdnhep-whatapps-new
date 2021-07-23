<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/ref';
    
    public function username()
    {
        return 'username';
    }
    
    public function logout(Request $request)
    {
        /*if(Auth::user()->user_cat == '1')
        {
            $redirect = 'admin-login';
        }
        else
        {
            $redirect = '';
        }
        
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/'.$redirect);*/
        if ($request->expectsJson()) {
            $user = Auth::guard('api')->user();
            
            if ($user) {
                $user->api_token = null;
                $user->save();
            }
            
            return response()->json(['data' => 'User logged out.'], 200);
        } else {
            if (Auth::check()) {
                if (Auth::user()->user_cat == '1') {
                    $redirect = 'login2';
                } else {
                    $redirect = '';
                }
                
                $this->guard()->logout();
    
                $request->session()->invalidate();
    
                return redirect('/' . $redirect);
            } else {
                return redirect('');
            }
        } 
    }
    
    public function login(Request $request)
    {
        $this->validateLogin($request);
//        if ($this->attemptLogin($request)) {
        if (Auth::attempt(['username' => Request('username'), 'password' => Request('password'), 'user_cat' => '2', 'status' => '1'])) {
            $user = $this->guard()->user();
            $user->generateToken();
            if($request->wantsJson()) {
                return response()->json(['data' => $user->toArray()]);
            }else{
                return $this->sendLoginResponse($request);
            }
        }

        return $this->sendFailedLoginResponse($request);
    }
    
    public function loginAdmin(Request $request)
    {
        $this->validateLogin($request);
//        if ($this->attemptLogin($request)) {
        if (Auth::attempt(['username' => Request('username'), 'password' => Request('password'), 'user_cat' => '1', 'status' => '1'])) {
            $user = $this->guard()->user();
            $user->generateToken();
            if($request->wantsJson()) {
                return response()->json(['data' => $user->toArray()]);
            }else{
                return $this->sendLoginResponse($request);
            }
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function withUserAccess(Request $request) 
    {
        $roles = DB::table('sys_user_access')
                ->join('sys_role_mapping', function($join)
                {
                    $join->on('sys_role_mapping.role_code', '=', 'sys_user_access.role_code')
                         ->where('sys_user_access.user_id', '=', Auth::guard('api')->user()->id);
                })
                ->join('sys_menu', function($join)
                {
                    $join->on('sys_menu.id', '=', 'sys_role_mapping.menu_id')
                         ->whereIn('sys_menu.id', [12, 61, 63, 74, 66]);
                })
                ->get();

        if ($roles->count() != 0)  {
            foreach($roles as $role) {
                if ($role->menu_id == 12 || $role->menu_id == 61 || $role->menu_id == 63 || $role->menu_id == 74) {
                    $menus = DB::table('sys_menu')
                            ->select('id', 'menu_txt', 'menu_parent_id', 'menu_loc')
                            ->where('menu_parent_id', $role->menu_id)
                            ->orderBy('sort')
                            ->get();
                } 
                elseif ($role->menu_id == 66) {
                    $menu = DB::table('sys_menu')
                            ->select('id', 'menu_txt', 'menu_parent_id', 'menu_loc')
                            ->where('id', $role->menu_id)
                            ->orderBy('sort')
                            ->get();
                }  
            }
            return response()->json(['data' => $menus->toArray(), 'pertanyaan' => $menu->toArray()]);
        }
        else {
            // UKK
            $menus = DB::table('sys_menu')
                    ->select('id', 'menu_txt', 'menu_parent_id', 'menu_loc')
                    ->where('id', 66)
                    ->orderBy('sort')
                    ->get();
            return response()->json(['pertanyaan' => $menus->toArray()]);
        }
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
