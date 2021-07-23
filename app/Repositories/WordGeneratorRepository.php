<?php

namespace App\Repositories;

use App\Letter;
use App\PenugasanCaseDetail;
use App\User;
use Carbon\Carbon;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class WordGeneratorRepository
{
    public static function generateForm1()
    {
        // test phpword generating
        $filename = 'helloWorld' . date('ymd') . '.docx';

        $phpWord = new PhpWord;

        $section = $phpWord->addSection();
        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert)'
        );

        try {
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        } catch (Exception $e) {

        }

        $objWriter->save($filename);

        return $filename;
    }
    
    public static function generateFormDownload($CA_CASEID, $CD_ACTTO){
        $mPenugasanCaseDetail = PenugasanCaseDetail::where(['CD_CASEID' => $CA_CASEID, 'CD_INVSTS' => '2', 'CD_ACTTO' => $CD_ACTTO])->first();
        $date = Carbon::parse($mPenugasanCaseDetail->CD_CREDT)->format('YmdHis');
        $SuratPegawai = Letter::where(['letter_code' => '2', 'letter_type' => '02', 'status' => '1'])->first(); // Templat Surat Kepada Pegawai
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->createSection();
        if($SuratPegawai){
            $ContentSuratPegawai = $SuratPegawai->header . $SuratPegawai->body . $SuratPegawai->footer;
        } else {
            $ContentSuratPegawai = NULL;
        }
        $ProfilPegawai = User::find($mPenugasanCaseDetail->CD_ACTTO);
        $patternsPegawai[1] = "#NEGERIPEGAWAI#";
        $patternsPegawai[2] = "#CAWANGANPEGAWAI#";
        $patternsPegawai[3] = "#TARIKHPENUGASAN#";
        $patternsPegawai[4] = "#MASAPENUGASAN#";
        $patternsPegawai[5] = "#NAMAPEGAWAIPENUGASAN#";
        $patternsPegawai[6] = "#NOADUAN#";
        $replacementsPegawai[1] = $ProfilPegawai->Negeri ? $ProfilPegawai->Negeri->descr : $ProfilPegawai->state_cd;
        $replacementsPegawai[2] = $ProfilPegawai->cawangan ? $ProfilPegawai->cawangan->BR_BRNNM : $ProfilPegawai->brn_cd;
        $replacementsPegawai[3] = '';
        $replacementsPegawai[4] = '';
        $replacementsPegawai[5] = $ProfilPegawai->name;
        $replacementsPegawai[6] = $CA_CASEID;
        $SuratPegawaiReplace = preg_replace($patternsPegawai, $replacementsPegawai, urldecode($ContentSuratPegawai));
        $arr_repPegawai = array("#", "#");
        $SuratPegawaiFinal = str_replace($arr_repPegawai, "", $SuratPegawaiReplace); // SuratPegawai in HTML
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $SuratPegawaiFinal, false, false);
        $file = $mPenugasanCaseDetail->CD_CREBY . '_' . $CA_CASEID . '_' . $date . '_2.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
    }
}