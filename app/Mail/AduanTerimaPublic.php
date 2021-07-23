<?php

namespace App\Mail;

use App\Aduan\PublicCase;
use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AduanTerimaPublic extends Mailable
{
    use Queueable, SerializesModels;
    public $mPubCase;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PublicCase $mPubCase)
    {
        $this->mPubCase = $mPubCase;
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
        $replacements[1] = $this->mPubCase->CA_CASEID;
        $replacements[2] = $this->mPubCase->CA_NAME;
        $replacements[3] = $this->mPubCase->CA_ADDR;
        if(count($this->mPubCase->daerahpengadu) == 1){
            $CA_DISTCD = $this->mPubCase->daerahpengadu->descr;
        } else {
            $CA_DISTCD = '';
        }
//        $replacements[4] = $this->mPubCase->daerahpengadu->descr;
        $replacements[4] = $CA_DISTCD;
        if(count($this->mPubCase->negeripengadu) == 1){
            $CA_STATECD = $this->mPubCase->negeripengadu->descr;
        } else {
            $CA_STATECD = '';
        }
//        $replacements[5] = $this->mPubCase->negeripengadu->descr;
        $replacements[5] = $CA_STATECD;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
