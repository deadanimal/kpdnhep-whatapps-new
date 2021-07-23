<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\GabungRelation;
use App\Agensi;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\Letter;
use App\Mail\AduanLuarBidang;
use App\Mail\MaklumatAduanTaklengkap;
use App\Mail\RujukAgensiLain;
use App\Models\Cases\CaseReasonTemplate;
use App\Penugasan;
use App\PenugasanCaseDetail;
use App\Ref;
use App\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;
use Validator;

class GabungController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(request $Request)
    {
//        dd($Request);
        $caseReasonTemplates = CaseReasonTemplate::where(['category' => 'AD51', 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code');

        $arrCASEID = $Request->CASEID;
        $countDurationIndicator = false;
        foreach($arrCASEID as $caseid) {
            $caseDetail[$caseid] = PenugasanCaseDetail::
                where('CD_CASEID', $caseid)
                ->where('CD_INVSTS', '0')
                ->orderBy('CD_CREDT', 'desc')
                ->first();
            $caseInfo[$caseid] = Penugasan::where(['CA_CASEID' => $caseid])->first();
            if($caseDetail[$caseid]) {
                $countDuration[$caseid] = Penugasan::GetDuration($caseDetail[$caseid]->CD_CREDT, $caseid);
            } else {
                $countDuration[$caseid] = Penugasan::GetDuration($caseInfo[$caseid]->CA_RCVDT, $caseid);
            }
            if($countDuration[$caseid] >= 4){
                $countDurationIndicator = true;
            }
        }

        if($Request->submit == 1) {
            $arrCASEID = $Request->CASEID;
            $mUser = User::find(Auth::User()->id);
            return view('aduan.gabung.edit', compact('arrCASEID','mUser','caseReasonTemplates','countDuration','countDurationIndicator'));
        }
        
        if($Request->submit == 2) {
            $arrCASEID = $Request->CASEID;
            $mUser = User::find(Auth::User()->id);
            return view('aduan.gabung.edit_kelompok', compact('arrCASEID','mUser','caseReasonTemplates','countDuration','countDurationIndicator'));
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // $this->validate($request, [
        $v = Validator::make($request->all(), [
//            'CA_INVBY' => 'required',
//            'CD_DESC' => 'required',
            'CD_DESC' => 'required_if:CA_INVSTS,2',
            'CA_INVBY' => 'required_if:CA_INVSTS,2',
            'CA_ANSWER' => 'required_unless:CA_INVSTS,2',
            'CA_MAGNCD' => 'required_if:CA_INVSTS,4',
            'CA_INVSTS' => 'required',
        ],
        [
//            'CA_INVBY.required' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan.',
//            'CD_DESC.required' => 'Ruangan Saranan diperlukan.',
            'CD_DESC.required_if' => 'Ruangan Saranan diperlukan.',
            'CA_INVBY.required_if' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan.',
            'CA_ANSWER.required_unless' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
            'CA_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
            'CA_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
            'CD_REASON.required' => 'Ruangan Alasan diperlukan.',
            'CD_REASON_DATE_FROM.required' => 'Ruangan Tarikh Dari diperlukan.',
            'CD_REASON_DATE_TO.required' => 'Ruangan Tarikh Hingga diperlukan.',
            'CD_REASON.*.required' => 'Ruangan Alasan diperlukan.',
            'CD_REASON_DATE_FROM.*.required' => 'Ruangan Tarikh Dari diperlukan.',
            'CD_REASON_DATE_TO.*.required' => 'Ruangan Tarikh Hingga diperlukan.',
        ]);
        if($request->submit == 1) {
            $countDurationIndicator = false;
            $arrayReasonDuration = $request->CD_REASON_DURATION;
            foreach($arrayReasonDuration as $key => $value) {
                if($request->CD_REASON_DURATION[$key] >= 4) {
                    $countDurationIndicator = true;
                }
                if($countDurationIndicator) {
                    $v->sometimes('CD_REASON', 'required', function ($input) {
                        return !empty($input->CA_INVSTS);
                    });
                    $v->sometimes(['CD_REASON_DATE_FROM', 'CD_REASON_DATE_TO'], 'required', function ($input) {
                        return $input->CD_REASON == 'P2' && !empty($input->CA_INVSTS);
                    });
                }
            }
        } else if($request->submit == 2) {
            $arrayReasonDuration = $request->CD_REASON_DURATION;
            foreach($arrayReasonDuration as $key => $value) {
                $v->sometimes("CD_REASON.$key", 'required', function ($input) use ($key) {
                    return $input->CD_REASON_DURATION[$key] >= 4 && !empty($input->CA_INVSTS);
                });
                $v->sometimes(["CD_REASON_DATE_FROM.$key", "CD_REASON_DATE_TO.$key"], 'required', function ($input) use ($key) {
                    return $input->CD_REASON_DURATION[$key] >= 4 && $input->CD_REASON[$key] == 'P2' && !empty($input->CA_INVSTS);
                });
            }
        }
        $v->validate();
        
//        dd($request);
        
        $arrCASEID = $request->CA_FILEREF;
        
        $MaxCaseRelId = DB::table('case_rel')->max('CR_RELID');
        if($MaxCaseRelId)
            $NextRelId = $MaxCaseRelId + 1;
        else
            $NextRelId = '1';
        
        foreach($arrCASEID as $CA_CASEID => $Value) {
            $mPenugasan = Penugasan::where(['CA_CASEID' => $CA_CASEID])->first();
            $mPenugasan->CA_INVBY = $request->CA_INVBY;
            $mPenugasan->CA_ASGTO = $request->CA_INVBY;
            $mPenugasan->CA_ASGDT = Carbon::now();
            $mPenugasan->CA_CASESTS = 2;
            $mPenugasan->CA_INVSTS = $request->CA_INVSTS;
            $mPenugasan->CA_ASGBY = Auth::User()->id;
            $mPenugasan->CA_ANSWER = $request->CA_ANSWER;
            if($Value){
                $mPenugasan->CA_FILEREF = $Value;
            }
            if($request->CA_INVSTS == '2')
            {
                $mPenugasan->CA_INVDT = Carbon::now();
            }
            $mPenugasan->CA_MAGNCD = $request->CA_MAGNCD != '' ? $request->CA_MAGNCD : null;
            if ($mPenugasan->save()) {
                $SuratPengadu = Letter::where(['letter_code' => $request->CA_INVSTS, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
                $SuratPegawai = Letter::where(['letter_code' => $request->CA_INVSTS, 'letter_type' => '02', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
                
                if($request->recipient) {
                    $arrRow = '';
                    foreach($request->recipient as $recipient) {
                        $arrRow .= '<tr>'
                                . '<td style="width:41%">&nbsp;&nbsp;&nbsp;</td>'
                                . '<td>: '.$recipient.'</td>'
                                . '</tr>';
                    }
                    $multi = '<table style="width:800px">'
                            .$arrRow
                            . '</table>';
                }else{
                    $multi = '';
                }
                
                if($SuratPengadu){
                    $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
                }
                if($SuratPegawai){
                    $ContentSuratPegawai = $SuratPegawai->header . $multi . $SuratPegawai->body . $SuratPegawai->footer;
                }
                $ProfilPegawai = User::find($mPenugasan->CA_INVBY);
                $ProfilPegawaiTugasOleh = User::find($mPenugasan->CA_ASGBY);
                
                if(!empty($mPenugasan->CA_STATECD)){
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mPenugasan->CA_STATECD])->first();
                    if (!$StateNm) {
                        $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'334','code'=>$mPenugasan->CA_STATECD])->first();
                    }
                    $CA_STATECD = $StateNm->descr;
                } else {
                    $CA_STATECD = '';
                }
                if(!empty($mPenugasan->CA_DISTCD)){
                    $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mPenugasan->CA_DISTCD])->first();
                    if (!$DestrictNm){
                        $CA_DISTCD = $mPenugasan->CA_DISTCD;
                    } else {
                        $CA_DISTCD = $DestrictNm->descr;
                    }
                } else {
                    $CA_DISTCD = '';
                }
                
                if ($request->CA_INVSTS == '2') //  DALAM SIASATAN
                {
                    $patternsPengadu[1] = "#NAMAPENGADU#";
                    $patternsPengadu[2] = "#ALAMATPENGADU#";
                    $patternsPengadu[3] = "#POSKODPENGADU#";
                    $patternsPengadu[4] = "#DAERAHPENGADU#";
                    $patternsPengadu[5] = "#NEGERIPENGADU#";
                    $patternsPengadu[6] = "#NOADUAN#";
                    $patternsPengadu[7] = "#NOTELPEJABATPEGAWAI#";
                    $patternsPengadu[8] = "#EMAILPEGAWAIPENYIASAT#";
                    $patternsPengadu[9] = "#NAMAPEGAWAIPENYIASAT#";
                    $patternsPengadu[10] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                    $patternsPengadu[11] = "#TARIKHPENUGASAN#";
                    $replacementsPengadu[1] = $mPenugasan->CA_NAME;
                    $replacementsPengadu[2] = $mPenugasan->CA_ADDR != ''? $mPenugasan->CA_ADDR : '';
                    $replacementsPengadu[3] = $mPenugasan->CA_POSCD != ''? $mPenugasan->CA_POSCD : '';
                    $replacementsPengadu[4] = $CA_DISTCD;
                    $replacementsPengadu[5] = $CA_STATECD;
                    $replacementsPengadu[6] = $CA_CASEID;
                    $replacementsPengadu[7] = $ProfilPegawai->cawangan ? $ProfilPegawai->cawangan->BR_TELNO : '';
                    $replacementsPengadu[8] = $ProfilPegawai->email;
                    $replacementsPengadu[9] = $ProfilPegawai->name;
                    $replacementsPengadu[10] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' ' 
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />' 
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
                    $replacementsPengadu[11] = Carbon::now()->format('d/m/Y');
                    
//                    $tarikhPenerimaan = date('d/m/Y', strtotime($mPenugasan->CA_RCVDT));
//                    $kodHariPenerimaan = date('N', strtotime($mPenugasan->CA_RCVDT));
//                    $namaHariPenerimaan = Ref::GetDescr('156', $kodHariPenerimaan, 'ms');
                    $patternsPegawai[1] = "#NEGERIPEGAWAI#";
                    $patternsPegawai[2] = "#CAWANGANPEGAWAI#";
                    $patternsPegawai[3] = "#TARIKHPENUGASAN#";
                    $patternsPegawai[4] = "#MASAPENUGASAN#";
                    $patternsPegawai[5] = "#NAMAPEGAWAIPENUGASAN#";
                    $patternsPegawai[6] = "#NOADUAN#";
                    $replacementsPegawai[1] = $ProfilPegawai->Negeri->descr;
                    $replacementsPegawai[2] = $ProfilPegawai->cawangan->BR_BRNNM;
//                    $replacementsPegawai[3] = $tarikhPenerimaan.' ('.$namaHariPenerimaan.')';
                    $replacementsPegawai[3] = '';
//                    $replacementsPegawai[4] = date('h:i A', strtotime($mPenugasan->CA_RCVDT));
                    $replacementsPegawai[4] = '';
                    $replacementsPegawai[5] = $ProfilPegawai->name;
                    $replacementsPegawai[6] = $CA_CASEID;
                }
                elseif ($request->CA_INVSTS == '4') { // RUJUK KE KEMENTERIAN/AGENSI LAIN
                    $mAgensi = DB::table('sys_min')->select('*')->where('MI_MINCD',$mPenugasan->CA_MAGNCD)->first();
                    $patternsPengadu[1] = "#NAMAAGENSI#";
                    $patternsPengadu[2] = "#ALAMATAGENSI#";
                    $patternsPengadu[3] = "#NOADUAN#";
                    $patternsPengadu[4] = "#KETERANGANADUAN#";
                    $patternsPengadu[5] = "#JAWAPANKEPADAPENGADU#";
                    $patternsPengadu[6] = "#NAMAPEGAWAIPENYIASAT#";
                    $patternsPengadu[7] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                    $patternsPengadu[8] = "#NAMAPENGADU#";
                    $patternsPengadu[9] = "#ALAMATPENGADU#";
                    $patternsPengadu[10] = "#POSKODPENGADU#";
                    $patternsPengadu[11] = "#DAERAHPENGADU#";
                    $patternsPengadu[12] = "#NEGERIPENGADU#";
                    $patternsPengadu[13] = "#NOTELEFONAGENSI#";
                    $patternsPengadu[14] = "#EMELAGENSI#";
                    $patternsPengadu[15] = "#TARIKHRUJUKAGENSI#";
                    $replacementsPengadu[1] = !empty($mAgensi) ? $mAgensi->MI_DESC : '';
                    $replacementsPengadu[2] = !empty($mAgensi) ? $mAgensi->MI_ADDR . '<br />'
                        . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $mAgensi->MI_POSCD. ' '
                        . \App\Ref::GetDescr('18', $mAgensi->MI_DISTCD, 'ms') . '<br />'
                        . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . \App\Ref::GetDescr('17', $mAgensi->MI_STATECD, 'ms') : '';
                    $replacementsPengadu[3] = $CA_CASEID;
                    $replacementsPengadu[4] = $mPenugasan->CA_SUMMARY;
                    $replacementsPengadu[5] = $mPenugasan->CA_ANSWER;
                    $replacementsPengadu[6] = $ProfilPegawaiTugasOleh->name;
                    $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM.'<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1.'<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2.'<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
                        . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
                    $replacementsPengadu[8] = $mPenugasan->CA_NAME;
                    $replacementsPengadu[9] = $mPenugasan->CA_ADDR != ''? $mPenugasan->CA_ADDR : '';
                    $replacementsPengadu[10] = $mPenugasan->CA_POSCD != ''? $mPenugasan->CA_POSCD : '';
                    $replacementsPengadu[11] = $CA_DISTCD;
                    $replacementsPengadu[12] = $CA_STATECD;
                    $replacementsPengadu[13] = !empty($mAgensi) ? $mAgensi->MI_TELNO : '';
                    $replacementsPengadu[14] = !empty($mAgensi) ? $mAgensi->MI_EMAIL : '';
                    $replacementsPengadu[15] = Carbon::now()->format('d/m/Y');

                    $mAgensi = Agensi::where(['MI_MINCD' => $mPenugasan->CA_MAGNCD, 'MI_STS' => '1'])->first();
                    if ($mAgensi->MI_EMAIL) {
                        $mPenyiasatan = \App\Aduan\Penyiasatan::where(['CA_CASEID' => $CA_CASEID])->first();
                        try {
                            if (App::environment(['production'])) {
                                Mail::to($mAgensi->MI_EMAIL)
                                    ->cc($ProfilPegawaiTugasOleh->email)
                                    ->send(new RujukAgensiLain($mPenyiasatan));
                            } else {
                                Mail::to($ProfilPegawaiTugasOleh->email)
                                    ->cc($ProfilPegawaiTugasOleh->email)
                                    ->send(new RujukAgensiLain($mPenyiasatan));
                            }
                        } catch (Exception $e) {
                            
                        }
                    }
                } 
                elseif ($request->CA_INVSTS == '5') { // Rujuk Ke Tribunal
                    $patternsPengadu[1] = "#NAMAPENGADU#";
                    $patternsPengadu[2] = "#ALAMATPENGADU#";
                    $patternsPengadu[3] = "#POSKODPENGADU#";
                    $patternsPengadu[4] = "#DAERAHPENGADU#";
                    $patternsPengadu[5] = "#NEGERIPENGADU#";
                    $patternsPengadu[6] = "#NOADUAN#";
                    $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
                    $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                    $patternsPengadu[9] = "#TARIKHRUJUKTRIBUNAL#";
                    $replacementsPengadu[1] = $mPenugasan->CA_NAME;
                    $replacementsPengadu[2] = $mPenugasan->CA_ADDR != ''? $mPenugasan->CA_ADDR : '';
                    $replacementsPengadu[3] = $mPenugasan->CA_POSCD != ''? $mPenugasan->CA_POSCD : '';
                    $replacementsPengadu[4] = $CA_DISTCD;
                    $replacementsPengadu[5] = $CA_STATECD;
                    $replacementsPengadu[6] = $CA_CASEID;
                    $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->name;
                    $replacementsPengadu[8] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM . '<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1 . '<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2 . '<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
                        . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
                    $replacementsPengadu[9] = Carbon::now()->format('d/m/Y');
                }
                else if ($request->CA_INVSTS == '7') //  MAKLUMAT TIDAK LENGKAP
                {  
                    $patternsPengadu[1] = "#NAMAPENGADU#";
                    $patternsPengadu[2] = "#ALAMATPENGADU#";
                    // $patternsPengadu[3] = "#POSKODPENGADU#";
                    $patternsPengadu[3] = "#DAERAHPENGADU#";
                    $patternsPengadu[4] = "#NEGERIPENGADU#";
                    $patternsPengadu[5] = "#NOADUAN#";
                    $patternsPengadu[6] = "#JAWAPANKEPADAPENGADU#";
                    $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
                    $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                    $patternsPengadu[9] = "#TARIKHMAKLUMATTIDAKLENGKAP#";
                    $replacementsPengadu[1] = $mPenugasan->CA_NAME;
                    $replacementsPengadu[2] = $mPenugasan->CA_ADDR != ''? $mPenugasan->CA_ADDR : '';
                    // $replacementsPengadu[3] = $mPenugasan->CA_POSCD != ''? $mPenugasan->CA_POSCD : '';
                    $replacementsPengadu[3] = $CA_DISTCD;
                    $replacementsPengadu[4] = $CA_STATECD;
                    $replacementsPengadu[5] = $CA_CASEID;
                    $replacementsPengadu[6] = $mPenugasan->CA_ANSWER;
                    $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->name;
                    $replacementsPengadu[8] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM . '<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1 . '<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2 . '<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
                        . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />'
                        . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
                    $replacementsPengadu[9] = Carbon::now()->format('d/m/Y');
                    if($mPenugasan->CA_EMAIL){
                        try {
                            Mail::to($mPenugasan->CA_EMAIL)->send(new MaklumatAduanTaklengkap($mPenugasan));
                        } catch (Exception $e) {
                            
                        }
                    }
                }
                else if($request->CA_INVSTS == '8') //  LUAR BIDANG KUASA
                    {   
                    $patternsPengadu[1] = "#NAMAPENGADU#";
                    $patternsPengadu[2] = "#ALAMATPENGADU#";
                    $patternsPengadu[3] = "#POSKODPENGADU#";
                    $patternsPengadu[4] = "#DAERAHPENGADU#";
                    $patternsPengadu[5] = "#NEGERIPENGADU#";
                    $patternsPengadu[6] = "#NOADUAN#";
                    $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
                    $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                    $patternsPengadu[9] = "#TARIKHLUARBIDANGKUASA#";
                    $replacementsPengadu[1] = $mPenugasan->CA_NAME;
                    $replacementsPengadu[2] = $mPenugasan->CA_ADDR != ''? $mPenugasan->CA_ADDR : '';
                    $replacementsPengadu[3] = $mPenugasan->CA_POSCD != ''? $mPenugasan->CA_POSCD : '';
                    $replacementsPengadu[4] = $CA_DISTCD;
                    $replacementsPengadu[5] = $CA_STATECD;
                    $replacementsPengadu[6] = $CA_CASEID;
                    $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->name;
                    $replacementsPengadu[8] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM.'<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
                    . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
                    $replacementsPengadu[9] = Carbon::now()->format('d/m/Y');
                    if($mPenugasan->CA_EMAIL){
                        try {
                            Mail::to($mPenugasan->CA_EMAIL)->send(new AduanLuarBidang($mPenugasan));
                        } catch (Exception $e) {
                            
                        }
                    }
                } 
                else if($request->CA_INVSTS == '6') //  PERTANYAAN
                    {   
                }
                
                $date = date('YmdHis');
                $userid = Auth::user()->id;

                if(!empty($SuratPengadu)) {
                    $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
                    $arr_repPengadu = array("#", "#");
                    $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
                    $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

                    $filename = $userid . '_' . $CA_CASEID . '_' . $date . '_1.pdf';
                    Storage::disk('letter')->put($filename, $SuratPengaduHtml->output()); // Store PDF to storage

                    $AttachSuratPengadu = new Attachment();
                    $AttachSuratPengadu->doc_title = $SuratPengadu->title;
                    $AttachSuratPengadu->file_name = $SuratPengadu->title;
                    $AttachSuratPengadu->file_name_sys = $filename;
                    if($AttachSuratPengadu->save()) {
                        $SuratPengaduId = $AttachSuratPengadu->id;
                    }
                }else{
                    $SuratPengaduId = NULL;
                }
                
                if(!empty($SuratPegawai)) {
                    $SuratPegawaiReplace = preg_replace($patternsPegawai, $replacementsPegawai, urldecode($ContentSuratPegawai));
                    $arr_repPegawai = array("#", "#");
                    $SuratPegawaiFinal = str_replace($arr_repPegawai, "", $SuratPegawaiReplace); // SuratPengadu in HTML
                    $SuratPegawaiHtml = PDF::loadHTML($SuratPegawaiFinal); // Generate PDF from HTML

                    $filename = $userid . '_' . $CA_CASEID . '_' . $date . '_2.pdf';
                    Storage::disk('letter')->put($filename, $SuratPegawaiHtml->output()); // Store PDF to storage

                    $AttachSuratPegawai = new Attachment();
                    $AttachSuratPegawai->doc_title = $SuratPegawai->title;
                    $AttachSuratPegawai->file_name = $SuratPegawai->title;
                    $AttachSuratPegawai->file_name_sys = $filename;
                    if($AttachSuratPegawai->save()) {
                        $SuratPegawaiId = $AttachSuratPegawai->id;
                    }
                }else{
                    $SuratPegawaiId = NULL;
                }
                
                PenugasanCaseDetail::where(['CD_CASEID' => $CA_CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                $mCaseDetail = new PenugasanCaseDetail();
                $mCaseDetail->CD_CASEID = $mPenugasan->CA_CASEID;
                if ($request->expectsJson()) {
                    $mCaseDetail->CD_TYPE = 'S'; // EZSTAR
                } else {
                    $mCaseDetail->CD_TYPE = 'D';
                }
                $mCaseDetail->CD_DESC = $request->CD_DESC;
                $mCaseDetail->CD_INVSTS = $request->CA_INVSTS;
                $mCaseDetail->CD_CASESTS = 2;
                $mCaseDetail->CD_CURSTS = 1;
                $mCaseDetail->CD_DOCATTCHID_PUBLIC = $SuratPengaduId;
                $mCaseDetail->CD_DOCATTCHID_ADMIN = $SuratPegawaiId;
                $mCaseDetail->CD_ACTFROM = Auth::User()->id;
                $mCaseDetail->CD_ACTTO = $mPenugasan->CA_INVBY;
                $mCaseDetail->CD_REASON_DURATION = $request->CD_REASON_DURATION["'$CA_CASEID'"];
                if($request->CD_REASON_DURATION["'$CA_CASEID'"] >= 4){
                    if($request->submit == 1) {
                        $mCaseDetail->CD_REASON = $request->CD_REASON;
                        if($request->CD_REASON == 'P2') {
                            $mCaseDetail->CD_REASON_DATE_FROM =
                                Carbon::parse($request->CD_REASON_DATE_FROM);
                            $mCaseDetail->CD_REASON_DATE_TO =
                                Carbon::parse($request->CD_REASON_DATE_TO);
                        }
                    } else if($request->submit == 2) {
                        $mCaseDetail->CD_REASON = $request->CD_REASON["'$CA_CASEID'"];
                        if($request->CD_REASON["'$CA_CASEID'"] == 'P2') {
                            $mCaseDetail->CD_REASON_DATE_FROM =
                                Carbon::parse($request->CD_REASON_DATE_FROM["'$CA_CASEID'"]);
                            $mCaseDetail->CD_REASON_DATE_TO =
                                Carbon::parse($request->CD_REASON_DATE_TO["'$CA_CASEID'"]);
                        }
                    }
                }
//                $mCaseDetail->save();
                if ($mCaseDetail->save()) {
                    if($request->submit == 1) {
                        $CaseRelation = new GabungRelation();
                        $CaseRelation->CR_RELID = $NextRelId;
                        $CaseRelation->CR_CASEID = $CA_CASEID;
                        $CaseRelation->save();
                    }
                }
            }
        }
        
        if ($request->expectsJson()) {
            return response()->json(['data' => 'Aduan telah berjaya diberi penugasan']);
        }
        $request->session()->flash('success', 'Aduan telah <b>berjaya</b> diberi Penugasan');
        return redirect('tugas');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
