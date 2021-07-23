<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Aduan\AdminCase;
use App\Email;

class AduanTerimaAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $mAdminCase;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AdminCase $adminCase)
    {
        $this->mAdminCase = $adminCase;
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
        $replacements[1] = $this->mAdminCase->CA_CASEID;
        $replacements[2] = $this->mAdminCase->CA_NAME;
        $replacements[3] = $this->mAdminCase->CA_ADDR;
        if(count($this->mAdminCase->daerahpengadu) == 1){
            $CA_DISTCD = $this->mAdminCase->daerahpengadu->descr;
        } else {
            $CA_DISTCD = '';
        }
//        $replacements[4] = $this->mAdminCase->daerahpengadu->descr;
        $replacements[4] = $CA_DISTCD;
        if(count($this->mAdminCase->negeripengadu) == 1){
            $CA_STATECD = $this->mAdminCase->negeripengadu->descr;
        } else {
            $CA_STATECD = '';
        }
//        $replacements[5] = $this->mAdminCase->negeripengadu->descr;
        $replacements[5] = $CA_STATECD;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
