<?php

namespace App\Console\Commands;

use App\Aduan\MaklumatXLengkap;
use App\Attachment;
use App\Letter;
use App\PenugasanCaseDetail;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PDF;

class cronMaklumatTidakLengkap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronMaklumatTidakLengkap:tukarstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tukar status aduan 7 ke 12 untuk maklumat tidak lengkap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 7 - MAKLUMAT TIDAK LENGKAP
        // 12 - SELESAI (MAKLUMAT TIDAK LENGKAP)
        
        $aduans = DB::table('case_info')
            ->leftJoin('case_dtl', 'case_info.CA_CASEID', '=', 'case_dtl.CD_CASEID')
            ->whereIn('CD_INVSTS', ['7']) // ,'8'
            ->whereRaw('CD_INVSTS = CA_INVSTS')
            ->where('CD_CURSTS', 1)
            ->whereYear('CD_CREDT', '>=', '2018')
            ->get();

        foreach ($aduans as $aduan) {
            $durasimaklumatxlengkap = MaklumatXLengkap::getduration($aduan->CD_CREDT, $aduan->CA_CASEID);
            $jumlahhari = DB::table('sys_ref')->where('cat', 1247)->first();
            if ($durasimaklumatxlengkap > $jumlahhari->code) {
                if (!empty($aduan->CA_INVBY)) { // MODUL PENYIASATAN
                    if ($aduan->CA_INVSTS == '7') {
                        $SuratPengaduId = $this->CetakSurat($aduan, 12);
                        $this->Transaksi($aduan, 12, 'Aduan telah menjadi selesai - maklumat tidak lengkap', $SuratPengaduId);
                    } elseif ($aduan->CA_INVSTS == '8') {
                        // $SuratPengaduId = $this->CetakSurat($aduan, 3);
                        // $this->Transaksi($aduan, 3, 'Aduan Dikemaskini oleh sistem', $SuratPengaduId);
                    }
                } else { // MODUL PENUGASAN
                    if ($aduan->CA_INVSTS == '7') {
                        $SuratPengaduId = $this->CetakSurat($aduan, 12);
                        $this->Transaksi($aduan, 12, 'Aduan telah menjadi selesai - maklumat tidak lengkap', $SuratPengaduId);
                    } elseif ($aduan->CA_INVSTS == '8') {
                        // $this->Transaksi($aduan, 9, 'Aduan Dikemaskini oleh sistem', null);
                    }
                }
            }
        }
    }
    
    public function Transaksi($aduan, $status, $desc, $SuratPengaduId)
    {
        $mCaseInfo = MaklumatXLengkap::where('CA_CASEID', $aduan->CA_CASEID)->first();
        $mCaseInfo->CA_INVSTS = $status;
        $mCaseInfo->CA_MODDT = Carbon::now();

        if ($mCaseInfo->save()) {
            DB::table('case_dtl')->where(['CD_CASEID' => $aduan->CA_CASEID, 'CD_CURSTS' => 1])->update(['CD_CURSTS' => 0]);
            $mPenugasanDetail = PenugasanCaseDetail::create([
                'CD_CASEID' => $aduan->CA_CASEID,
                'CD_TYPE' => 'D',
                'CD_DESC' => $desc,
                'CD_INVSTS' => $status,
                'CD_CASESTS' => $aduan->CA_CASESTS,
                'CD_CURSTS' => 1,
                'CD_DOCATTCHID_PUBLIC' => $SuratPengaduId]);
        }
    }

    public function CetakSurat($aduan, $status)
    {
        $SuratPengadu = Letter::where(['letter_code' => $status, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu

        if ($SuratPengadu) {
            $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
        }

        if ($aduan->CA_STATECD != '') {
            $StateNm = DB::table('sys_ref')->select('descr')->where(['cat' => '17', 'code' => $aduan->CA_STATECD])->first();
            if (!$StateNm){
                $CA_STATECD = $aduan->CA_STATECD;
            } else {
                $CA_STATECD = $StateNm->descr;
            }
        } else {
            $CA_STATECD = '';
        }
        if ($aduan->CA_DISTCD != '') {
            $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat' => '18', 'code' => $aduan->CA_DISTCD])->first();
            if (!$DestrictNm){
                $CA_DISTCD = $aduan->CA_DISTCD;
            } else {
                $CA_DISTCD = $DestrictNm->descr;
            }
        } else {
            $CA_DISTCD = '';
        }

        $patternsPengadu[1] = "#NAMAPENGADU#";
        $patternsPengadu[2] = "#ALAMATPENGADU#";
        $patternsPengadu[3] = "#POSKODPENGADU#";
        $patternsPengadu[4] = "#DAERAHPENGADU#";
        $patternsPengadu[5] = "#NEGERIPENGADU#";
        $patternsPengadu[6] = "#NOADUAN#";
        $patternsPengadu[7] = "#TARIKHSELESAIMAKLUMATTIDAKLENGKAP#";
        $replacementsPengadu[1] = $aduan->CA_NAME;
        $replacementsPengadu[2] = $aduan->CA_ADDR != '' ? $aduan->CA_ADDR : '';
        $replacementsPengadu[3] = $aduan->CA_POSCD != '' ? $aduan->CA_POSCD : '';
        $replacementsPengadu[4] = $CA_DISTCD;
        $replacementsPengadu[5] = $CA_STATECD;
        $replacementsPengadu[6] = $aduan->CA_CASEID;
        $replacementsPengadu[7] = Carbon::now()->format('d/m/Y');

        if ($status == '3') {
            $ProfilPegawai = User::find($aduan->CA_INVBY);

            $patternsPengadu[8] = "#JAWAPANKEPADAPENGADU#";
            $patternsPengadu[9] = "#NAMAPEGAWAIPENYIASAT#";
            $patternsPengadu[10] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
            $replacementsPengadu[8] = $aduan->CA_ANSWER != '' ? $aduan->CA_ANSWER : '';
            $replacementsPengadu[9] = $ProfilPegawai->name;
            $replacementsPengadu[10] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />' . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />' . $ProfilPegawai->cawangan->BR_ADDR2;
        }

        $date = date('Ymdhis');
        $userid = $aduan->CA_CREBY;

        // Generate Surat Kepada Pengadu
        if (!empty($SuratPengadu)) {
            $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
            $arr_repPengadu = array("#", "#");
            $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
            $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

            $filename = $userid . '_' . $aduan->CA_CASEID . '_' . $date . '.pdf';
            Storage::disk('letter')->put($filename, $SuratPengaduHtml->output()); // Store PDF to storage

            $AttachSuratPengadu = new Attachment();
            $AttachSuratPengadu->doc_title = $SuratPengadu->title;
            $AttachSuratPengadu->file_name = $SuratPengadu->title;
            $AttachSuratPengadu->file_name_sys = $filename;
            if ($AttachSuratPengadu->save()) {
                $SuratPengaduId = $AttachSuratPengadu->id;
            }
        } else {
            $SuratPengaduId = null;
        }
        return $SuratPengaduId;
    }
}
