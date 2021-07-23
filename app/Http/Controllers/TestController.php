<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AduanTerimaPublic;
use Illuminate\Support\Facades\Mail;
use App\Aduan\PublicCase;
use App\Email;
use Collective\Html;
use DB;
use Exception;

class TestController extends Controller
{
    public function SendEmail(Request $Request)
    {
        $mPubCase = PublicCase::where(['CA_CASEID' => '01710826'])->first();
        Mail::to($Request->user())
                ->queue(new AduanTerimaPublic($mPubCase));
//        $TemplateEmail = Email::findOrFail(1);
//        $HTMLEmail = $TemplateEmail->header;
//        
//        Mail::raw($HTMLEmail, function ($message){
//            $message->to('epilim88@gmail.com');
//        });
        echo 'berjaya';exit;
    }
    
    public function UpdateSubAkta()
    {
        $mTableAkta = DB::table('case_act')->select('id','CT_SUBAKTA')->get();
        foreach($mTableAkta as $TableAkta) {
            $mRef = DB::table('sys_ref')->where('code', 'LIKE', "%{$TableAkta->CT_SUBAKTA}")->where('cat','714')->first();
            $akta = explode(' ', $mRef->code)[0];
            DB::table('case_act')->where('id', $TableAkta->id)->update(['CT_AKTA' => $akta, 'CT_SUBAKTA' => $mRef->code]);
        }
        dd('Berjaya');
    }
    
    public function UpdateIpNo()
    {
        $mTableAkta = DB::table('case_act')->select('id','CT_CASEID')->get();
        foreach($mTableAkta as $TableAkta) {
            $mCaseInfo = DB::table('case_info')->where('CA_CASEID', $TableAkta->CT_CASEID)->first();
            if($mCaseInfo)
            DB::table('case_act')->where('id', $TableAkta->id)->update(['CT_IPNO' => $mCaseInfo->CA_IPNO]);
        }
        dd('Berjaya');
    }
    
    public function UpdatePassword()
    {
        $mUser = DB::table('sys_users')->select('id','text_password')->where('user_cat',2)->get();
        $i = 1;
        foreach($mUser as $User) {
            DB::table('sys_users')->where('id', $User->id)->update(['password' => bcrypt($User->text_password)]);
            $i++;
        }
        echo "Done Update ".$i." Rekod";exit;
    }
    
    public function index()
    {
        
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

    public function createemailtest()
    {
        return view('emails.createemailtest');
    }
    
    public function sendemailtest(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content');
        $emails = explode(";", $request->email);
        foreach ($emails as $email) {
            try {
                Mail::send('emails.contentmailtest', [
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
