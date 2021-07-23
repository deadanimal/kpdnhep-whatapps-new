<?php

namespace App\Mail;

use App\Penugasan;
use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmelNotifikasiAduanDalamSiasatan extends Mailable
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
        $TemplateEmail = 
            Email::where(['email_type'=>'02','email_code'=>'2','status'=>'1'])
            ->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;

        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#NAMAPENGADU#";
        $patterns[3] = "#NAMAPEGAWAIPENYIASAT#";
        $patterns[4] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
        $replacements[1] = $this->mPenugasan->CA_CASEID;
        $replacements[2] = $this->mPenugasan->CA_NAME;
        $replacements[3] = $this->mPenugasan->namapenyiasat 
            ? $this->mPenugasan->namapenyiasat->name 
            : '';
        if($this->mPenugasan->namapenyiasat->Cawangan){
            $BR_BRNNM = $this->mPenugasan->namapenyiasat->Cawangan->BR_BRNNM;
            $BR_ADDR1 = $this->mPenugasan->namapenyiasat->Cawangan->BR_ADDR1;
            $BR_ADDR2 = $this->mPenugasan->namapenyiasat->Cawangan->BR_ADDR2;
            $BR_POSCD = $this->mPenugasan->namapenyiasat->Cawangan->BR_POSCD;
            if($this->mPenugasan->namapenyiasat->Cawangan->DaerahPegawai){
                $BR_DISTCD = $this->mPenugasan->namapenyiasat->Cawangan->DaerahPegawai->descr;
            } else {
                $BR_DISTCD = '';
            }
            if($this->mPenugasan->namapenyiasat->Cawangan->NegeriPegawai){
                $BR_STATECD = $this->mPenugasan->namapenyiasat->Cawangan->NegeriPegawai->descr;
            } else {
                $BR_STATECD = '';
            }
        } else {
            $BR_BRNNM = '';
            $BR_ADDR1 = '';
            $BR_ADDR2 = '';
            $BR_POSCD = '';
            $BR_DISTCD = '';
            $BR_STATECD = '';
        }
        $replacements[4] = $BR_BRNNM.'<br />'
            .$BR_ADDR1.'<br />'
            .$BR_ADDR2.'<br />'
            .$BR_POSCD.' '.$BR_DISTCD.'<br />'
            .$BR_STATECD.'<br />';
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
