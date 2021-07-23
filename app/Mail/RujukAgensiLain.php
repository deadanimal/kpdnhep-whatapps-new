<?php

namespace App\Mail;

use App\Aduan\Penyiasatan;
use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RujukAgensiLain extends Mailable
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
        $TemplateEmail = Email::where(['email_type'=>'02','email_code'=>'4'])->first();
        $HTMLEmail = $TemplateEmail->header.$TemplateEmail->body.$TemplateEmail->footer;
        $patterns[1] = "#NOADUAN#";
        $patterns[2] = "#JAWAPANKEPADAPENGADU#";
        $patterns[3] = "#NAMAPEGAWAIPENYIASAT#";
        $patterns[4] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
        $patterns[5] = "#NAMAAGENSI#";
        $patterns[6] = "#ALAMATAGENSI#";
        $patterns[7] = "#KETERANGANADUAN#";
        $patterns[8] = "#NOKADPENGENALANPENGADU#";
        $patterns[9] = "#NAMAPENGADU#";
        $patterns[10] = "#NOTELEFONBIMBITPENGADU#";
        $patterns[11] = "#EMELPENGADU#";
        $replacements[1] = $this->mSiasat->CA_CASEID;
        $replacements[2] = $this->mSiasat->CA_ANSWER;
        if ($this->mSiasat->invby != null) {
            if(count($this->mSiasat->invby) == 1){
                $CA_INVBY = $this->mSiasat->invby->name;
            } else {
                $CA_INVBY = '';
            }
//            $replacements[3] = $this->mSiasat->invby->name;
            $replacements[3] = $CA_INVBY;
            if(count($this->mSiasat->invby->Cawangan) == 1){
                $BR_BRNNM = $this->mSiasat->invby->Cawangan->BR_BRNNM;
                $BR_ADDR1 = $this->mSiasat->invby->Cawangan->BR_ADDR1;
                $BR_ADDR2 = $this->mSiasat->invby->Cawangan->BR_ADDR2;
                $BR_POSCD = $this->mSiasat->invby->Cawangan->BR_POSCD;
                if(count($this->mSiasat->invby->Cawangan->DaerahPegawai) == 1){
                    $BR_DISTCD = $this->mSiasat->invby->Cawangan->DaerahPegawai->descr;
                } else {
                    $BR_DISTCD = '';
                }
                if(count($this->mSiasat->invby->Cawangan->NegeriPegawai) == 1){
                    $BR_STATECD = $this->mSiasat->invby->Cawangan->NegeriPegawai->descr;
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
//            $replacements[4] = $this->mSiasat->invby->Cawangan->BR_BRNNM.'<br>'
//            .$this->mSiasat->invby->Cawangan->BR_ADDR1.'<br>'
//            .$this->mSiasat->invby->Cawangan->BR_ADDR2.'<br>'
//            .$this->mSiasat->invby->Cawangan->BR_POSCD.'<br>'
//            .$this->mSiasat->invby->Cawangan->DaerahPegawai->descr.'<br>'
//            .$this->mSiasat->invby->Cawangan->NegeriPegawai->descr.'<br>';
            $replacements[4] = $BR_BRNNM.'<br>'
                .$BR_ADDR1.'<br>'
                .$BR_ADDR2.'<br>'
                .$BR_POSCD.' '.$BR_DISTCD.'<br>'
                .$BR_STATECD.'<br>';
        } else {
            if(count($this->mSiasat->GetAsgByName) == 1){
                $CA_ASGBY = $this->mSiasat->GetAsgByName->name;
            } else {
                $CA_ASGBY = '';
            }
//            $replacements[3] = $this->mSiasat->GetAsgByName->name;
            $replacements[3] = $CA_ASGBY;
            if(count($this->mSiasat->GetAsgByName->Cawangan) == 1){
                $BR_BRNNM = $this->mSiasat->GetAsgByName->Cawangan->BR_BRNNM;
                $BR_ADDR1 = $this->mSiasat->GetAsgByName->Cawangan->BR_ADDR1;
                $BR_ADDR2 = $this->mSiasat->GetAsgByName->Cawangan->BR_ADDR2;
                $BR_POSCD = $this->mSiasat->GetAsgByName->Cawangan->BR_POSCD;
                if(count($this->mSiasat->GetAsgByName->Cawangan->DaerahPegawai) == 1){
                    $BR_DISTCD = $this->mSiasat->GetAsgByName->Cawangan->DaerahPegawai->descr;
                } else {
                    $BR_DISTCD = '';
                }
                if(count($this->mSiasat->GetAsgByName->Cawangan->NegeriPegawai) == 1){
                    $BR_STATECD = $this->mSiasat->GetAsgByName->Cawangan->NegeriPegawai->descr;
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
//            $replacements[4] = $this->mSiasat->GetAsgByName->Cawangan->BR_BRNNM.'<br>'
//            .$this->mSiasat->GetAsgByName->Cawangan->BR_ADDR1.'<br>'
//            .$this->mSiasat->GetAsgByName->Cawangan->BR_ADDR2.'<br>'
//            .$this->mSiasat->GetAsgByName->Cawangan->BR_POSCD.'<br>'
//            .$this->mSiasat->GetAsgByName->Cawangan->DaerahPegawai->descr.'<br>'
//            .$this->mSiasat->GetAsgByName->Cawangan->NegeriPegawai->descr.'<br>';
            $replacements[4] = $BR_BRNNM.'<br>'
                .$BR_ADDR1.'<br>'
                .$BR_ADDR2.'<br>'
                .$BR_POSCD.' '.$BR_DISTCD.'<br>'
                .$BR_STATECD.'<br>';
        }
        if(count($this->mSiasat->agency) == 1){
            $MI_DESC = $this->mSiasat->agency->MI_DESC;
            $MI_ADDR = $this->mSiasat->agency->MI_ADDR;
            $MI_POSCD = $this->mSiasat->agency->MI_POSCD;
            if(count($this->mSiasat->agency->daerahagensi) == 1){
                $MI_DISTCD = $this->mSiasat->agency->daerahagensi->descr;
            } else {
                $MI_DISTCD = '';
            }
            if(count($this->mSiasat->agency->Negeriagensi) == 1){
                $MI_STATECD = $this->mSiasat->agency->Negeriagensi->descr;
            } else {
                $MI_STATECD = '';
            }
        } else {
            $MI_DESC = '';
            $MI_ADDR = '';
        }
//        $replacements[5] = $this->mSiasat->agency->MI_DESC;
//        $replacements[6] = $this->mSiasat->agency->MI_ADDR;
        $replacements[5] = $MI_DESC;
        $replacements[6] = $MI_ADDR .  '<br />' 
            . $MI_POSCD . ' ' . $MI_DISTCD .  '<br />' 
            . $MI_STATECD;
        $replacements[7] = $this->mSiasat->CA_SUMMARY;
        $replacements[8] = $this->mSiasat->CA_DOCNO;
        $replacements[9] = $this->mSiasat->CA_NAME;
        $replacements[10] = $this->mSiasat->CA_MOBILENO;
        $replacements[11] = $this->mSiasat->CA_EMAIL;
        $ContentReplace = preg_replace($patterns, $replacements, urldecode($HTMLEmail));
        $arr_rep = array("#", "#");
        $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
        return $this->subject($TemplateEmail->title)->view('emails.aduan_admin', compact('ContentFinal'));
    }
}
