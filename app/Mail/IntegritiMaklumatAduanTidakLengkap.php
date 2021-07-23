<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Email;
use App\Integriti\IntegritiAdmin;

class IntegritiMaklumatAduanTidakLengkap extends Mailable
{
    use Queueable, SerializesModels;
    public $mPenugasan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(IntegritiAdmin $mPenugasan)
    {
        //
        $this->mPenugasan = $mPenugasan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        $TemplateEmail = Email::where(['email_type'=>'01','email_code'=>'07'])->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;

        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#NAMAPENGADU#";
        $patterns[3] = "#ALAMATPENGADU#";
        $patterns[4] = "#DAERAHPENGADU#";
        $patterns[5] = "#NEGERIPENGADU#";
        $replacements[1] = $this->mPenugasan->IN_CASEID;
        $replacements[2] = $this->mPenugasan->IN_NAME;
        $replacements[3] = $this->mPenugasan->IN_ADDR;
        // if(count($this->mPenugasan->daerahpengadu) == 1){
        if($this->mPenugasan->indistcd){
            $IN_DISTCD = $this->mPenugasan->indistcd->descr;
        } else {
            $IN_DISTCD = '';
        }
//        $replacements[4] = $this->mPenugasan->daerahpengadu->descr;
        $replacements[4] = $IN_DISTCD;
        // if(count($this->mPenugasan->negeripengadu) == 1){
        if($this->mPenugasan->instatecd){
            $IN_STATECD = $this->mPenugasan->instatecd->descr;
        } else {
            $IN_STATECD = '';
        }
//        $replacements[5] = $this->mPenugasan->negeripengadu->descr;
        $replacements[5] = $IN_STATECD;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
