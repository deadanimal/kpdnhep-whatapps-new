<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Email;
use App\SasCase;

class AduanTutupSas extends Mailable
{
    use Queueable, SerializesModels;
    
    public $model;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SasCase $SasCase)
    {
        $this->model = $SasCase;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $TemplateEmail = Email::where(['email_type'=>'01','email_code'=>'9'])->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;
        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#JAWAPANKEPADAPENGADU#";
        $patterns[3] = "#NAMAPEGAWAIPENYIASAT#";
        $patterns[4] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
        $replacements[1] = $this->model->CA_CASEID;
        $replacements[2] = $this->model->CA_ANSWER;
        $replacements[3] = $this->model->invby->name;
        $replacements[4] = $this->model->invby->Cawangan->BR_BRNNM.'<br>'
            .$this->model->invby->Cawangan->BR_ADDR1.'<br>'
            .$this->model->invby->Cawangan->BR_ADDR2.'<br>'
            .$this->model->invby->Cawangan->BR_POSCD.'<br>'
            .$this->model->invby->Cawangan->DaerahPegawai->descr.'<br>'
            .$this->model->invby->Cawangan->NegeriPegawai->descr.'<br>'
        ;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
//        return $this->view('view.name');
    }
}
