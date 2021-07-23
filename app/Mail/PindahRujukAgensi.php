<?php

namespace App\Mail;
use App\Aduan\PindahAduan;
use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PindahRujukAgensi extends Mailable
{
    use Queueable, SerializesModels;
    public $mPindah;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PindahAduan $mPindah)
    {
        $this->mPindah = $mPindah;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $TemplateEmail = Email::where(['email_type'=>'02','email_code'=>'4'])->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;
        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#JAWAPANKEPADAPENGADU#";
        $patterns[3] = "#NAMAAGENSI#";
        $patterns[4] = "#ALAMATAGENSI#";
        $patterns[5] = "#KETERANGANADUAN#";
        $patterns[6] = "#NAMAPEGAWAIPENYIASAT#";
        $patterns[7] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
        $patterns[8] = "#NOKADPENGENALANPENGADU#";
        $patterns[9] = "#NAMAPENGADU#";
        $patterns[10] = "#NOTELEFONBIMBITPENGADU#";
        $patterns[11] = "#EMELPENGADU#";
        $replacements[1] = $this->mPindah->CA_CASEID;
        $replacements[2] = $this->mPindah->CA_ANSWER;
        if(count($this->mPindah->agensi) == 1){
            $MI_DESC = $this->mPindah->agensi->MI_DESC;
            $MI_ADDR = $this->mPindah->agensi->MI_ADDR;
            $MI_POSCD = $this->mPindah->agensi->MI_POSCD;
            if($this->mPindah->agensi->daerahagensi){
                $MI_DISTCD = $this->mPindah->agensi->daerahagensi->descr;
            } else {
                $MI_DISTCD = '';
            }
            if($this->mPindah->agensi->Negeriagensi){
                $MI_STATECD = $this->mPindah->agensi->Negeriagensi->descr;
            } else {
                $MI_STATECD = '';
            }
        } else {
            $MI_DESC = '';
            $MI_ADDR = '';
            $MI_DISTCD = '';
            $MI_STATECD = '';
        }
        $replacements[3] = $MI_DESC;
        $replacements[4] = $MI_ADDR . '<br />' 
            . $MI_POSCD . ' ' . $MI_DISTCD . '<br />' 
            . $MI_STATECD;
//        $replacements[3] = $this->mPindah->agensi->MI_DESC;
//        $replacements[4] = $this->mPindah->agensi->MI_ADDR;
        $replacements[5] = $this->mPindah->CA_SUMMARY;
        if($this->mPindah->penugasanpindaholeh){
            $CA_ASGBY = $this->mPindah->penugasanpindaholeh->name;
        } else {
            $CA_ASGBY = '';
        }
        $replacements[6] = $CA_ASGBY;
        if($this->mPindah->penugasanpindaholeh->Cawangan){
            $BR_BRNNM = $this->mPindah->penugasanpindaholeh->Cawangan->BR_BRNNM;
            $BR_ADDR1 = $this->mPindah->penugasanpindaholeh->Cawangan->BR_ADDR1;
            $BR_ADDR2 = $this->mPindah->penugasanpindaholeh->Cawangan->BR_ADDR2;
            $BR_POSCD = $this->mPindah->penugasanpindaholeh->Cawangan->BR_POSCD;
            if($this->mPindah->penugasanpindaholeh->Cawangan->DaerahPegawai){
                $BR_DISTCD = $this->mPindah->penugasanpindaholeh->Cawangan->DaerahPegawai->descr;
            } else {
                $BR_DISTCD = '';
            }
            if($this->mPindah->penugasanpindaholeh->Cawangan->NegeriPegawai){
                $BR_STATECD = $this->mPindah->penugasanpindaholeh->Cawangan->NegeriPegawai->descr;
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
        $replacements[7] = $BR_BRNNM.'<br />'
            .$BR_ADDR1.'<br />'
            .$BR_ADDR2.'<br />'
            .$BR_POSCD.' '.$BR_DISTCD.'<br />'
            .$BR_STATECD.'<br />';
        $replacements[8] = $this->mPindah->CA_DOCNO;
        $replacements[9] = $this->mPindah->CA_NAME;
        $replacements[10] = $this->mPindah->CA_MOBILENO;
        $replacements[11] = $this->mPindah->CA_EMAIL;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
