<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Email;
use App\Pertanyaan\PertanyaanAdmin;

class PertanyaanTerimaAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $model;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PertanyaanAdmin $pertanyaanAdmin)
    {
        $this->model = $pertanyaanAdmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->view('view.name');
        $TemplateEmail = Email::where(['email_type'=>'01','email_code'=>'6'])->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;
        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#NAMAPENGADU#";
        $patterns[3] = "#ALAMATPENGADU#";
        $patterns[4] = "#DAERAHPENGADU#";
        $patterns[5] = "#NEGERIPENGADU#";
        $replacements[1] = $this->model->AS_ASKID;
        $replacements[2] = $this->model->AS_NAME;
        $replacements[3] = $this->model->AS_ADDR;
        if(count($this->model->daerahpengadu) == 1){
            $AS_DISTCD = $this->model->daerahpengadu->descr;
        } else {
            $AS_DISTCD = '';
        }
//        $replacements[4] = $this->model->daerahpengadu->descr;
        $replacements[4] = $AS_DISTCD;
        if(count($this->model->negeripengadu) == 1){
            $AS_STATECD = $this->model->negeripengadu->descr;
        } else {
            $AS_STATECD = '';
        }
//        $replacements[5] = $this->model->negeripengadu->descr;
        $replacements[5] = $AS_STATECD;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
