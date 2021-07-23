<?php

namespace App\Http\Controllers\Integriti;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Attachment;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
use App\Integriti\IntegritiAdminDoc;
use App\Letter;
use App\Mail\IntegritiMaklumatAduanTidakLengkap;
use App\Mail\IntegritiMaklumanStatus;
use App\User;
use DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class IntegritiTugasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('integriti.tugas.index');
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
    public function edit($id)
    {
        $model = IntegritiAdmin::find($id);
        if ($model) {
            $mUser = User::find(Auth::User()->id);
            $mPenugasan = IntegritiAdmin::
            // where(['IN_CASEID' => $IN_CASEID])
            where(function ($query) use ($id, $model) {
                $query->where('IN_CASEID', $id)
                    ->orWhere('IN_CASEID', $model->IN_CASEID);
            })
            ->first();
            // $mPenugasanDetail = PenugasanCaseDetail::where(['CD_CASEID' => $IN_CASEID,'CD_INVSTS' => '2'])->first();
            $mPenugasanDetail = IntegritiAdminDetail::
                // where(['CD_CASEID' => $IN_CASEID,'CD_INVSTS' => '2'])
                where(function ($query) use ($id, $model) {
                    $query->where('ID_CASEID', $id)
                        ->orWhere('ID_CASEID', $model->IN_CASEID);
                })
                ->where(['ID_INVSTS' => '02'])
                ->first();
            $mBukaSemula = DB::table('integriti_case_forward')
                ->where(['IF_FORWARD_CASEID' => $model->IN_CASEID])
                ->first();
            if($mBukaSemula){
                $mBukaSemulaOld = IntegritiAdmin::
                where(['IN_CASEID' => $mBukaSemula->IF_CASEID])
                ->first();
            } else {
                $mBukaSemulaOld = '';
            }
            return view(
                'integriti.tugas.edit', compact(
                    'mUser', 'mPenugasan',
                    // 'IN_CASEID',
                    'mPenugasanDetail','mBukaSemula','mBukaSemulaOld'
                )
            );
        } else {
            return redirect()->route('integrititugas.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'IN_CLASSIFICATION' => 'required',
            'IN_CMPLCAT' => 'required_if:IN_CLASSIFICATION,1',
            'ID_DESC' => 'required_if:IN_INVSTS,02',
            'ID_DESC_NOTE' => 'required_if:IN_INVSTS,08',
            'IN_INVBY' => 'required_if:IN_INVSTS,02',
            // 'IN_CMPLCD' => 'required_if:IN_INVSTS,02',
            'IN_ANSWER' => 'required_if:IN_INVSTS,02,IN_INVSTS,04,IN_INVSTS,05,IN_INVSTS,06,IN_INVSTS,07,IN_INVSTS,08',
            'IN_ACTION_BRSTATECD' => 'required_if:IN_INVSTS,013',
            'IN_ACTION_BRNCD' => 'required_if:IN_INVSTS,013',
            'IN_MAGNCD' => 'required_if:IN_INVSTS,014',
            'IN_INVSTS' => 'required',
            // 'IN_SUMMARY' => 'required',
            'IN_IPNO' => 'required_if:IN_INVSTS,02|max:30',
            'IN_MEETINGNUM' => 'required_if:IN_INVSTS,02,IN_INVSTS,05,IN_INVSTS,013,IN_INVSTS,014',
            'IN_ACCESSIND' => 'required_if:IN_INVSTS,02',
        ],
        [
            'IN_CLASSIFICATION.required' => 'Ruangan Klasifikasi diperlukan.',
            'IN_CMPLCAT.required_if' => 'Ruangan Kategori diperlukan.',
            'ID_DESC.required_if' => 'Ruangan Hasil Keputusan JMA diperlukan.',
            'ID_DESC_NOTE.required_if' => 'Ruangan Catatan diperlukan.',
            'IN_INVBY.required_if' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan.',
            // 'IN_CMPLCD.required_if' => 'Ruangan SubKategori diperlukan.',
            'IN_ANSWER.required_if' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
            'IN_ACTION_BRSTATECD.required_if' => 'Ruangan Negeri diperlukan.',
            'IN_ACTION_BRNCD.required_if' => 'Ruangan Bahagian / Cawangan diperlukan.',
            'IN_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
            'IN_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
            // 'IN_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
            'IN_IPNO.required_if' => 'Ruangan No. IP diperlukan.',
            'IN_IPNO.max' => 'Ruangan No. IP mesti tidak melebihi :max aksara.',
            'IN_MEETINGNUM.required_if' => 'Ruangan No. Bilangan Mesyuarat JMA diperlukan.',
            'IN_ACCESSIND.required_if' => 'Ruangan Akses Maklumat Pengadu diperlukan.',
        ]);
        // $mPenugasan = Penugasan::where(['IN_CASEID' => $IN_CASEID])->first();
        $mPenugasan = IntegritiAdmin::find($id);
        if($request->IN_INVSTS == '02')
        // Penugasan ( dalam siasatan )
        {
            $mPenugasan->IN_INVBY = $request->IN_INVBY;
            $mPenugasan->IN_ASGTO = $request->IN_INVBY;
            $mPenugasan->IN_CMPLCAT = $request->IN_CMPLCAT;
            // $mPenugasan->IN_CMPLCD = $request->IN_CMPLCD;
            $mPenugasan->IN_INVDT = Carbon::now();
            $mPenugasan->IN_ACCESSIND = $request->IN_ACCESSIND;
            $mPenugasan->IN_ACCESSBY = Auth::User()->id;
            $mPenugasan->IN_ACCESSDATE = Carbon::now();
            $mPenugasan->IN_CASESTS = '2';
            $mPenugasan->IN_IPNO = $request->IN_IPNO;
            $mPenugasan->IN_ANSWER = $request->IN_ANSWER;
        }
        elseif($request->IN_INVSTS == '04') 
        // Penutupan (Rujuk Agensi)
        {
            // $mPenugasan->IN_MAGNCD = $request->IN_MAGNCD;
            $mPenugasan->IN_ANSWER = $request->IN_ANSWER;
            $mPenugasan->IN_CASESTS = '2';
        }
        elseif (in_array($request->IN_INVSTS, ['05','06','08']))
        // Penutupan (nfa, pertanyaan, luar bidang kuasa)
        {
//            $mPenugasan->IN_INVBY = '';
//            $mPenugasan->IN_ASGTO = '';
            $mPenugasan->IN_ANSWER = $request->IN_ANSWER;
            $mPenugasan->IN_CASESTS = '2';
        }
        elseif (in_array($request->IN_INVSTS, ['07']))
        // maklumat tidak lengkap
        {
            $mPenugasan->IN_ANSWER = $request->IN_ANSWER;
            $mPenugasan->IN_CASESTS = '1';
        }
        elseif (in_array($request->IN_INVSTS, ['014']))
        // dalam tindakan (agensi)
        {
            $mPenugasan->IN_MAGNCD = $request->IN_MAGNCD;
        }
        $mPenugasan->IN_ASGDT = Carbon::now();
        // $mPenugasan->IN_CASESTS = 2;
        $mPenugasan->IN_INVSTS = $request->IN_INVSTS;
        $mPenugasan->IN_ASGBY = Auth::User()->id;
        // $mPenugasan->IN_FILEREF = $request->IN_FILEREF;
        $mPenugasan->IN_MEETINGNUM = $request->IN_MEETINGNUM;
        $mPenugasan->IN_CLASSIFICATION = $request->IN_CLASSIFICATION;
        $mPenugasan->IN_ACTION_BRSTATECD = $request->IN_ACTION_BRSTATECD;
        $mPenugasan->IN_ACTION_BRNCD = $request->IN_ACTION_BRNCD;
        // $mPenugasan->IN_CMPLCAT = $request->IN_CMPLCAT;
//        $mPenugasan->IN_CMPLCD = $request->IN_CMPLCD;
        // $mPenugasan->IN_SUMMARY = $request->IN_SUMMARY;
        if ($mPenugasan->save()) {

            $SuratPengadu = Letter::
                where(['letter_code' => $request->IN_INVSTS, 'letter_type' => '01', 'status' => '1'])
                ->first(); 
            // Templete Surat Kepada Pengadu
            // $SuratPegawai = Letter::where(['letter_code' => $request->IN_INVSTS, 'letter_type' => '02', 'status' => '1'])->first(); // Templete Surat Kepada Pegawai
            
//             if($request->recipient) {
//                 $arrRow = '';
//                 foreach($request->recipient as $recipient) {
//                     $arrRow .= '<tr>'
//                             . '<td style="width:41%">&nbsp;&nbsp;&nbsp;</td>'
//                             . '<td>: '.$recipient.'</td>'
//                             . '</tr>';
//                 }
//                 $multi = '<table style="width:800px">'
//                         .$arrRow
//                         . '</table>';
//             }else{
//                 $multi = '';
//             }
            
            if($SuratPengadu){
                $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
            } else {
                $ContentSuratPengadu = '';
            }
//             if($SuratPegawai)
//             $ContentSuratPegawai = $SuratPegawai->header . $multi . $SuratPegawai->body . $SuratPegawai->footer;
            
//             $ProfilPegawai = User::find($mPenugasan->IN_INVBY);
//             $ProfilPegawaiTugasOleh = User::find($mPenugasan->IN_ASGBY);
            
//             if(!empty($mPenugasan->IN_STATECD)){
//                 $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mPenugasan->IN_STATECD])->first();
//                 if (!$StateNm) {
//                     $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'334','code'=>$mPenugasan->IN_STATECD])->first();
//                 }
//                 $IN_STATECD = $StateNm->descr;
//             } else {
//                 $IN_STATECD = '';
//             }
//             if(!empty($mPenugasan->IN_DISTCD)){
//                 $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mPenugasan->IN_DISTCD])->first();
//                 if (!$DestrictNm){
//                     $IN_DISTCD = $mPenugasan->IN_DISTCD;
//                 } else {
//                     $IN_DISTCD = $DestrictNm->descr;
//                 }
//             } else {
//                 $IN_DISTCD = '';
//             }
            if($mPenugasan->indistcd){
                $IN_DISTCD = $mPenugasan->indistcd->descr;
            } else {
                $IN_DISTCD = '';
            }
            if($mPenugasan->instatecd){
                $IN_STATECD = $mPenugasan->instatecd->descr;
            } else {
                $IN_STATECD = '';
            }
            
            if ($request->IN_INVSTS == '02') //  DALAM SIASATAN
            {
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#JAWAPANKEPADAPENGADU#";
                $replacementsPengadu[1] = $mPenugasan->IN_NAME;
                $replacementsPengadu[2] = 
                $mPenugasan->IN_ADDR 
                    ? nl2br(htmlspecialchars($mPenugasan->IN_ADDR)) 
                    : '';
                $replacementsPengadu[3] = $mPenugasan->IN_POSTCD
                    // != ''? $mPenugasan->IN_POSTCD : ''
                    ;
                $replacementsPengadu[4] = $IN_DISTCD;
                $replacementsPengadu[5] = $IN_STATECD;
                $replacementsPengadu[6] = $mPenugasan->IN_CASEID;
                $replacementsPengadu[7] = 
                    $mPenugasan->IN_ANSWER 
                    ? nl2br(htmlspecialchars($mPenugasan->IN_ANSWER)) 
                    : '';
            } 
            // elseif ($request->IN_INVSTS == '4') { // RUJUK KE KEMENTERIAN/AGENSI LAIN
//                 $mAgensi = DB::table('sys_min')->select('*')->where('MI_MINCD',$mPenugasan->IN_MAGNCD)->first();
//                 $patternsPengadu[1] = "#NAMAAGENSI#";
//                 $patternsPengadu[2] = "#ALAMATAGENSI#";
//                 $patternsPengadu[3] = "#NOADUAN#";
//                 $patternsPengadu[4] = "#KETERANGANADUAN#";
//                 $patternsPengadu[5] = "#JAWAPANKEPADAPENGADU#";
//                 $patternsPengadu[6] = "#NAMAPEGAWAIPENYIASAT#";
//                 $patternsPengadu[7] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
//                 $patternsPengadu[8] = "#NAMAPENGADU#";
//                 $patternsPengadu[9] = "#ALAMATPENGADU#";
//                 $patternsPengadu[10] = "#POSKODPENGADU#";
//                 $patternsPengadu[11] = "#DAERAHPENGADU#";
//                 $patternsPengadu[12] = "#NEGERIPENGADU#";
//                 $patternsPengadu[13] = "#NOTELEFONAGENSI#";
//                 $patternsPengadu[14] = "#EMELAGENSI#";
//                 $replacementsPengadu[1] = !empty($mAgensi) ? $mAgensi->MI_DESC : '';
//                 $replacementsPengadu[2] = !empty($mAgensi) ? $mAgensi->MI_ADDR . '<br />' 
//                     . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $mAgensi->MI_POSCD. ' '
//                     . \App\Ref::GetDescr('18', $mAgensi->MI_DISTCD, 'ms') . '<br />' 
//                     . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . \App\Ref::GetDescr('17', $mAgensi->MI_STATECD, 'ms') : '';
//                 $replacementsPengadu[3] = $IN_CASEID;
//                 $replacementsPengadu[4] = $mPenugasan->IN_SUMMARY;
//                 $replacementsPengadu[5] = $mPenugasan->IN_ANSWER;
//                 $replacementsPengadu[6] = $ProfilPegawaiTugasOleh->name;
//                 $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM.'<br />'
//                     . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1.'<br />'
//                     . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2.'<br />'
//                     . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
//                     . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />' 
//                     . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
//                 $replacementsPengadu[8] = $mPenugasan->IN_NAME;
//                 $replacementsPengadu[9] = $mPenugasan->IN_ADDR != ''? $mPenugasan->IN_ADDR : '';
//                 $replacementsPengadu[10] = $mPenugasan->IN_POSCD != ''? $mPenugasan->IN_POSCD : '';
//                 $replacementsPengadu[11] = $IN_DISTCD;
//                 $replacementsPengadu[12] = $IN_STATECD;
//                 $replacementsPengadu[13] = !empty($mAgensi) ? $mAgensi->MI_TELNO : '';
//                 $replacementsPengadu[14] = !empty($mAgensi) ? $mAgensi->MI_EMAIL : '';
                
//                 $mAgensiData = Agensi::where(['MI_MINCD' => $mPenugasan->IN_MAGNCD, 'MI_STS' => '1'])->first();
//                 if ($mAgensiData->MI_EMAIL) {
//                     $mPenyiasatan = \App\Aduan\Penyiasatan::where(['IN_CASEID' => $IN_CASEID])->first();
//                     Mail::to($mAgensi->MI_EMAIL)->send(new RujukAgensiLain($mPenyiasatan));
//                 }
//             } elseif ($request->IN_INVSTS == '5') { // Rujuk Ke Tribunal
//                 $patternsPengadu[1] = "#NAMAPENGADU#";
//                 $patternsPengadu[2] = "#ALAMATPENGADU#";
//                 $patternsPengadu[3] = "#POSKODPENGADU#";
//                 $patternsPengadu[4] = "#DAERAHPENGADU#";
//                 $patternsPengadu[5] = "#NEGERIPENGADU#";
//                 $patternsPengadu[6] = "#NOADUAN#";
//                 $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
//                 $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
//                 $replacementsPengadu[1] = $mPenugasan->IN_NAME;
//                 $replacementsPengadu[2] = $mPenugasan->IN_ADDR != ''? $mPenugasan->IN_ADDR : '';
//                 $replacementsPengadu[3] = $mPenugasan->IN_POSCD != ''? $mPenugasan->IN_POSCD : '';
//                 $replacementsPengadu[4] = $IN_DISTCD;
//                 $replacementsPengadu[5] = $IN_STATECD;
//                 $replacementsPengadu[6] = $IN_CASEID;
//                 $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->name;
//                 $replacementsPengadu[8] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM . '<br />'
//                     . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1 . '<br />'
//                     . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2 . '<br />'
//                     . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
//                     . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />'
//                     . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
//             } 
            else if ($request->IN_INVSTS == '07') { //  MAKLUMAT TIDAK LENGKAP
                $patternsPengadu[1] = "#NOADUAN#";
                $patternsPengadu[2] = "#NAMAPENGADU#";
                $patternsPengadu[3] = "#ALAMATPENGADU#";
                $patternsPengadu[4] = "#POSKODPENGADU#";
                $patternsPengadu[5] = "#DAERAHPENGADU#";
                $patternsPengadu[6] = "#NEGERIPENGADU#";
                $replacementsPengadu[1] = $mPenugasan->IN_CASEID;
                $replacementsPengadu[2] = $mPenugasan->IN_NAME;
                $replacementsPengadu[3] = 
                    $mPenugasan->IN_ADDR 
                    ? nl2br(htmlspecialchars($mPenugasan->IN_ADDR)) 
                    : '';
                $replacementsPengadu[4] = $mPenugasan->IN_POSTCD != '' ? $mPenugasan->IN_POSTCD : '';
                $replacementsPengadu[5] = $IN_DISTCD;
                $replacementsPengadu[6] = $IN_STATECD;
                
                if($mPenugasan->IN_EMAIL){
                    try {
                        Mail::to($mPenugasan->IN_EMAIL)
                            ->send(new IntegritiMaklumatAduanTidakLengkap($mPenugasan))
                            ;
                        // Send biasa
                        // Mail::to($Request->user())->queue(new AduanTerimaPublic($mPubCase)); // Send pakai queue
                    } catch (Exception $e) {
                                
                    }
                }
            } 
            else if($request->IN_INVSTS == '08') { //  LUAR BIDANG KUASA
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#CATATAN#";
                $replacementsPengadu[1] = $mPenugasan->IN_NAME;
                $replacementsPengadu[2] = 
                    $mPenugasan->IN_ADDR 
                    ? nl2br(htmlspecialchars($mPenugasan->IN_ADDR)) 
                    : '';
                $replacementsPengadu[3] = $mPenugasan->IN_POSTCD 
                    // != ''? $mPenugasan->IN_POSTCD : ''
                    ;
                $replacementsPengadu[4] = $IN_DISTCD;
                $replacementsPengadu[5] = $IN_STATECD;
                $replacementsPengadu[6] = $mPenugasan->IN_CASEID;
                $replacementsPengadu[7] = 
                    $request->ID_DESC_NOTE
                    ? nl2br(htmlspecialchars($request->ID_DESC_NOTE)) 
                    : '';
//                 if($mPenugasan->IN_EMAIL){
//                     Mail::to($mPenugasan->IN_EMAIL)->send(new AduanLuarBidang($mPenugasan));
//                 }
            }
            // else if($request->IN_INVSTS == '6') { //  PERTANYAAN
                   
            // }
            
            $date = date('YmdHis');
            $userid = Auth::user()->id;
            
            if(!empty($SuratPengadu)) {
                $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
                $arr_repPengadu = array("#", "#");
                $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
                $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

                $filename = $userid . '_' . $id . '_' . $date . '_1.pdf';
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
            
//             if(!empty($SuratPegawai)) {
//                 $SuratPegawaiReplace = preg_replace($patternsPegawai, $replacementsPegawai, urldecode($ContentSuratPegawai));
//                 $arr_repPegawai = array("#", "#");
//                 $SuratPegawaiFinal = str_replace($arr_repPegawai, "", $SuratPegawaiReplace); // SuratPegawai in HTML
//                 $SuratPegawaiHtml = PDF::loadHTML($SuratPegawaiFinal); // Generate PDF from HTML

//                 $filenameSuratPegawai = $userid . '_' . $IN_CASEID . '_' . $date . '_2.pdf';
//                 Storage::disk('letter')->put($filenameSuratPegawai, $SuratPegawaiHtml->output()); // Store PDF to storage

//                 $AttachSuratPegawai = new Attachment();
//                 $AttachSuratPegawai->doc_title = $SuratPegawai->title;
//                 $AttachSuratPegawai->file_name = $SuratPegawai->title;
//                 $AttachSuratPegawai->file_name_sys = $filenameSuratPegawai;
//                 if($AttachSuratPegawai->save()) {
//                     $SuratPegawaiId = $AttachSuratPegawai->id;
//                 }
//             }else{
//                 $SuratPegawaiId = NULL;
//             }
            
            // IntegritiAdminDetail::where(['CD_CASEID' => $IN_CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
            IntegritiAdminDetail::
            // where(['CD_CASEID' => $IN_CASEID, 'CD_CURSTS' => '1'])
            where(function ($query) use ($id, $mPenugasan) {
                $query->where('ID_CASEID', $id)
                    ->orWhere('ID_CASEID', $mPenugasan->IN_CASEID);
            })
            ->update(['ID_CURSTS' => '0']);
            $mCaseDetail = new IntegritiAdminDetail();
            $mCaseDetail->ID_CASEID = $mPenugasan->IN_CASEID;
            // if ($request->expectsJson()) {
            //     $mCaseDetail->CD_TYPE = 'S'; // EZSTAR
            // } else {
            //     $mCaseDetail->CD_TYPE = 'D';
            // }
            if (in_array($request->IN_INVSTS, ['02'])) {
                $mCaseDetail->ID_DESC = $request->ID_DESC;
            } else if (in_array($request->IN_INVSTS, ['08'])) {
                $mCaseDetail->ID_DESC = $request->ID_DESC_NOTE;
            }
            $mCaseDetail->ID_INVSTS = $request->IN_INVSTS;
            // $mCaseDetail->ID_CASESTS = 2;
            $mCaseDetail->ID_CASESTS = $mPenugasan->IN_CASESTS;
            $mCaseDetail->ID_CURSTS = 1;
            $mCaseDetail->ID_DOCATACHID_PUBLIC = $SuratPengaduId;
            // $mCaseDetail->ID_DOCATTCHID_ADMIN = $SuratPegawaiId;
            $mCaseDetail->ID_ACTFROM = Auth::User()->id;
            $mCaseDetail->ID_ACTTO = $mPenugasan->IN_INVBY;
            if ($mCaseDetail->save()) {
                if ($request->expectsJson()) {
                    return response()->json(['data' => 'Aduan telah berjaya diberi penugasan']);
                }
                $request->session()->flash('success', 'Aduan telah berjaya diberi Penugasan');
                return redirect('integrititugas');
            }
        }
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

    public function __construct()
    {
//        $this->middleware('locale');
        $this->middleware(['locale','auth']);
    }

    public function getdatatable(DataTables $datatables, Request $request)
    {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        // $mPenugasan = Penugasan::where(['IN_CASESTS'=>1,'IN_BRNCD'=>Auth::user()->brn_cd])->whereIn('IN_INVSTS',[0,1])->orderBy('IN_CREDT', 'DESC');
        $mPenugasan = IntegritiAdmin::
            // where(['IN_BRNCD'=>Auth::user()->brn_cd])
            // ->
            whereIn('IN_INVSTS',['01','013','014'])
            ->orderBy('IN_CREATED_AT', 'DESC')
            ;
        $mUser = User::find(Auth::user()->id);
        
        if ($request->mobile) {
            return response()->json(['data' => $mPenugasan->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mPenugasan)
            ->addIndexColumn()
            // ->addColumn('check', '<input type="checkbox" class="i-checks" name="CASEID[]" value="{{ $IN_CASEID }}" onclick="anyCheck()">')
            // ->editColumn('IN_CASEID', function (Penugasan $penugasan) {
            //     return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
            // })
            ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                return view('integriti.base.summarylink', compact('integriti'))->render();
            })
            ->editColumn('IN_SUMMARY', function(IntegritiAdmin $penugasan) {
                if($penugasan->IN_SUMMARY != '')
                return implode(' ', array_slice(explode(' ', ucfirst($penugasan->IN_SUMMARY)), 0, 7)).'...';
                else
                return '';
            })
            ->editColumn('IN_INVSTS', function(IntegritiAdmin $penugasan) {
                // if($penugasan->IN_INVSTS != '')
                if($penugasan->invsts){
                    return $penugasan->invsts->descr;
                }
                else{
                    // return '';
                    return $penugasan->IN_INVSTS;
                }
            })
            ->editColumn('IN_RCVDT', function(IntegritiAdmin $penugasan) {
                if($penugasan->IN_RCVDT != '')
                return Carbon::parse($penugasan->IN_RCVDT)->format('d-m-Y h:i A');
                else
                return '';
            })
            // ->addColumn('tempoh', function(Penugasan $penugasan) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
            //     $totalDuration = $penugasan->GetDuration($penugasan->IN_RCVDT, $penugasan->IN_CASEID);
            //     if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
            //         return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //     else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
            //         return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //     else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
            //         return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //     else if ($totalDuration > $TempohKetiga->code)
            //         return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            // })
            // ->addColumn('action', '
            //     <a href="{{ route("tugas.penugasanaduan", $IN_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
            // ')
            ->addColumn('action', '
                <a href="{{ route("integrititugas.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
            ')
            // <a href="{{ url(\'tugas\penugasan_aduan\', $IN_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
            ->rawColumns([
                // 'check',
                'IN_CASEID',
                'action',
                // 'tempoh'
            ])
            ->filter(function ($query) use ($request) {
                if ($request->has('IN_CASEID')) {
                    $query->where('IN_CASEID', 'like', "%{$request->get('IN_CASEID')}%");
                }
                if ($request->has('IN_SUMMARY')) {
                    $query->where('IN_SUMMARY', 'like', "%{$request->get('IN_SUMMARY')}%");
                }
                if ($request->has('IN_AGAINSTNM')) {
                    $query->where('IN_AGAINSTNM', 'like', "%{$request->get('IN_AGAINSTNM')}%");
                }
            })
        ;
        return $datatables->make(true);
    }

    public function getdatatableuser(Request $request) {
        $mUser = User::with('role')
            ->select(
                'id',
                'username',
                'name', 
                // 'state_cd','brn_cd',
                DB::raw(
                    '(select count(IN_CASEID) from integriti_case_info where IN_INVBY = sys_users.id AND IN_INVSTS = "02") as count_case'
                )
            )
            ->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')
            ->where([
                'user_cat' => '1', 
                'status' => '1', 
                // 'brn_cd' => Auth::user()->brn_cd
            ])
            ->whereIn('sys_user_access.role_code',[
                '800',
                // '410','440','450'
                '191','192','193'
            ])
            ;
        
        if ($request->mobile) {
            return response()->json(['data' => $mUser->get()->toArray()]);
        }
        $datatables = Datatables::of($mUser)
            ->addIndexColumn()
            // ->editColumn('state_cd', function(User $user) {
            //     if($user->state_cd != '')
            //     return $user->Negeri->descr;
            //     else
            //     return '';
            // })
            // ->editColumn('brn_cd', function(User $user) {
            //     if($user->brn_cd != '')
            //     return $user->Cawangan->BR_BRNNM;
            //     else
            //     return '';
            // })
//                ->editColumn('tugas', function(User $user) {
//                    return Penugasan::GetCountTugas($user->id);
//                })
            ->editColumn('role.role_code', function (User $user) {
                // if($user->role->role_code != '')
                if(count($user->role) == 1)
                    return User::ShowRoleName($user->role->role_code);
                else
                    return '';
            })
            ->addColumn('action', '<a class="btn btn-xs btn-primary" onclick="myFunction({{ $id }})"><i class="fa fa-arrow-down"></i></a>')
            ->filter(function ($query) use ($request) {
                if ($request->has('icnew')) {
                    $query->where('username', 'like', "%{$request->get('icnew')}%");
                }
                if ($request->has('name')) {
                    $query->where('name', 'like', "%{$request->get('name')}%");
                }
                if ($request->has('role_cd')) {
                    $query->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')->where('sys_user_access.role_code', $request->get('role_cd'));
                }
            })
            ;
        return $datatables->make(true);
    }

    public function getmeetingdetail($id)
    {
        $model = DB::table('integriti_meetings')
            ->where('id', $id)
            ->pluck('id', 'IM_MEETINGNUM');
        return json_encode($model);
    }
}
