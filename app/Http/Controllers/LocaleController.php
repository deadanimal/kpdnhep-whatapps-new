<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use URL;

class LocaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $locale
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function SetLocale($locale)
    {
        session()->put('locale', $locale);
        return redirect(url(URL::previous()));
    }
    
    public function FontIncrease()
    {
        $currentsize = session()->get('fontsize');
        if($currentsize) {
            $nextsize = $currentsize + 1;
        }else{
            $nextsize = 1;
        }
        session()->put('fontsize', $nextsize);
        return redirect(url(URL::previous()));
    }
    
    public function FontDecrease()
    {
        $currentsize = session()->get('fontsize');
        if($currentsize) {
            $nextsize = $currentsize - 1;
        }else{
            $nextsize = -1;
        }
        session()->put('fontsize', $nextsize);
        return redirect(url(URL::previous()));
    }
    
    public function FontReset()
    {
        session()->put('fontsize', 0);
        return redirect(url(URL::previous()));
    }
    
    public function FontColor($id = 0)
    {
        session()->put('fontcolor', $id);
        return redirect(url(URL::previous()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
