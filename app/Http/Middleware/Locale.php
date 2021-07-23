<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Config;
use App;
use Illuminate\Support\Facades\Auth;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $languages = ['ms','en'];
    
//    public function handle($request, Closure $next)
//    {
//        $locale = session()->get('locale');
//        app()->setLocale('en');
//        
//        return $next($request);
//    }
    
    public function handle($request, Closure $next)
    {
        if(!Session::has('locale'))
        {
//            Session::put('locale', $request->getPreferredLanguage($this->languages));
            Session::put('locale', config('app.locale'));
        }
        
        if(Auth::guest()){
            app()->setLocale(Session::get('locale'));
        }else{
            if(Auth::user()->user_cat == '2'){
                if(Auth::user()->lang != null){
                    app()->setLocale(Auth::user()->lang);
                } else {
                    app()->setLocale(config('app.locale'));
//                    app()->setLocale($request->getPreferredLanguage($this->languages));
                }
            }
        }
//        if(Auth::user()->user_cat == '2'){
//            if(Auth::user()->lang != null){
//                app()->setLocale(Auth::user()->lang);
//            } else {
//                app()->setLocale(Session::get('locale'));
//            }
//        } else {
//            app()->setLocale(Session::get('locale'));
//        }
//        if(!Session::has('statut')) 
//        {
//            Session::put('statut', Auth::check() ?  Auth::user()->role->slug : 'visitor');
//        }

        return $next($request);
    }
}
