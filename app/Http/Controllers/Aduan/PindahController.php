<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\PindahAduan;
use App\Aduan\PindahAduanDetail;
use App\Aduan\PindahAduanDoc;
use App\Agensi;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\Letter;
use App\Mail\PindahRujukAgensi;
use App\Models\Cases\CaseReasonTemplate;
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
use Yajra\DataTables\Facades\DataTables;

class PindahController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $mRefInvsts = Ref::where(['cat' => '292', 'status' => '1'])
            ->whereIn('code', ['0','1','2'])
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('descr', 'code');
        return view('aduan.pindah.index', compact('mRefInvsts'));
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        //
    }

    public function show(PindahAduan $pindahAduan) {
        //
    }

    public function edit($CASEID) {
        $mPindah = PindahAduan::where(['CA_CASEID' => $CASEID])->first();
        if (empty($mPindah)) {
            return redirect()->route('pindah.index')->with('warning', 'Maklumat Aduan <b>TIDAK DIJUMPAI</b>.');
        }
        $mUser = User::find(Auth::User()->id);
        $mRefState = Ref::where(['cat' => '17', 'status' => '1'])
            ->pluck('descr', 'code');
        $mCaseReasonTemplate = CaseReasonTemplate::where(['category' => 'AD51', 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code');
        $mPindahCaseDetail = PindahAduanDetail::
            where('CD_CASEID', $CASEID)
            ->where('CD_INVSTS', '0')
            ->orderBy('CD_CREDT', 'desc')
            ->first();
        if($mPindahCaseDetail){
            $countDuration = PindahAduan::getduration($mPindahCaseDetail->CD_CREDT, $CASEID);
        } else {
            $countDuration = PindahAduan::getduration($mPindah->CA_RCVDT, $CASEID);
        }
        return view('aduan.pindah.edit', compact('mPindah', 'mUser', 'mRefState', 'mCaseReasonTemplate', 'countDuration'));
    }

    public function update(Request $Request, $CASEID) {
        if (!$Request->expectsJson()) {
            // $this->validate($Request, [
            $v = Validator::make($Request->all(), [
                // 'CA_INVBY_NAME' => 'required_if:CA_INVSTS,0',
                'CA_INVSTS' => 'required',
                'CA_BR_STATECD' => 'required_if:CA_INVSTS,0',
                'CA_BRNCD' => 'required_if:CA_INVSTS,0',
                'CD_DESC' => 'required',
                'CA_MAGNCD' => 'required_if:CA_INVSTS,4',
                'CA_ANSWER' => 'required_if:CA_INVSTS,4,CA_INVSTS,5',
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
            ], [
                // 'CA_INVBY_NAME.required_if' => 'Ruangan Dipindahkan Kepada diperlukan.',
                'CA_INVSTS.required' => 'Ruangan Status diperlukan.',
                'CA_BR_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
                'CA_BRNCD.required_if' => 'Ruangan Cawangan diperlukan.',
                'CD_DESC.required' => 'Ruangan Saranan diperlukan.',
                'CA_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
                'CA_ANSWER.required_if' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CD_REASON.required' => 'Ruangan Alasan diperlukan.',
                'CD_REASON_DATE_FROM.required' => 'Ruangan Tarikh Dari diperlukan.',
                'CD_REASON_DATE_TO.required' => 'Ruangan Tarikh Hingga diperlukan.',
            ]);
            $v->sometimes('CD_REASON', 'required', function ($input) {
                return $input->CD_REASON_DURATION >= 4 && $input->CA_INVSTS != '';
            });
            $v->sometimes(['CD_REASON_DATE_FROM', 'CD_REASON_DATE_TO'], 'required', function ($input) {
                return $input->CD_REASON_DURATION >= 4 && $input->CD_REASON == 'P2' && $input->CA_INVSTS != '';
            });
            $v->validate();
        }

        $mPindah = PindahAduan::find($CASEID);
        $mPindahOldBrncd = $mPindah->CA_BRNCD;
        $mPindah->fill($Request->all());
        if($Request->CA_FILEREF){
            $mPindah->CA_FILEREF = $Request->CA_FILEREF;
        }
        
        if ($Request->expectsJson()) {
            $mPindah->CA_ASGDT = Carbon::now();
        }

        if ($Request->CA_INVSTS == '0') {
            $mPindah->CA_CASESTS = '1'; // Belum Diberi Penugasan
            $mPindah->CA_INVSTS = $Request->CA_INVSTS;
            
            $mPindah->CA_BRNCD = $Request->CA_BRNCD;
            $mPindah->CA_INVBY = null;
            $mPindah->CA_INVDT = null;

            if($Request->expectsJson()) {
                $mPindah->CA_INVDT = Carbon::now();
            }
//            $mPindah->CA_DEPTCD = '';
        } elseif ($Request->CA_INVSTS == '4') {
            $mPindah->CA_COMPLETEDT = Carbon::now();
            $mPindah->CA_CASESTS = '2'; // Telah Diberi Penugasan
            $mAgensi = Agensi::where(['MI_MINCD' => $mPindah->CA_MAGNCD, 'MI_STS' => '1'])->first();
            // if ($mAgensi->MI_EMAIL) {
            //     Mail::to($mAgensi->MI_EMAIL)->send(new PindahRujukAgensi($mPindah));
            // }
            $mPindah->CA_INVSTS = $Request->CA_INVSTS;
        } elseif ($Request->CA_INVSTS == '5') {
            $mPindah->CA_COMPLETEDT = Carbon::now();
            $mPindah->CA_CASESTS = '2'; // Telah Diberi Penugasan
            $mPindah->CA_INVSTS = $Request->CA_INVSTS;
        }
        // if($Request->CA_INVBY){
        //     $UserInvBy = User::find($Request->CA_INVBY);
        //     $mPindah->CA_BRNCD = $UserInvBy->brn_cd;
        // }
//        dd($mPindah);
//        dd($Request);
//        dd($UserInvBy);

        if ($mPindah->save()) {

            $SuratPengadu = Letter::where(['letter_code' => $Request->CA_INVSTS, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
            $SuratPegawai = Letter::where(['letter_code' => $Request->CA_INVSTS, 'letter_type' => '02', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu

            if ($SuratPengadu)
                $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
            if ($SuratPegawai)
                $ContentSuratPegawai = $SuratPegawai->header . $SuratPegawai->body . $SuratPegawai->footer;

            $ProfilPegawai = User::find($mPindah->CA_INVBY);
            $ProfilPegawaiPindahOleh = User::find($mPindah->CA_ASGBY);

            if(!empty($mPindah->CA_STATECD)){
                $StateNm = DB::table('sys_ref')->select('descr')->where(['cat' => '17', 'code' => $mPindah->CA_STATECD])->first();
                if (!$StateNm) {
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'334','code'=>$mPindah->CA_STATECD])->first();
                    if($StateNm){
                        $CA_STATECD = $StateNm->descr;
                    } else {
                        $CA_STATECD = $mPindah->CA_STATECD;
                    }
                } else {
                    $CA_STATECD = $StateNm->descr;
                }
            } else {
                $CA_STATECD = '';
            }
            if(!empty($mPindah->CA_DISTCD)){
                $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat' => '18', 'code' => $mPindah->CA_DISTCD])->first();
                if (!$DestrictNm){
                    $CA_DISTCD = $mPindah->CA_DISTCD;
                } else {
                    $CA_DISTCD = $DestrictNm->descr;
                }
            } else {
                $CA_DISTCD = '';
            }

            if ($mPindah->CA_INVSTS == '4') { // Rujuk Ke Kementerian/Agensi Lain
                $mAgensi = Agensi::where(['MI_MINCD' => $mPindah->CA_MAGNCD, 'MI_STS' => 1])->firstOrFail();

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
                $replacementsPengadu[3] = $mPindah->CA_CASEID;
                $replacementsPengadu[4] = $mPindah->CA_SUMMARY;
                $replacementsPengadu[5] = $mPindah->CA_ANSWER;
                $replacementsPengadu[6] = $ProfilPegawaiPindahOleh->name;
                $replacementsPengadu[7] = $ProfilPegawaiPindahOleh->cawangan->BR_BRNNM . '<br />' 
                    . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR1 . '<br />' 
                    . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR2 . '<br />' 
                    . $ProfilPegawaiPindahOleh->cawangan->BR_POSCD . ' '
                    . $ProfilPegawaiPindahOleh->cawangan->DaerahPegawai->descr . '<br />' 
                    . $ProfilPegawaiPindahOleh->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[8] = $mPindah->CA_NAME;
                $replacementsPengadu[9] = $mPindah->CA_ADDR != '' ? $mPindah->CA_ADDR : '';
                $replacementsPengadu[10] = $mPindah->CA_POSCD != '' ? $mPindah->CA_POSCD : '';
                $replacementsPengadu[11] = $CA_DISTCD;
                $replacementsPengadu[12] = $CA_STATECD;
                $replacementsPengadu[13] = !empty($mAgensi) ? $mAgensi->MI_TELNO : '';
                $replacementsPengadu[14] = !empty($mAgensi) ? $mAgensi->MI_EMAIL : '';
                $replacementsPengadu[15] = Carbon::now()->format('d/m/Y');
                if ($mAgensi->MI_EMAIL) {
                    try {
                        if (App::environment(['production'])) {
                            Mail::to($mAgensi->MI_EMAIL)
                                ->cc($ProfilPegawaiPindahOleh->email)
                                ->send(new PindahRujukAgensi($mPindah));
                        } else {
                            Mail::to($ProfilPegawaiPindahOleh->email)
                                ->cc($ProfilPegawaiPindahOleh->email)
                                ->send(new PindahRujukAgensi($mPindah));
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            } else if ($mPindah->CA_INVSTS == '5') { // Rujuk Ke Tribunal
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#EMAILPEGAWAIPENYIASAT#";
                $patternsPengadu[8] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[9] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $patternsPengadu[10] = "#TARIKHRUJUKTRIBUNAL#";
                $replacementsPengadu[1] = $mPindah->CA_NAME;
                $replacementsPengadu[2] = $mPindah->CA_ADDR != '' ? $mPindah->CA_ADDR : '';
                $replacementsPengadu[3] = $mPindah->CA_POSCD != '' ? $mPindah->CA_POSCD : '';
                $replacementsPengadu[4] = $CA_DISTCD;
                $replacementsPengadu[5] = $CA_STATECD;
                $replacementsPengadu[6] = $mPindah->CA_CASEID;
                $replacementsPengadu[7] = $ProfilPegawaiPindahOleh->email;
                $replacementsPengadu[8] = $ProfilPegawaiPindahOleh->name;
                $replacementsPengadu[9] = $ProfilPegawaiPindahOleh->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawaiPindahOleh->cawangan->BR_POSCD . ' '
                    . $ProfilPegawaiPindahOleh->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawaiPindahOleh->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[10] = Carbon::now()->format('d/m/Y');
            } else if ($mPindah->CA_INVSTS == '0') { // Pindah ke Negeri/Bahagian/Cawangan Lain
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#EMAILPEGAWAIPENYIASAT#";
                $patternsPengadu[8] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[9] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $patternsPengadu[10] = "#NAMACAWANGANPEGAWAI#";
                $patternsPengadu[11] = "#NOTELCAWANGANPEGAWAI#";
                $patternsPengadu[12] = "#TARIKHPINDAH#";
                $replacementsPengadu[1] = $mPindah->CA_NAME;
                $replacementsPengadu[2] = $mPindah->CA_ADDR != '' ? $mPindah->CA_ADDR : '';
                $replacementsPengadu[3] = $mPindah->CA_POSCD != '' ? $mPindah->CA_POSCD : '';
                $replacementsPengadu[4] = $CA_DISTCD;
                $replacementsPengadu[5] = $CA_STATECD;
                $replacementsPengadu[6] = $mPindah->CA_CASEID;
                $replacementsPengadu[7] = $ProfilPegawaiPindahOleh->email;
                $replacementsPengadu[8] = $ProfilPegawaiPindahOleh->name;
                $replacementsPengadu[9] = $ProfilPegawaiPindahOleh->cawangan->BR_BRNNM . '<br />' 
                    . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR1 . '<br />' 
                    . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR2 . '<br />' 
                    . $ProfilPegawaiPindahOleh->cawangan->BR_POSCD . ' '. $ProfilPegawaiPindahOleh->cawangan->DaerahPegawai->descr . '<br />' 
                    . $ProfilPegawaiPindahOleh->cawangan->NegeriPegawai->descr;
//                $replacementsPengadu[10] = $ProfilPegawaiPindahOleh->cawangan ? 
//                    $ProfilPegawaiPindahOleh->cawangan->BR_BRNNM . ', '. $ProfilPegawaiPindahOleh->cawangan->NegeriPegawai->descr 
//                    : '';
                // $replacementsPengadu[10] = $ProfilPegawai->cawangan ?
                //     $ProfilPegawai->cawangan->BR_BRNNM . ', '. $ProfilPegawai->cawangan->NegeriPegawai->descr
                //     : '';
                $replacementsPengadu[10] =
                    $mPindah->namacawangan
                    ? $mPindah->namacawangan->BR_BRNNM
                    : '';
//                $replacementsPengadu[11] = $ProfilPegawaiPindahOleh->cawangan ? $ProfilPegawaiPindahOleh->cawangan->BR_TELNO : '';
                $replacementsPengadu[11] =
                    $mPindah->namacawangan
                    ? $mPindah->namacawangan->BR_TELNO
                    : '';
                $replacementsPengadu[12] = Carbon::now()->format('d/m/Y h:i A');
            }

            $date = date('YmdHis');
            $userid = Auth::user()->id;

            // Generate Surat Kepada Pengadu
            if (!empty($SuratPengadu)) {
                $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
                $arr_repPengadu = array("#", "#");
                $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
                $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

                $filename = $userid . '_' . $mPindah->CA_CASEID . '_' . $date . '.pdf';
                Storage::disk('letter')->put($filename, $SuratPengaduHtml->output()); // Store PDF to storage

                $AttachSuratPengadu = new Attachment();
                $AttachSuratPengadu->doc_title = $SuratPengadu->title;
                $AttachSuratPengadu->file_name = $SuratPengadu->title;
                $AttachSuratPengadu->file_name_sys = $filename;
                if ($AttachSuratPengadu->save()) {
                    $SuratPengaduId = $AttachSuratPengadu->id;
                }
            } else {
                $SuratPengaduId = NULL;
            }

            // Generate Surat Kepada Pegawai
            if (!empty($SuratPegawai)) {
                $SuratPegawaiId = NULL;
            } else {
                $SuratPegawaiId = NULL;
            }

            PindahAduanDetail::where(['CD_CASEID' => $mPindah->CA_CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
            $mPindahDetail = new PindahAduanDetail();
            $mPindahDetail->CD_CASEID = $mPindah->CA_CASEID;
            if ($Request->expectsJson()) {
                $mPindahDetail->CD_TYPE = 'S'; // EZSTAR
            } else {
                $mPindahDetail->CD_TYPE = 'D';
            }
            $mPindahDetail->CD_DESC = $Request->CD_DESC;
            $mPindahDetail->CD_INVSTS = $mPindah->CA_INVSTS;
            $mPindahDetail->CD_CASESTS = $mPindah->CA_CASESTS;
            $mPindahDetail->CD_CURSTS = 1;
            $mPindahDetail->CD_DOCATTCHID_PUBLIC = $SuratPengaduId;
            $mPindahDetail->CD_DOCATTCHID_ADMIN = $SuratPegawaiId;
            $mPindahDetail->CD_ACTFROM = Auth::User()->id;
            // $mPindahDetail->CD_ACTTO = $Request->CA_INVBY;
            $mPindahDetail->CD_BRNCD_FROM = $mPindahOldBrncd;
            if($Request->CA_INVSTS == '0'){
                $mPindahDetail->CD_BRNCD_TO = $Request->CA_BRNCD;
            }
            $mPindahDetail->CD_REASON = $Request->CD_REASON;
            $mPindahDetail->CD_REASON_DURATION = $Request->CD_REASON_DURATION;
            if($Request->CD_REASON == 'P2'){
                $mPindahDetail->CD_REASON_DATE_FROM =
                    Carbon::parse($Request->CD_REASON_DATE_FROM);
                $mPindahDetail->CD_REASON_DATE_TO =
                    Carbon::parse($Request->CD_REASON_DATE_TO);
            }
            if ($mPindahDetail->save()) {
                if ($Request->expectsJson()) {
                    return response()->json(['data' => 'Aduan telah berjaya dipindah']);
                }
                return redirect()->route('pindah.index')->with('success', 'Aduan telah <b>BERJAYA</b> dipindah');
            }
        }
    }

    public function destroy(PindahAduan $pindahAduan) {
        //
    }

    public function getuserdetail($id) {
        $userDetail = DB::table('sys_users')
                ->select('id', 'name', 'brn_cd')
                ->where('id', $id)
                ->first();
        return json_encode($userDetail);
    }

    public function getdatatablecase(Request $request) {
//        $mPindah = PindahAduan::where(['CA_INVSTS' => 1, 'CA_BRNCD' => Auth::user()->brn_cd])->orderBy('CA_RCVDT', 'DESC');
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mPindah = PindahAduan::where(['CA_BRNCD' => Auth::user()->brn_cd])
            ->whereIn('CA_INVSTS', [1,0,2])
            // ->orderBy('CA_RCVDT', 'DESC')
            ;
        
        if ($request->mobile) {
            return response()->json(['data' => $mPindah->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = Datatables::of($mPindah)
                ->addIndexColumn()
                ->addColumn('check', '<input type="checkbox" class="i-checks" name="CASEID[]" value="{{ $CA_CASEID }}" onclick="anyCheck()">')
                ->editColumn('CA_SUMMARY', function(PindahAduan $pindahAduan) {
                    if ($pindahAduan->CA_SUMMARY != '')
                        return implode(' ', array_slice(explode(' ', $pindahAduan->CA_SUMMARY), 0, 7)) . ' ...';
                    else
                        return '';
//                return '<div style="max-height:80px; overflow:auto">'.$pindahAduan->CA_SUMMARY.'</div>';
                })
                ->editColumn('CA_INVSTS', function(PindahAduan $pindahAduan) {
                    if ($pindahAduan->CA_INVSTS != '')
                        return $pindahAduan->statusAduan->descr;
                    else
                        return '';
                })
                ->editColumn('CA_CASEID', function (PindahAduan $penugasan) {
                     return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
                })
                ->editColumn('CA_RCVDT', function(PindahAduan $pindahAduan) {
                    return $pindahAduan->CA_RCVDT ? with(new Carbon($pindahAduan->CA_RCVDT))->format('d-m-Y h:i A') : '';
                })
                ->addColumn('tempoh', function(PindahAduan $pindahAduan) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
                    $mPindahCaseDetail = PindahAduanDetail::
                        where('CD_CASEID', $pindahAduan->CA_CASEID)
                        ->where('CD_INVSTS', '0')
                        ->orderBy('CD_CREDT', 'desc')
                        ->first();
                    if($mPindahCaseDetail){
                        $totalDuration = $pindahAduan->getduration($mPindahCaseDetail->CD_CREDT, $pindahAduan->CA_CASEID);
                    } else {
                        $totalDuration = $pindahAduan->getduration($pindahAduan->CA_RCVDT, $pindahAduan->CA_CASEID);
                    }
                    if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                            return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                        else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                            return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
                        else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                            return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                        else if ($totalDuration > $TempohKetiga->code)
                            return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                })
                ->addColumn('action', '
                        <a href="{{ url("pindah/{$CA_CASEID}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                        <i class="fa fa-pencil"></i></a>')
                        
                ->rawColumns(['check', 'CA_CASEID', 'CA_SUMMARY', 'tempoh', 'action'])
                ->filter(function ($query) use ($request) {
                    if ($request->has('CA_CASEID')) {
                        $query->where('CA_CASEID', 'like', "%{$request->get('CA_CASEID')}%");
                    }
                    if ($request->has('CA_SUMMARY')) {
                        $query->where('CA_SUMMARY', 'like', "%{$request->get('CA_SUMMARY')}%");
                    }
                    if ($request->has('CA_AGAINSTNM')) {
                        $query->where('CA_AGAINSTNM', 'like', "%{$request->get('CA_AGAINSTNM')}%");
                    }
                    if ($request->has('CA_RCVDT')) {
                        $query->whereDate('CA_RCVDT', Carbon::parse($request->get('CA_RCVDT'))->format('Y-m-d'));
                    }
                    if ($request->has('CA_INVSTS')) {
                        $query->where('CA_INVSTS', $request->get('CA_INVSTS'));
                    }
                });
//        if ($CA_CASEID = $datatables->request->get('CA_CASEID')) {
//            $datatables->where('CA_CASEID', 'LIKE', "%$CA_CASEID%");
//        }
//        if ($CA_SUMMARY = $datatables->request->get('CA_SUMMARY')) {
//            $datatables->where('CA_SUMMARY', 'LIKE', "%$CA_SUMMARY%");
//        }
//        if ($CA_AGAINSTNM = $datatables->request->get('CA_AGAINSTNM')) {
//            $datatables->where('CA_AGAINSTNM', 'LIKE', "%$CA_AGAINSTNM%");
//        }
//        if ($CA_RCVDT = $datatables->request->get('CA_RCVDT')) {
//            $datatables->whereDate('CA_RCVDT', Carbon::parse($CA_RCVDT)->format('Y-m-d'));
//        }
//        if ($CA_INVSTS = $datatables->request->get('CA_INVSTS')) {
//            $datatables->where('CA_INVSTS', '=', $CA_INVSTS);
//        }
        return $datatables->make(true);
    }

    public function GetDataTableUser(Request $request) {
//        $mUser = User::with('role')
//                ->select('id','username','name', 'state_cd','brn_cd',DB::raw('(select count(CA_CASEID) from case_info where CA_INVBY = sys_users.id AND CA_INVSTS = 2) as count_case'))
//                ->where(['user_cat' => '1', 'status' => '1']);
        $mUser = User::join('sys_user_access as b', 'b.user_id', '=', 'sys_users.id')
                ->join('sys_role_mapping as c', 'c.role_code', '=', 'b.role_code')
                ->join('sys_menu as d', 'd.id', '=', 'c.menu_id')
                ->join('sys_menu as e', 'e.menu_parent_id', '=', 'd.id')
                ->leftJoin('sys_ref as f', 'f.code', '=', 'b.role_code')
//                ->distinct()
                ->select(DB::raw('DISTINCT(sys_users.id)'), 'sys_users.username', 'sys_users.name', 'sys_users.state_cd', 'sys_users.brn_cd', 'b.role_code', 'f.descr')
                ->where([
            ['e.menu_txt', 'LIKE', 'Penugasan'],
            ['sys_users.user_cat', '=', 1],
            ['sys_users.status', '=', 1],
            ['f.cat', '=', 152],
            ['f.status', '=', 1]
        ])
        ->whereIn('f.code', ['310','340','320','120','110','210','220']);
        
        if ($request->mobile) {
            return response()->json(['data' => $mUser->get()->toArray()]);
        }
        $datatables = Datatables::of($mUser)
                ->addIndexColumn()
                ->editColumn('state_cd', function(User $user) {
                    if ($user->state_cd != '')
                        return $user->Negeri->descr;
                    else
                        return '';
                })
                ->editColumn('brn_cd', function(User $user) {
                    if ($user->brn_cd != '')
                        return $user->Cawangan->BR_BRNNM;
                    else
                        return '';
                })
//                ->editColumn('tugas', function(User $user) {
//                    return Penugasan::GetCountTugas($user->id);
//                })
                ->editColumn('role_code', function (User $user) {
                    if($user->role_code != '')
                        return User::ShowRoleName($user->role->role_code);
                    else
                        return '';
                })
                ->addColumn('action', function (User $User) {
                    return view('aduan.pindah.user_action_btn', compact('User'))->render();
////                    return '<a class="btn btn-xs btn-primary" onclick="myFunction( '.$user->id.','.$user->name.' )"><i class="fa fa-arrow-down"></i></a>';
                })
                ->filter(function ($query) use ($request) {
            if ($request->has('icnew')) {
                $query->where('sys_users.username', 'like', "%{$request->get('icnew')}%");
            }
            if ($request->has('name')) {
                $query->where('sys_users.name', 'like', "%{$request->get('name')}%");
            }
            if ($request->has('state_cd')) {
                $query->where('sys_users.state_cd', $request->get('state_cd'));
            }
            if ($request->has('brn_cd')) {
                $query->where('sys_users.brn_cd', $request->get('brn_cd'));
            }
//                    if ($request->has('role_cd')) {
//                        $query->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')->where('sys_user_access.role_code', $request->get('role_cd'));
//                    }
        })
        ;
        return $datatables->make(true);
    }

    public function getdatatableattachment(Request $request, $CASEID) {
        $mPindahAduanDoc = PindahAduanDoc::where('CC_CASEID', $CASEID)
            ->where('CC_IMG_CAT', '1')
        ;
        
        if ($request->mobile) {
            return response()->json(['data' => $mPindahAduanDoc->get()->toArray()]);
        }
        $datatables = Datatables::of($mPindahAduanDoc)
                ->addIndexColumn()
//                ->editColumn('id', function(PindahAduanDoc $pindahAduanDoc) {
//                    if ($pindahAduanDoc->CC_DOCATTCHID != '')
//                        return $pindahAduanDoc->attachment->id;
//                    else
//                        return '';
//                })
//                ->editColumn('doc_title', function(PindahAduanDoc $pindahAduanDoc) {
//                    if ($pindahAduanDoc->CC_DOCATTCHID != '')
//                        return $pindahAduanDoc->attachment->doc_title;
//                    else
//                        return '';
//                })
//                ->editColumn('file_name_sys', function(PindahAduanDoc $pindahAduanDoc) {
//                    if ($pindahAduanDoc->CC_DOCATTCHID != '')
//                        return '<a href=' . Storage::disk('local')->url($pindahAduanDoc->attachment->file_name_sys) . ' target="_blank">' . $pindahAduanDoc->attachment->file_name . '</a>';
//                    else
//                        return '';
//                })
                ->editColumn('CC_IMG_NAME', function(PindahAduanDoc $pindahAduanDoc) {
                    if($pindahAduanDoc->CC_IMG_NAME != '')
                        return '<a href='.Storage::disk('bahanpath')->url($pindahAduanDoc->CC_PATH.$pindahAduanDoc->CC_IMG).' target="_blank">'.$pindahAduanDoc->CC_IMG_NAME.'</a>';
                    else
                        return '';
                })
//                ->editColumn('updated_at', function(PindahAduanDoc $pindahAduanDoc) {
//                    if ($pindahAduanDoc->CC_DOCATTCHID != '')
//                        return $pindahAduanDoc->attachment->updated_at ? with(new Carbon($pindahAduanDoc->attachment->updated_at))->format('d-m-Y h:i A') : '';
//                    else
//                        return '';
//                })
                ->editColumn('updated_at', function(PindahAduanDoc $pindahAduanDoc) {
                    if($pindahAduanDoc->updated_at != '')
                        return $pindahAduanDoc->updated_at ? with(new Carbon($pindahAduanDoc->updated_at))->format('d-m-Y h:i A') : '';
                    else
                        return '';
                })
                ->rawColumns(['CC_IMG_NAME'])
        ;
        return $datatables->make(true);
    }

    public function getdatatablemergecase(Request $request, $CASEID) {
        $mPindahAduan = PindahAduan::where('CA_MERGE', $CASEID)->orderBy('CA_CREDT', 'desc');
        
        if ($request->mobile) {
            return response()->json(['data' => $mPindahAduan->get()->toArray()]);
        }
        $datatables = Datatables::of($mPindahAduan)
                ->addIndexColumn()
                ->editColumn('CA_SUMMARY', function(PindahAduan $pindahAduan) {
                    if ($pindahAduan->CA_SUMMARY != '')
                        return implode(' ', array_slice(explode(' ', $pindahAduan->CA_SUMMARY), 0, 7)) . ' ...';
                    else
                        return '';
                })
                ->editColumn('CA_INVSTS', function(PindahAduan $pindahAduan) {
                    if ($pindahAduan->CA_INVSTS != '')
                        return $pindahAduan->statusAduan->descr;
                    else
                        return '';
                })
                ->editColumn('CA_RCVDT', function(PindahAduan $pindahAduan) {
            return $pindahAduan->CA_RCVDT ? with(new Carbon($pindahAduan->CA_RCVDT))->format('d-m-Y h:i A') : '';
        })
        ;
        return $datatables->make(true);
    }

    public function getdatatabletransaction(Request $request, $CASEID) {
        $mPindahAduanDetail = PindahAduanDetail::where('CD_CASEID', $CASEID);
        
        if ($request->mobile) {
            return response()->json(['data' => $mPindahAduanDetail->get()->toArray()]);
        }
        $datatables = Datatables::of($mPindahAduanDetail)
                ->addIndexColumn()
                ->editColumn('CD_INVSTS', function(PindahAduanDetail $pindahAduanDetail) {
                    if ($pindahAduanDetail->CD_INVSTS != '')
                        return $pindahAduanDetail->statusaduan->descr;
                    else
                        return '';
                })
                ->editColumn('CD_ACTFROM', function(PindahAduanDetail $pindahAduanDetail) {
                    if ($pindahAduanDetail->CD_ACTFROM != '') {
                        if ($pindahAduanDetail->actfrom) {
                            return $pindahAduanDetail->actfrom->name;
                        } else {
                            return $pindahAduanDetail->CD_ACTFROM;
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('CD_ACTTO', function(PindahAduanDetail $pindahAduanDetail) {
                    if ($pindahAduanDetail->CD_ACTTO != '') {
                        if ($pindahAduanDetail->actto) {
                            return $pindahAduanDetail->actto->name;
                        } else {
                            return $pindahAduanDetail->CD_ACTTO;
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('CD_DESC', function(PindahAduanDetail $pindahAduanDetail) {
                    if ($pindahAduanDetail->CD_CASEID != '')
                        return $pindahAduanDetail->CD_DESC;
                    else
                        return '';
                })
                ->editColumn('CD_CREDT', function (PindahAduanDetail $pindahAduanDetail) {
                    return $pindahAduanDetail->CD_CREDT ? with(new Carbon($pindahAduanDetail->CD_CREDT))->format('d-m-Y h:i A') : '';
                })
                ->editColumn('CD_DOCATTCHID_ADMIN', function(PindahAduanDetail $pindahAduanDetail) {
                    if ($pindahAduanDetail->CD_DOCATTCHID_ADMIN != '')
                        return '<a href=' . Storage::disk('letter')->url($pindahAduanDetail->suratadmin->file_name_sys) . ' target="_blank">' . $pindahAduanDetail->suratadmin->file_name . '</a>';
                    else
                        return '';
                })
//            ->rawColumns(['CD_DOCATTCHID_ADMIN'])
                ->editColumn('CD_DOCATTCHID_PUBLIC', function(PindahAduanDetail $pindahAduanDetail) {
                    if ($pindahAduanDetail->CD_DOCATTCHID_PUBLIC != '')
                        return '<a href=' . Storage::disk('letter')->url($pindahAduanDetail->suratpublic->file_name_sys) . ' target="_blank">' . $pindahAduanDetail->suratpublic->file_name . '</a>';
                    else
                        return '';
                })
                ->rawColumns(['CD_DOCATTCHID_ADMIN', 'CD_DOCATTCHID_PUBLIC'])
        ;
        return $datatables->make(true);
    }

    public function ShowSummary($CASEID) {
        $model = PindahAduan::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PindahAduanDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PindahAduanDoc::where(['CC_CASEID' => $CASEID])->get();
        return view('aduan.pindah.show_summary_modal', compact('model', 'trnsksi', 'img'));
    }

    public function PrintSummary($CASEID) {
        $model = PindahAduan::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PindahAduanDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PindahAduanDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('aduan.pindah.show_summary_modal', compact('model', 'trnsksi', 'img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }

    public function EditKelompok(request $Request) {
        $arrCASEID = $Request->CASEID;
        foreach($arrCASEID as $caseid) {
            $caseDetail[$caseid] = PindahAduanDetail::
                where('CD_CASEID', $caseid)
                ->where('CD_INVSTS', '0')
                ->orderBy('CD_CREDT', 'desc')
                ->first();
            $caseInfo[$caseid] = PindahAduan::where(['CA_CASEID' => $caseid])->first();
            if($caseDetail[$caseid]) {
                $countDuration[$caseid] = PindahAduan::GetDuration($caseDetail[$caseid]->CD_CREDT, $caseid);
            } else {
                $countDuration[$caseid] = PindahAduan::GetDuration($caseInfo[$caseid]->CA_RCVDT, $caseid);
            }
        }
        $mUser = User::find(Auth::User()->id);
        $mRefState = Ref::where(['cat' => '17', 'status' => '1'])
            ->pluck('descr', 'code');
        $caseReasonTemplates = CaseReasonTemplate::where(['category' => 'AD51', 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code');
        return view('aduan.pindah.editkelompok', compact('arrCASEID', 'mUser', 'mRefState', 'caseReasonTemplates', 'countDuration'));
    }

    public function SubmitKelompok(request $Request) {
        if (!$Request->expectsJson()) {
            // $this->validate($Request, [
            $v = Validator::make($Request->all(), [
        //            'CA_INVBY' => 'required',
                // 'CA_INVBY_NAME' => 'required_if:CA_INVSTS,0',
                'CA_INVSTS' => 'required',
                'CA_BR_STATECD' => 'required_if:CA_INVSTS,0',
                'CA_BRNCD' => 'required_if:CA_INVSTS,0',
                'CD_DESC' => 'required',
                'CA_MAGNCD' => 'required_if:CA_INVSTS,4',
                'CA_ANSWER' => 'required_if:CA_INVSTS,4,CA_INVSTS,5',
                    ], [
        //            'CA_INVBY.required' => 'Ruangan Dipindahkan Kepada diperlukan.',
                // 'CA_INVBY_NAME.required_if' => 'Ruangan Dipindahkan Kepada diperlukan.',
                'CA_INVSTS.required' => 'Ruangan Status diperlukan.',
                'CA_BR_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
                'CA_BRNCD.required_if' => 'Ruangan Cawangan diperlukan.',
                'CD_DESC.required' => 'Ruangan Saranan diperlukan.',
                'CA_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
                'CA_ANSWER.required_if' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
                'CD_REASON.*.required' => 'Ruangan Alasan diperlukan.',
                'CD_REASON_DATE_FROM.*.required' => 'Ruangan Tarikh Dari diperlukan.',
                'CD_REASON_DATE_TO.*.required' => 'Ruangan Tarikh Hingga diperlukan.',
            ]);
            $arrayReasonDuration = $Request->CD_REASON_DURATION;
            foreach($arrayReasonDuration as $key => $value) {
                $v->sometimes("CD_REASON.$key", 'required', function ($input) use ($key) {
                    return $input->CD_REASON_DURATION[$key] >= 4 && $input->CA_INVSTS != '';
                });
                $v->sometimes(["CD_REASON_DATE_FROM.$key", "CD_REASON_DATE_TO.$key"], 'required', function ($input) use ($key) {
                    return $input->CD_REASON_DURATION[$key] >= 4 && $input->CD_REASON[$key] == 'P2' && $input->CA_INVSTS != '';
                });
            }
            $v->validate();
        }

        $arrCASEID = $Request->CA_FILEREF;
        foreach ($arrCASEID as $CASEID => $Value) {
            $mPindah = PindahAduan::find($CASEID);
            $mPindahOldBrncd = $mPindah->CA_BRNCD;
            $mPindah->fill($Request->all());
            if($Value){
                $mPindah->CA_FILEREF = $Value;
            }

            if ($Request->CA_INVSTS == '0') {
                $mPindah->CA_CASESTS = '1'; // Belum Diberi Penugasan
                $mPindah->CA_INVSTS = $Request->CA_INVSTS;

                $mPindah->CA_BRNCD = $Request->CA_BRNCD;
                $mPindah->CA_INVBY = null;
                $mPindah->CA_INVDT = null;

                if($Request->expectsJson()) {
                    $mPindah->CA_INVDT = Carbon::now();
                }
                //            $mPindah->CA_DEPTCD = '';
            } elseif ($Request->CA_INVSTS == '4') {
                $mPindah->CA_COMPLETEDT = Carbon::now();
                $mPindah->CA_CASESTS = '2'; // Telah Diberi Penugasan
                $mPindah->CA_INVSTS = $Request->CA_INVSTS;
                $mAgensi = Agensi::where(['MI_MINCD' => $mPindah->CA_MAGNCD, 'MI_STS' => '1'])->first();
                // if ($mAgensi->MI_EMAIL) {
                //     Mail::to($mAgensi->MI_EMAIL)->send(new PindahRujukAgensi($mPindah));
                // }
            } elseif ($Request->CA_INVSTS == '5') {
                $mPindah->CA_COMPLETEDT = Carbon::now();
                $mPindah->CA_CASESTS = '2'; // Telah Diberi Penugasan
                $mPindah->CA_INVSTS = $Request->CA_INVSTS;
            }
            // if($Request->CA_INVBY){
            // $UserInvBy = User::find($Request->CA_INVBY);
            // $mPindah->CA_BRNCD = $UserInvBy->brn_cd;
            //        dd($mPindah);
            //        dd($Request);
            //        dd($UserInvBy);
            // }
            if ($mPindah->save()) {
                $SuratPengadu = Letter::where(['letter_code' => $Request->CA_INVSTS, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
                $SuratPegawai = Letter::where(['letter_code' => $Request->CA_INVSTS, 'letter_type' => '02', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu

                if ($SuratPengadu)
                    $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
                if ($SuratPegawai)
                    $ContentSuratPegawai = $SuratPegawai->header . $SuratPegawai->body . $SuratPegawai->footer;

                $ProfilPegawai = User::find($mPindah->CA_INVBY);
                $ProfilPegawaiPindahOleh = User::find($mPindah->CA_ASGBY);

//                if ($mPindah->CA_STATECD != '') {
                if(!empty($mPindah->CA_STATECD)){
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat' => '17', 'code' => $mPindah->CA_STATECD])->first();
                    if (!$StateNm) {
                        $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'334','code'=>$mPindah->CA_STATECD])->first();
                        if($StateNm){
                            $CA_STATECD = $StateNm->descr;
                        } else {
                            $CA_STATECD = $mPindah->CA_STATECD;
                        }
                    } else {
                        $CA_STATECD = $StateNm->descr;
                    }
                } else {
                    $CA_STATECD = '';
                }
//                if ($mPindah->CA_DISTCD != '') {
                if(!empty($mPindah->CA_DISTCD)){
                    $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat' => '18', 'code' => $mPindah->CA_DISTCD])->first();
                    if (!$DestrictNm){
                        $CA_DISTCD = $mPindah->CA_DISTCD;
                    } else {
                        $CA_DISTCD = $DestrictNm->descr;
                    }
                } else {
                    $CA_DISTCD = '';
                }

                if ($mPindah->CA_INVSTS == '4') { // Rujuk Ke Kementerian/Agensi Lain
                    $mAgensi = Agensi::where(['MI_MINCD' => $mPindah->CA_MAGNCD])->firstOrFail();

                    $patternsPengadu[1] = "#NAMAAGENSI#";
                    $patternsPengadu[2] = "#ALAMATAGENSI#";
                    $patternsPengadu[3] = "#NOADUAN#";
                    $patternsPengadu[4] = "#KETERANGANADUAN#";
                    $patternsPengadu[5] = "#NAMAPEGAWAIPENYIASAT#";
                    $patternsPengadu[6] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                    $patternsPengadu[7] = "#NAMAPENGADU#";
                    $patternsPengadu[8] = "#ALAMATPENGADU#";
                    $patternsPengadu[9] = "#POSKODPENGADU#";
                    $patternsPengadu[10] = "#DAERAHPENGADU#";
                    $patternsPengadu[11] = "#NEGERIPENGADU#";
                    $patternsPengadu[12] = "#NOTELEFONAGENSI#";
                    $patternsPengadu[13] = "#EMELAGENSI#";
                    $patternsPengadu[14] = "#TARIKHRUJUKAGENSI#";

                    $replacementsPengadu[1] = $mAgensi->MI_DESC;
                    $replacementsPengadu[2] = !empty($mAgensi) ? $mAgensi->MI_ADDR . '<br />'
                    . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $mAgensi->MI_POSCD. ' '
                    . \App\Ref::GetDescr('18', $mAgensi->MI_DISTCD, 'ms') . '<br />'
                    . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . \App\Ref::GetDescr('17', $mAgensi->MI_STATECD, 'ms') : '';
                    $replacementsPengadu[3] = $mPindah->CA_CASEID;
                    $replacementsPengadu[4] = $mPindah->CA_SUMMARY;
                    $replacementsPengadu[5] = $ProfilPegawaiPindahOleh->name;
                    $replacementsPengadu[6] = $ProfilPegawaiPindahOleh->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawaiPindahOleh->cawangan->BR_POSCD . ' '
                    . $ProfilPegawaiPindahOleh->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawaiPindahOleh->cawangan->NegeriPegawai->descr;
                    $replacementsPengadu[7] = $mPindah->CA_NAME;
                    $replacementsPengadu[8] = $mPindah->CA_ADDR != '' ? $mPindah->CA_ADDR : '';
                    $replacementsPengadu[9] = $mPindah->CA_POSCD != '' ? $mPindah->CA_POSCD : '';
                    $replacementsPengadu[10] = $CA_DISTCD;
                    $replacementsPengadu[11] = $CA_STATECD;
                    $replacementsPengadu[12] = !empty($mAgensi) ? $mAgensi->MI_TELNO : '';
                    $replacementsPengadu[13] = !empty($mAgensi) ? $mAgensi->MI_EMAIL : '';
                    $replacementsPengadu[14] = Carbon::now()->format('d/m/Y');
                    if ($mAgensi->MI_EMAIL) {
                        try {
                            if (App::environment(['production'])) {
                                Mail::to($mAgensi->MI_EMAIL)
                                    ->cc($ProfilPegawaiPindahOleh->email)
                                    ->send(new PindahRujukAgensi($mPindah));
                            } else {
                                Mail::to($ProfilPegawaiPindahOleh->email)
                                    ->cc($ProfilPegawaiPindahOleh->email)
                                    ->send(new PindahRujukAgensi($mPindah));
                            }
                        } catch (Exception $e) {
                            
                        }
                    }
                } else if ($mPindah->CA_INVSTS == '5') { // Rujuk Ke Tribunal
                    $patternsPengadu[1] = "#NAMAPENGADU#";
                    $patternsPengadu[2] = "#ALAMATPENGADU#";
                    $patternsPengadu[3] = "#POSKODPENGADU#";
                    $patternsPengadu[4] = "#DAERAHPENGADU#";
                    $patternsPengadu[5] = "#NEGERIPENGADU#";
                    $patternsPengadu[6] = "#NOADUAN#";
                    $patternsPengadu[7] = "#EMAILPEGAWAIPENYIASAT#";
                    $patternsPengadu[8] = "#NAMAPEGAWAIPENYIASAT#";
                    $patternsPengadu[9] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                    $patternsPengadu[10] = "#TARIKHRUJUKTRIBUNAL#";
                    $replacementsPengadu[1] = $mPindah->CA_NAME;
                    $replacementsPengadu[2] = $mPindah->CA_ADDR != '' ? $mPindah->CA_ADDR : '';
                    $replacementsPengadu[3] = $mPindah->CA_POSCD != '' ? $mPindah->CA_POSCD : '';
                    $replacementsPengadu[4] = $CA_DISTCD;
                    $replacementsPengadu[5] = $CA_STATECD;
                    $replacementsPengadu[6] = $mPindah->CA_CASEID;
                    $replacementsPengadu[7] = $ProfilPegawaiPindahOleh->email;
                    $replacementsPengadu[8] = $ProfilPegawaiPindahOleh->name;
                    $replacementsPengadu[9] = $ProfilPegawaiPindahOleh->cawangan->BR_BRNNM . '<br />'
                        . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR1 . '<br />'
                        . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR2 . '<br />'
                        . $ProfilPegawaiPindahOleh->cawangan->BR_POSCD . ' '
                        . $ProfilPegawaiPindahOleh->cawangan->DaerahPegawai->descr . '<br />'
                        . $ProfilPegawaiPindahOleh->cawangan->NegeriPegawai->descr;
                    $replacementsPengadu[10] = Carbon::now()->format('d/m/Y');
                } else if ($mPindah->CA_INVSTS == '0') { // Pindah ke Negeri/Bahagian/Cawangan Lain
                    $patternsPengadu[1] = "#NAMAPENGADU#";
                    $patternsPengadu[2] = "#ALAMATPENGADU#";
                    $patternsPengadu[3] = "#POSKODPENGADU#";
                    $patternsPengadu[4] = "#DAERAHPENGADU#";
                    $patternsPengadu[5] = "#NEGERIPENGADU#";
                    $patternsPengadu[6] = "#NOADUAN#";
                    $patternsPengadu[7] = "#EMAILPEGAWAIPENYIASAT#";
                    $patternsPengadu[8] = "#NAMAPEGAWAIPENYIASAT#";
                    $patternsPengadu[9] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                    $patternsPengadu[10] = "#NAMACAWANGANPEGAWAI#";
                    $patternsPengadu[11] = "#NOTELCAWANGANPEGAWAI#";
                    $patternsPengadu[12] = "#TARIKHPINDAH#";
                    $replacementsPengadu[1] = $mPindah->CA_NAME;
                    $replacementsPengadu[2] = $mPindah->CA_ADDR != '' ? $mPindah->CA_ADDR : '';
                    $replacementsPengadu[3] = $mPindah->CA_POSCD != '' ? $mPindah->CA_POSCD : '';
                    $replacementsPengadu[4] = $CA_DISTCD;
                    $replacementsPengadu[5] = $CA_STATECD;
                    $replacementsPengadu[6] = $mPindah->CA_CASEID;
                    $replacementsPengadu[7] = $ProfilPegawaiPindahOleh->email;
                    $replacementsPengadu[8] = $ProfilPegawaiPindahOleh->name;
                    $replacementsPengadu[9] = $ProfilPegawaiPindahOleh->cawangan->BR_BRNNM . '<br />' 
                        . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR1 . '<br />' 
                        . $ProfilPegawaiPindahOleh->cawangan->BR_ADDR2 . '<br />' 
                        . $ProfilPegawaiPindahOleh->cawangan->BR_POSCD . ' '. $ProfilPegawaiPindahOleh->cawangan->DaerahPegawai->descr . '<br />' 
                        . $ProfilPegawaiPindahOleh->cawangan->NegeriPegawai->descr;
    //                $replacementsPengadu[10] = $ProfilPegawaiPindahOleh->cawangan ? 
    //                    $ProfilPegawaiPindahOleh->cawangan->BR_BRNNM . ', '. $ProfilPegawaiPindahOleh->cawangan->NegeriPegawai->descr 
    //                    : '';
                    // $replacementsPengadu[10] = $ProfilPegawai->cawangan ?
                    //     $ProfilPegawai->cawangan->BR_BRNNM . ', '. $ProfilPegawai->cawangan->NegeriPegawai->descr
                    //     : '';
                    $replacementsPengadu[10] =
                        $mPindah->namacawangan
                        ? $mPindah->namacawangan->BR_BRNNM
                        : '';
    //                $replacementsPengadu[11] = $ProfilPegawaiPindahOleh->cawangan ? $ProfilPegawaiPindahOleh->cawangan->BR_TELNO : '';
                    // $replacementsPengadu[11] = $ProfilPegawai->cawangan ? $ProfilPegawai->cawangan->BR_TELNO : '';
                    $replacementsPengadu[11] =
                        $mPindah->namacawangan
                        ? $mPindah->namacawangan->BR_TELNO
                        : '';
                    $replacementsPengadu[12] = Carbon::now()->format('d/m/Y h:i A');
                }

                $date = date('YmdHis');
                $userid = Auth::user()->id;

                // Generate Surat Kepada Pengadu
                if (!empty($SuratPengadu)) {
                    $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
                    $arr_repPengadu = array("#", "#");
                    $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
                    $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

                    $filename = $userid . '_' . $mPindah->CA_CASEID . '_' . $date . '.pdf';
                    Storage::disk('letter')->put($filename, $SuratPengaduHtml->output()); // Store PDF to storage

                    $AttachSuratPengadu = new Attachment();
                    $AttachSuratPengadu->doc_title = $SuratPengadu->title;
                    $AttachSuratPengadu->file_name = $SuratPengadu->title;
                    $AttachSuratPengadu->file_name_sys = $filename;
                    if ($AttachSuratPengadu->save()) {
                        $SuratPengaduId = $AttachSuratPengadu->id;
                    }
                } else {
                    $SuratPengaduId = NULL;
                }

                // Generate Surat Kepada Pegawai
                if (!empty($SuratPegawai)) {
                    $SuratPegawaiId = NULL;
                } else {
                    $SuratPegawaiId = NULL;
                }

                PindahAduanDetail::where(['CD_CASEID' => $mPindah->CA_CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                $mPindahDetail = new PindahAduanDetail();
                $mPindahDetail->CD_CASEID = $mPindah->CA_CASEID;
                if ($Request->expectsJson()) {
                    $mPindahDetail->CD_TYPE = 'S'; // EZSTAR
                } else {
                    $mPindahDetail->CD_TYPE = 'D';
                }
                $mPindahDetail->CD_DESC = $Request->CD_DESC;
                $mPindahDetail->CD_INVSTS = $mPindah->CA_INVSTS;
                $mPindahDetail->CD_CASESTS = $mPindah->CA_CASESTS;
                $mPindahDetail->CD_CURSTS = 1;
                $mPindahDetail->CD_DOCATTCHID_PUBLIC = $SuratPengaduId;
                $mPindahDetail->CD_DOCATTCHID_ADMIN = $SuratPegawaiId;
                $mPindahDetail->CD_ACTFROM = Auth::User()->id;
                // $mPindahDetail->CD_ACTTO = $mPindah->CA_INVBY;
                $mPindahDetail->CD_BRNCD_FROM = $mPindahOldBrncd;
                if($Request->CA_INVSTS == '0'){
                    $mPindahDetail->CD_BRNCD_TO = $Request->CA_BRNCD;
                }
                $mPindahDetail->CD_REASON = $Request->CD_REASON["'$CASEID'"];
                $mPindahDetail->CD_REASON_DURATION = $Request->CD_REASON_DURATION["'$CASEID'"];
                if($Request->CD_REASON["'$CASEID'"] == 'P2') {
                    $mPindahDetail->CD_REASON_DATE_FROM =
                        Carbon::parse($Request->CD_REASON_DATE_FROM["'$CASEID'"]);
                    $mPindahDetail->CD_REASON_DATE_TO =
                        Carbon::parse($Request->CD_REASON_DATE_TO["'$CASEID'"]);
                }
                $mPindahDetail->save();
            }
        }
        
        if ($Request->expectsJson()) {
            return response()->json(['data' => 'Aduan telah berjaya dipindah']);
        }
        return redirect()->route('pindah.index')->with('success', 'Aduan telah <b>BERJAYA</b> dipindah');
    }

}
