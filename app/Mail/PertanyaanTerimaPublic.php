<?php

namespace App\Mail;
use App\Pertanyaan\PertanyaanPublic;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Email;

class PertanyaanTerimaPublic extends Mailable
{
    use Queueable, SerializesModels;
    public $mPertanyaanPublic;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PertanyaanPublic $mPertanyaanPublic)
    {
        $this->mPertanyaanPublic = $mPertanyaanPublic;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $TemplateEmail = Email::where(['email_type'=>'01','email_code'=>'6'])->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;
        
        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#NAMAPENGADU#";
        $patterns[3] = "#ALAMATPENGADU#";
        $patterns[4] = "#DAERAHPENGADU#";
        $patterns[5] = "#NEGERIPENGADU#";
        $replacements[1] = $this->mPertanyaanPublic->AS_ASKID;
        $replacements[2] = $this->mPertanyaanPublic->AS_NAME;
        $replacements[3] = $this->mPertanyaanPublic->AS_ADDR;
        if(count($this->mPertanyaanPublic->daerahpengadu) == 1){
            $AS_DISTCD = $this->mPertanyaanPublic->daerahpengadu->descr;
        } else {
            $AS_DISTCD = '';
        }
//        $replacements[4] = $this->mPertanyaanPublic->daerahpengadu->descr;
        $replacements[4] = $AS_DISTCD;
        if(count($this->mPertanyaanPublic->negeripengadu) == 1){
            $AS_STATECD = $this->mPertanyaanPublic->negeripengadu->descr;
        } else {
            $AS_STATECD = '';
        }
//        $replacements[5] = $this->mPertanyaanPublic->negeripengadu->descr;
        $replacements[5] = $AS_STATECD;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
