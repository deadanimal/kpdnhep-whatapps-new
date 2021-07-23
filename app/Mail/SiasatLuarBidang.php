<?php

namespace App\Mail;

use App\Aduan\Penyiasatan;
use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SiasatLuarBidang extends Mailable
{
    use Queueable, SerializesModels;
    public $mSiasat;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Penyiasatan $mSiasat)
    {
        $this->mSiasat = $mSiasat;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $TemplateEmail = Email::where(['email_type'=>'01','email_code'=>'8'])->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;
//        
        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#NAMAPENGADU#";
        $patterns[3] = "#ALAMATPENGADU#";
        $patterns[4] = "#DAERAHPENGADU#";
        $patterns[5] = "#NEGERIPENGADU#";
        $patterns[6] = "#JAWAPANKEPADAPENGADU#";
        $replacements[1] = $this->mSiasat->CA_CASEID;
        $replacements[2] = $this->mSiasat->CA_NAME;
        $replacements[3] = $this->mSiasat->CA_ADDR;
        if(count($this->mSiasat->daerahpengadu) == 1){
            $CA_DISTCD = $this->mSiasat->daerahpengadu->descr;
        } else {
            $CA_DISTCD = '';
        }
//        $replacements[4] = $this->mSiasat->daerahpengadu->descr;
        $replacements[4] = $CA_DISTCD;
        if(count($this->mSiasat->negeripengadu) == 1){
            $CA_STATECD = $this->mSiasat->negeripengadu->descr;
        } else {
            $CA_STATECD = '';
        }
//        $replacements[5] = $this->mSiasat->negeripengadu->descr;
        $replacements[5] = $CA_STATECD;
        $replacements[6] = $this->mSiasat->CA_ANSWER;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
