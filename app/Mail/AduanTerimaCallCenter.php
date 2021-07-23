<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Email;
use App\Aduan\CallCenterCase;

class AduanTerimaCallCenter extends Mailable
{
    use Queueable, SerializesModels;
    
    public $mCallCenterCase;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CallCenterCase $callCenterCase)
    {
        $this->mCallCenterCase = $callCenterCase;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $TemplateEmail = Email::where(['email_type'=>'01','email_code'=>'1'])->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;
        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#NAMAPENGADU#";
        $patterns[3] = "#ALAMATPENGADU#";
        $patterns[4] = "#DAERAHPENGADU#";
        $patterns[5] = "#NEGERIPENGADU#";
        $replacements[1] = $this->mCallCenterCase->CA_CASEID;
        $replacements[2] = $this->mCallCenterCase->CA_NAME;
        $replacements[3] = $this->mCallCenterCase->CA_ADDR;
        if(count($this->mCallCenterCase->daerahpengadu) == 1){
            $CA_DISTCD = $this->mCallCenterCase->daerahpengadu->descr;
        } else {
            $CA_DISTCD = '';
        }
//        $replacements[4] = $this->mCallCenterCase->daerahpengadu->descr;
        $replacements[4] = $CA_DISTCD;
        if(count($this->mCallCenterCase->negeripengadu) == 1){
            $CA_STATECD = $this->mCallCenterCase->negeripengadu->descr;
        } else {
            $CA_STATECD = '';
        }
//        $replacements[5] = $this->mCallCenterCase->negeripengadu->descr;
        $replacements[5] = $CA_STATECD;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
