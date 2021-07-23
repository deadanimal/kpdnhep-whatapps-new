<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Exception;

class TestAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('test.index');
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

    public function __construct()
    {
        $this->middleware(['locale','auth']);
    }
    
    public function sendemail(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content');
        $emails = explode(";", $request->email);
        foreach ($emails as $email) {
            try {
                Mail::send('test.emailcontent', [
                    'title' => $title, 'content' => $content
                ], 
                function ($message) use ($request, $title, $content, $email)
                {
                    // $message->to($request->email);
                    $message->to($email);
                    // $message->cc($request->cc);
                    if ($request->cc != '') {
                        $ccs = explode(";", $request->cc);
                        foreach($ccs as $cc) {
                            $message->cc($cc);
                        }
                    }
                    // $message->bcc($request->bcc);
                    if ($request->bcc != '') {
                        $bccs = explode(";", $request->bcc);
                        foreach($bccs as $bcc) {
                            $message->bcc($bcc);
                        }
                    }
                    $message->subject($request->title);
                });
                return redirect()
                    ->back()
                    ->with('success', 'Emel telah berjaya dihantar');
            } catch (Exception $ex) {
                return redirect()
                    ->back()
                    ->with(
                        'alert', 
                        'Emel gagal dihantar.<br />'
                        .$ex->getMessage()
                    );
            }
        }
    }
}
