<?php

namespace App\Mail;

use App\Penugasan;
use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MaklumatAduanTaklengkap extends Mailable
{
    use Queueable, SerializesModels;
    public $mPenugasan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Penugasan $mPenugasan)
    {
        $this->mPenugasan = $mPenugasan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $TemplateEmail = Email::where(['email_type'=>'01','email_code'=>'7'])->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;

        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#NAMAPENGADU#";
        $patterns[3] = "#ALAMATPENGADU#";
        $patterns[4] = "#DAERAHPENGADU#";
        $patterns[5] = "#NEGERIPENGADU#";
        $patterns[6] = "#JAWAPANKEPADAPENGADU#";
        $replacements[1] = $this->mPenugasan->CA_CASEID;
        $replacements[2] = $this->mPenugasan->CA_NAME;
        $replacements[3] = $this->mPenugasan->CA_ADDR;
        if(count($this->mPenugasan->daerahpengadu) == 1){
            $CA_DISTCD = $this->mPenugasan->daerahpengadu->descr;
        } else {
            $CA_DISTCD = '';
        }
//        $replacements[4] = $this->mPenugasan->daerahpengadu->descr;
        $replacements[4] = $CA_DISTCD;
        if(count($this->mPenugasan->negeripengadu) == 1){
            $CA_STATECD = $this->mPenugasan->negeripengadu->descr;
        } else {
            $CA_STATECD = '';
        }
//        $replacements[5] = $this->mPenugasan->negeripengadu->descr;
        $replacements[5] = $CA_STATECD;
        $replacements[6] = $this->mPenugasan->CA_ANSWER;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
