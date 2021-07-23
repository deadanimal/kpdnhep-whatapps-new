<?php

namespace App\Http\Controllers\Integriti;

use App\Integriti\IntegritiPublic;
use App\Integriti\IntegritiPublicDetail;
use App\Integriti\IntegritiPublicDoc;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\User;
use App\Letter;
use App\Agensi;
use PDF;
use Carbon\Carbon;
use App\Attachment;
use App\Ref;
use App\Role;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\ComplaintReceived;
use App\Events\TaskAssigned;
use Illuminate\Support\Facades\Mail;
use App\Mail\AduanLuarBidang;
use App\Mail\MaklumatAduanTaklengkap;
use App\Mail\RujukAgensiLain;
use App\Repositories\WordGeneratorRepository;

use Illuminate\Http\Request;

class PengurusanIntegritiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct()
    {
//        $this->middleware('locale');
        $this->middleware(['locale','auth']);
    }
    public function index()
    {
        // if (Auth::user()->Role->role_code == '170') { // PEGAWAI SIPAR
            return view('integriti.pengurusan.index_integriti');
        // }
    }

    public function getDataTableIntegriti(Datatables $datatables, Request $request)
    {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mIntegriti = IntegritiPublic::orderBy('IN_CREATED_AT', 'DESC'); //where('IN_INVSTS', '01')->
        $mUser = User::find(Auth::user()->id);
        
        if ($request->mobile) {
            return response()->json(['data' => $mIntegriti->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = Datatables::of($mIntegriti)
                ->addIndexColumn()
                ->editColumn('IN_CASEID', function (IntegritiPublic $integriti) {
                    return view('integriti.base.summarylink', compact('integriti'))->render();
                })
                ->editColumn('IN_SUMMARY', function(IntegritiPublic $integriti) {
                    if($integriti->IN_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', ucfirst($integriti->IN_SUMMARY)), 0, 7)).'...';
                    else
                    return '';
                })
                ->editColumn('IN_INVSTS', function(IntegritiPublic $integriti) {
                    if($integriti->IN_INVSTS != '')
                    return $integriti->StatusAduan->descr;
                    else
                    return '';
                })
                ->editColumn('IN_RCVDT', function(IntegritiPublic $integriti) {
                    if($integriti->IN_RCVDT != '')
                    return Carbon::parse($integriti->IN_RCVDT)->format('d-m-Y h:i A');
                    else
                    return '';
                })
                /*->addColumn('tempoh', function(Penugasan $penugasan) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
                    $totalDuration = $penugasan->GetDuration($penugasan->IN_RCVDT, $penugasan->IN_CASEID);
                    if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                        return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                    else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                        return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
                    else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                        return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                    else if ($totalDuration > $TempohKetiga->code)
                        return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                })*/
                ->addColumn('action', '
                    <a href="{{ route("pengurusanintegriti.pengurusanaduan", $IN_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
                ')
                ->rawColumns(['check','IN_CASEID','action','tempoh'])
                ->filter(function ($query) use ($request) {
                    if ($request->has('IN_CASEID')) {
                        $query->where('IN_CASEID', 'like', "%{$request->get('IN_CASEID')}%");
                    }
                    if ($request->has('IN_SUMMARY_TITLE')) {
                        $query->where('IN_SUMMARY_TITLE', 'like', "%{$request->get('IN_SUMMARY_TITLE')}%");
                    }
                    if ($request->has('IN_SUMMARY')) {
                        $query->where('IN_SUMMARY', 'like', "%{$request->get('IN_SUMMARY')}%");
                    }
                    if ($request->has('IN_AGAINSTNM')) {
                        $query->where('IN_AGAINSTNM', 'like', "%{$request->get('IN_AGAINSTNM')}%");
                    }
                });
            return $datatables->make(true);
    }
    
    public function getcmpllist($CMPLCAT) {
        $mCatList = DB::table('sys_ref')
            ->where(['cat' => '634', 'status' => '1'])
            ->where('code', 'like', "$CMPLCAT%")
            ->orderBy('sort')
            ->pluck('code', 'descr')
            ->prepend('', '-- SILA PILIH --');
        return json_encode($mCatList);
    }

    public function PengurusanAduan($IN_CASEID)
    {
        $mUser = User::find(Auth::User()->id);
        $mIntegriti = IntegritiPublic::where(['IN_CASEID' => $IN_CASEID])->first();
        $mIntegritiDetail = IntegritiPublicDetail::where(['ID_CASEID' => $IN_CASEID])->first();//,'ID_INVSTS' => '2'
        $mIntegritiPublicDoc = IntegritiPublicDoc::where(['ID_CASEID' => $IN_CASEID])->get();
        // $mBukaSemula = DB::table('case_forward')->where(['CF_FWRD_CASEID' => $IN_CASEID])->first();
        return view('integriti.pengurusan.pengurusan_aduan', compact('mUser', 'mIntegriti','IN_CASEID','mIntegritiDetail', 'mIntegritiPublicDoc'));
    }
    
    public function TukarStatus(Request $request, $IN_CASEID) {
        // dd($request->all());
        /* $this->validate($request, [
            'IN_INVSTS' => 'required',
            'IN_CMPLCAT' => 'required',
        ],
        [
            'IN_INVSTS.required' => 'Ruangan Status diperlukan.',
            'IN_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
        ]); */
        $mIntegriti = IntegritiPublic::where(['IN_CASEID' => $IN_CASEID])->first();
        $mIntegriti->IN_INVSTS = $request->IN_INVSTS;
        $mIntegriti->IN_CMPLCAT = $request->IN_CMPLCAT;

        if ($mIntegriti->save()) {
            
            /* $SuratPengadu = Letter::where(['letter_code' => $request->IN_INVSTS, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
            $SuratPegawai = Letter::where(['letter_code' => $request->IN_INVSTS, 'letter_type' => '02', 'status' => '1'])->first(); // Templete Surat Kepada Pegawai
            
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
            
            if($SuratPengadu)
            $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
            if($SuratPegawai)
            $ContentSuratPegawai = $SuratPegawai->header . $multi . $SuratPegawai->body . $SuratPegawai->footer;
            
            $ProfilPegawai = User::find($mIntegriti->IN_INVBY);
            $ProfilPegawaiTugasOleh = User::find($mIntegriti->IN_ASGBY);
            
            if(!empty($mIntegriti->IN_STATECD)){
                $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mIntegriti->IN_STATECD])->first();
                if (!$StateNm) {
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'334','code'=>$mIntegriti->IN_STATECD])->first();
                }
                $IN_STATECD = $StateNm->descr;
            } else {
                $IN_STATECD = '';
            }
            if(!empty($mIntegriti->IN_DISTCD)){
                $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mIntegriti->IN_DISTCD])->first();
                if (!$DestrictNm){
                    $IN_DISTCD = $mIntegriti->IN_DISTCD;
                } else {
                    $IN_DISTCD = $DestrictNm->descr;
                }
            } else {
                $IN_DISTCD = '';
            }
            
            if ($request->IN_INVSTS == '2') //  DALAM SIASATAN
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
                $replacementsPengadu[1] = $mIntegriti->IN_NAME;
                $replacementsPengadu[2] = $mIntegriti->IN_ADDR != ''? $mIntegriti->IN_ADDR : '';
                $replacementsPengadu[3] = $mIntegriti->IN_POSCD != ''? $mIntegriti->IN_POSCD : '';
                $replacementsPengadu[4] = $IN_DISTCD;
                $replacementsPengadu[5] = $IN_STATECD;
                $replacementsPengadu[6] = $IN_CASEID;
                $replacementsPengadu[7] = $ProfilPegawai->cawangan->BR_TELNO;
                $replacementsPengadu[8] = $ProfilPegawai->email;
                $replacementsPengadu[9] = $ProfilPegawai->name;
                $replacementsPengadu[10] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' ' 
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />' 
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;

//                $tarikhPenerimaan = date('d/m/Y', strtotime($mIntegriti->IN_RCVDT));
//                $kodHariPenerimaan = date('N', strtotime($mIntegriti->IN_RCVDT));
//                $namaHariPenerimaan = Ref::GetDescr('156', $kodHariPenerimaan, 'ms');
                $patternsPegawai[1] = "#NEGERIPEGAWAI#";
                $patternsPegawai[2] = "#CAWANGANPEGAWAI#";
                $patternsPegawai[3] = "#TARIKHPENUGASAN#";
                $patternsPegawai[4] = "#MASAPENUGASAN#";
                $patternsPegawai[5] = "#NAMAPEGAWAIPENUGASAN#";
                $patternsPegawai[6] = "#NOADUAN#";
                $replacementsPegawai[1] = $ProfilPegawai->Negeri->descr;
                $replacementsPegawai[2] = $ProfilPegawai->cawangan->BR_BRNNM;
//                $replacementsPegawai[3] = $tarikhPenerimaan.' ('.$namaHariPenerimaan.')';
                $replacementsPegawai[3] = '';
//                $replacementsPegawai[4] = date('h:i A', strtotime($mIntegriti->IN_RCVDT));
                $replacementsPegawai[4] = '';
                $replacementsPegawai[5] = $ProfilPegawai->name;
                $replacementsPegawai[6] = $IN_CASEID;
                    
            } elseif ($request->IN_INVSTS == '4') { // RUJUK KE KEMENTERIAN/AGENSI LAIN
                $mAgensi = DB::table('sys_min')->select('*')->where('MI_MINCD',$mIntegriti->IN_MAGNCD)->first();
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
                $replacementsPengadu[1] = !empty($mAgensi) ? $mAgensi->MI_DESC : '';
                $replacementsPengadu[2] = !empty($mAgensi) ? $mAgensi->MI_ADDR . '<br />' 
                    . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $mAgensi->MI_POSCD. ' '
                    . \App\Ref::GetDescr('18', $mAgensi->MI_DISTCD, 'ms') . '<br />' 
                    . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . \App\Ref::GetDescr('17', $mAgensi->MI_STATECD, 'ms') : '';
                $replacementsPengadu[3] = $IN_CASEID;
                $replacementsPengadu[4] = $mIntegriti->IN_SUMMARY;
                $replacementsPengadu[5] = $mIntegriti->IN_ANSWER;
                $replacementsPengadu[6] = $ProfilPegawaiTugasOleh->name;
                $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM.'<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1.'<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2.'<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
                    . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />' 
                    . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[8] = $mIntegriti->IN_NAME;
                $replacementsPengadu[9] = $mIntegriti->IN_ADDR != ''? $mIntegriti->IN_ADDR : '';
                $replacementsPengadu[10] = $mIntegriti->IN_POSCD != ''? $mIntegriti->IN_POSCD : '';
                $replacementsPengadu[11] = $IN_DISTCD;
                $replacementsPengadu[12] = $IN_STATECD;
                $replacementsPengadu[13] = !empty($mAgensi) ? $mAgensi->MI_TELNO : '';
                $replacementsPengadu[14] = !empty($mAgensi) ? $mAgensi->MI_EMAIL : '';
                
                $mAgensiData = Agensi::where(['MI_MINCD' => $mIntegriti->IN_MAGNCD, 'MI_STS' => '1'])->first();
                if ($mAgensiData->MI_EMAIL) {
                    $mPenyiasatan = \App\Aduan\Penyiasatan::where(['IN_CASEID' => $IN_CASEID])->first();
                    Mail::to($mAgensi->MI_EMAIL)->send(new RujukAgensiLain($mPenyiasatan));
                }
            } elseif ($request->IN_INVSTS == '5') { // Rujuk Ke Tribunal
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $replacementsPengadu[1] = $mIntegriti->IN_NAME;
                $replacementsPengadu[2] = $mIntegriti->IN_ADDR != ''? $mIntegriti->IN_ADDR : '';
                $replacementsPengadu[3] = $mIntegriti->IN_POSCD != ''? $mIntegriti->IN_POSCD : '';
                $replacementsPengadu[4] = $IN_DISTCD;
                $replacementsPengadu[5] = $IN_STATECD;
                $replacementsPengadu[6] = $IN_CASEID;
                $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->name;
                $replacementsPengadu[8] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
                    . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
            } 
            else if ($request->IN_INVSTS == '7') { //  MAKLUMAT TIDAK LENGKAP
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                // $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[3] = "#DAERAHPENGADU#";
                $patternsPengadu[4] = "#NEGERIPENGADU#";
                $patternsPengadu[5] = "#NOADUAN#";
                $patternsPengadu[6] = "#JAWAPANKEPADAPENGADU#";
                $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $replacementsPengadu[1] = $mIntegriti->IN_NAME;
                $replacementsPengadu[2] = $mIntegriti->IN_ADDR != ''? $mIntegriti->IN_ADDR : '';
                // $replacementsPengadu[3] = $mIntegriti->IN_POSCD != ''? $mIntegriti->IN_POSCD : '';
                $replacementsPengadu[3] = $IN_DISTCD;
                $replacementsPengadu[4] = $IN_STATECD;
                $replacementsPengadu[5] = $IN_CASEID;
                $replacementsPengadu[6] = $mIntegriti->IN_ANSWER;
                $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->name;
                $replacementsPengadu[8] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
                    . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
                if($mIntegriti->IN_EMAIL){
                    Mail::to($mIntegriti->IN_EMAIL)->send(new MaklumatAduanTaklengkap($mIntegriti));
                }
            } else if($request->IN_INVSTS == '8') { //  LUAR BIDANG KUASA
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $replacementsPengadu[1] = $mIntegriti->IN_NAME;
                $replacementsPengadu[2] = $mIntegriti->IN_ADDR != ''? $mIntegriti->IN_ADDR : '';
                $replacementsPengadu[3] = $mIntegriti->IN_POSCD != ''? $mIntegriti->IN_POSCD : '';
                $replacementsPengadu[4] = $IN_DISTCD;
                $replacementsPengadu[5] = $IN_STATECD;
                $replacementsPengadu[6] = $IN_CASEID;
                $replacementsPengadu[7] = $ProfilPegawaiTugasOleh->name;
                $replacementsPengadu[8] = $ProfilPegawaiTugasOleh->cawangan->BR_BRNNM.'<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->BR_POSCD . ' '
                    . $ProfilPegawaiTugasOleh->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawaiTugasOleh->cawangan->NegeriPegawai->descr;
                if($mIntegriti->IN_EMAIL){
                    Mail::to($mIntegriti->IN_EMAIL)->send(new AduanLuarBidang($mIntegriti));
                }
            } else if($request->IN_INVSTS == '6') { //  PERTANYAAN
                   
            }
            
            $date = date('Ymdhis');
            $userid = Auth::user()->id;
            
            if(!empty($SuratPengadu)) {
                $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
                $arr_repPengadu = array("#", "#");
                $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
                $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

                $filename = $userid . '_' . $IN_CASEID . '_' . $date . '_1.pdf';
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
                $SuratPegawaiFinal = str_replace($arr_repPegawai, "", $SuratPegawaiReplace); // SuratPegawai in HTML
                $SuratPegawaiHtml = PDF::loadHTML($SuratPegawaiFinal); // Generate PDF from HTML

                $filenameSuratPegawai = $userid . '_' . $IN_CASEID . '_' . $date . '_2.pdf';
                Storage::disk('letter')->put($filenameSuratPegawai, $SuratPegawaiHtml->output()); // Store PDF to storage

                $AttachSuratPegawai = new Attachment();
                $AttachSuratPegawai->doc_title = $SuratPegawai->title;
                $AttachSuratPegawai->file_name = $SuratPegawai->title;
                $AttachSuratPegawai->file_name_sys = $filenameSuratPegawai;
                if($AttachSuratPegawai->save()) {
                    $SuratPegawaiId = $AttachSuratPegawai->id;
                }
            }else{
                $SuratPegawaiId = NULL;
            } */
            
            $mCaseBefore = IntegritiPublicDetail::where(['ID_CASEID' => $IN_CASEID, 'ID_CURSTS' => '1'])->first();
            if (!empty($mCaseBefore)) {
                IntegritiPublicDetail::where(['ID_CASEID' => $IN_CASEID, 'ID_CURSTS' => '1'])->update(['ID_CURSTS' => '0']);
            }
            $mCaseDetail = new IntegritiPublicDetail();
            $mCaseDetail->ID_CASEID = $mIntegriti->IN_CASEID;
            if ($request->expectsJson()) {
                $mCaseDetail->ID_TYPE = 'S'; // EZSTAR
            } else {
                $mCaseDetail->ID_TYPE = 'D';
            }
            // $mCaseDetail->ID_DESC = $request->ID_DESC;
            $mCaseDetail->ID_INVSTS = $request->IN_INVSTS;
            // $mCaseDetail->ID_CASESTS = 2;
            $mCaseDetail->ID_CURSTS = 1;
            // $mCaseDetail->ID_DOCATTCHID_PUBLIC = $SuratPengaduId;
            // $mCaseDetail->ID_DOCATTCHID_ADMIN = $SuratPegawaiId;
            $mCaseDetail->ID_ACTFROM = Auth::User()->id;
            // $mCaseDetail->ID_ACTTO = $mIntegriti->IN_INVBY;
            if ($mCaseDetail->save()) {
                if ($request->expectsJson()) {
                    return response()->json(['data' => 'Aduan telah berjaya ditukar status']);
                }
                $request->session()->flash('success', 'Aduan telah berjaya ditukar status');
                return redirect('pengurusanintegriti');
            }
        }
    }

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
    public function edit($IN_CASEID)
    {
        //
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
        //
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
