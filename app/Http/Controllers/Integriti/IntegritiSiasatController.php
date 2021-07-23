<?php
namespace App\Http\Controllers\Integriti;

use App\Aduan\GabungRelation;
use App\Agensi;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAct;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
use App\Integriti\IntegritiAdminDoc;
use App\Integriti\IntegritiForward;
use App\Letter;
use App\Mail\RujukAgensiLain;
use App\Mail\SiasatMaklumatTakLengkap;
use App\Mail\SiasatLuarBidang;
use App\User;
// use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Image;
use PDF;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class IntegritiSiasatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('integriti.siasat.index');
    }

    public function GetDataTable(Request $request)
    {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mSiasat = IntegritiAdmin::where([
                ['IN_CASEID', '<>', null],
                ['IN_INVSTS', '=', '02'],
                // ['IN_IPSTS', '=', '02'],
                // ['IN_BRNCD', '=', Auth::user()->brn_cd],
                ['IN_INVBY', '=', Auth::user()->id],
            ])
            ->whereNull('IN_COMPLETEBY')
            ->whereNull('IN_COMPLETEDT')
            ->orderBy('IN_RCVDT', 'DESC');

        if ($request->mobile) {
            return response()->json(['data' => $mSiasat->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = Datatables::of($mSiasat)
            ->addIndexColumn()
            ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                return view('integriti.base.summarylink', compact('integriti'))->render();
            })
            ->editColumn('IN_INVSTS', function (IntegritiAdmin $Siasat) {
                if ($Siasat->IN_INVSTS != '')
                    return $Siasat->invsts->descr;
                else
                    return '';
            })
            ->editColumn('IN_RCVDT', function (IntegritiAdmin $Siasat) {
                if ($Siasat->IN_RCVDT != '')
                    return date('d-m-Y', strtotime($Siasat->IN_RCVDT));
                else
                    return '';
            })
            ->editColumn('IN_SUMMARY', function (IntegritiAdmin $Siasat) {
                if ($Siasat->IN_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $Siasat->IN_SUMMARY), 0, 10)) . ' ...';
                else
                    return '';
            })
            /* ->addColumn('tempoh', function (Penyiasatan $Siasat) use ($TempohPertama, $TempohKedua, $TempohKetiga) {
                $totalDuration = $Siasat->GetDuration($Siasat->IN_RCVDT, $Siasat->IN_CASEID);
                if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                    return '<div style="background-color:#3F6; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                    return '<div style="background-color:#FF3;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                    return '<div style="background-color:#F0F; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohKetiga->code)
                    return '<div style="background-color:#F00; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
            }) */
            ->addColumn('action', '<a href="{{ route("integritisiasat.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
            ->rawColumns(['IN_CASEID', 'action']) //'tempoh'
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
                if ($request->has('IN_RCVDT')) {
                    $query->whereDate('IN_RCVDT', Carbon::parse($request->get('IN_RCVDT'))->format('Y-m-d'));
                }
            });

        return $datatables->make(true);
    }

    public function PenyiasatanAduan($IN_CASEID)
    {
        $mUser = User::find(Auth::User()->id);
        $mSiasat = Penyiasatan::where(['IN_CASEID' => $IN_CASEID])->first();
        return view('aduan.Penyiasatan.penyiasatan_aduan', compact('mUser', 'mSiasat'));
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {

    }

    public function store(Request $IN_CASEID)
    {
        $mSiasatDetail = new SiasatDetail;
        $mSiasatDetail->CD_DESC = request('CD_DESC');
    }

    public function show($IN_CASEID)
    {
    }

    public function edit($id)
    {
        $mIntegriti = IntegritiAdmin::where('id', $id)->first();
        $IN_CASEID = $mIntegriti->IN_CASEID;
        $mSiasat = IntegritiAdmin::where('IN_CASEID', $IN_CASEID)->first();
        $mAttachSiasatCount = DB::table('integriti_case_doc')
            ->where(['IC_CASEID' => $IN_CASEID, 'IC_DOCCAT' => 2])
            ->count('IC_CASEID');
        /* $mKesSiasatCount = DB::table('case_act')
            ->where(['CT_CASEID' => $IN_CASEID])
            ->count('CT_CASEID'); */
        $mKesSiasatCount = 0;
        $AttachmentCount = DB::table('integriti_case_doc')
            ->where('IC_CASEID', $IN_CASEID)
            ->where('IC_DOCCAT', '<>', 2)
            ->count('IC_CASEID');
        $mResultJMA = IntegritiAdminDetail::where(['ID_CASEID' => $IN_CASEID, 'ID_INVSTS' => '02'])->whereNull('ID_IPSTS')->first();
        $mAlasanTolak = IntegritiAdminDetail::where(['ID_CASEID' => $IN_CASEID, 'ID_INVSTS' => '02', 'ID_IPSTS' => '02'])->orderBy('ID_CREATED_AT', 'DESC')->first();
        $mGabungOne = DB::table('integriti_case_rel')->where(['IR_CASEID' => $IN_CASEID])->first();
        if ($mGabungOne) {
            $mGabungAll = DB::table('integriti_case_rel')->where(['IR_RELID' => $mGabungOne->IR_RELID])->get();
        } else {
            $mGabungAll = '';
        }
        return view('integriti.siasat.edit', compact('mSiasat', 'AttachmentCount', 'mKesSiasatCount', 'mResultJMA', 'mAlasanTolak', 'mAttachSiasatCount', 'mGabungAll'));
    }

    public function GetSubAkta($Akta)
    {
        $mRef = DB::table('sys_ref')->where(['cat' => '714', 'status' => '1'])->where('code', 'LIKE', "{$Akta}%")->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('code', 'descr');

        $mRef->prepend('', '-- SILA PILIH --');
        return $mRef;
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        if ($request->hantar) {
            $this->validate($request, [
                // 'IN_AREACD' => 'required',
                // 'IN_IPSTS' => 'required',
                // 'IN_MAGNCD' => 'required_if:IN_IPSTS,04',
                'IN_RESULT' => 'required',
                'IN_RECOMMEND' => 'required',
                'IN_ANSWER' => 'required',
                'IN_RECOMMENDTYP'    => 'required|array|min:1',
                'IN_RECOMMENDTYP.*'  => 'required|string|distinct|min:1',
                // 'IN_SSP' => 'required_if:IN_INVSTS,3',
                // 'IN_IPNO' => 'required_if:IN_SSP,YES',
                // 'IN_AKTA' => 'required_if:IN_SSP,YES',
                // 'IN_SUBAKTA' => 'required_if:IN_SSP,YES',
            ],
            [
                // 'IN_AREACD.required' => 'Ruangan Kawasan Kes diperlukan.',
                // 'IN_IPSTS.required' => 'Ruangan Status Penyiasatan diperlukan.',
                // 'IN_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
                'IN_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan.',
                'IN_RECOMMEND.required' => 'Ruangan Catatan Pengesyoran diperlukan.',
                'IN_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
                'IN_RECOMMENDTYP.required' => 'Ruangan Jenis Pengesyoran diperlukan.',
                // 'IN_RECOMMENDTYP.*.required' => 'Ruangan Jenis Pengesyoran diperlukan',
                // 'IN_SSP.required_if' => 'Ruangan Wujud Kes diperlukan.',
                // 'IN_IPNO.required_if' => 'Ruangan No.IP diperlukan.',
                // 'IN_AKTA.required_if' => 'Ruangan Akta diperlukan.',
                // 'IN_SUBAKTA.required_if' => 'Ruangan Kod Akta diperlukan.',
            ]);
        }

        $mIntegriti = IntegritiAdmin::where('id', $id)->first();
        $CASEID = $mIntegriti->IN_CASEID;
        if ($request->hantar) {
            $CheckRelation = DB::table('integriti_case_rel')->where('IR_CASEID', $CASEID)->value('IR_RELID');

            if ($CheckRelation) {
                $ArrCaseId = DB::table('integriti_case_rel')->where('IR_RELID', $CheckRelation)->pluck('IR_CASEID');
                foreach ($ArrCaseId as $GetCaseId) {
                    $Save = $this->Save($request, $GetCaseId);
                }
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Aduan telah berjaya dihantar']);
                }
                return redirect()->route('integritisiasat.index')->with('success', 'Aduan telah berjaya dihantar');
            } else {
                $Save = $this->Save($request, $CASEID);
                if ($Save) {
                    if ($request->expectsJson()) {
                        return response()->json(['message' => 'Aduan telah berjaya dihantar']);
                    }
                    return redirect()->route('integritisiasat.index')->with('success', 'Aduan telah berjaya dihantar');
                } else
                    return back()->with('alert', 'Masalah Teknikal');
            }
        } else { // untuk butang simpan
            $mSiasat = IntegritiAdmin::where('id', $id)->first();
            $data = $request->all();
            if (!empty($data['IN_RECOMMENDTYP'])) {
                $data['IN_RECOMMENDTYP'] = implode(",",$data['IN_RECOMMENDTYP']);
            }
            $mSiasat->fill($data);
            $mSiasat->IN_COMPLETEDT = null;
            $mSiasat->IN_INVSTS = '02';
            if ($mSiasat->save()) {
                if ($request->expectsJson()) {
                    return response()->json(['data' => $mSiasat->id, 'message' => 'Aduan telah berjaya disimpan']);
                }
                return back()->with('success', 'Aduan telah berjaya disimpan');
            }
        }
    }

    public function siasat(Request $request, $CASEID)
    {
        $this->validate($request, [
            // 'IN_AREACD' => 'required',
            'IN_INVSTS' => 'required',
            'IN_MAGNCD' => 'required_if:IN_INVSTS,4',
            'IN_RESULT' => 'required',
            'IN_RECOMMEND' => 'required',
            'IN_ANSWER' => 'required',
            // 'IN_SSP' => 'required',
            // 'IN_IPNO' => 'required_if:IN_SSP,YES',
            // 'IN_AKTA' => 'required_if:IN_SSP,YES',
            // 'IN_SUBAKTA' => 'required_if:IN_SSP,YES',
        ],
        [
            // 'IN_AREACD.required' => 'Ruangan Kawasan Kes diperlukan.',
            'IN_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
            'IN_MAGNCD.required_if' => 'Ruangan Kementerian/Agensi diperlukan.',
            'IN_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan.',
            'IN_RECOMMEND.required' => 'Ruangan Saranan diperlukan.',
            'IN_ANSWER.required' => 'Ruangan Jawapan Pada Pengadu diperlukan.',
            // 'IN_SSP.required' => 'Ruangan Kes SPP diperlukan.',
            // 'IN_IPNO.required_if' => 'Ruangan No.IP diperlukan.',
            // 'IN_AKTA.required_if' => 'Ruangan Akta diperlukan.',
            // 'IN_SUBAKTA.required_if' => 'Ruangan Kod Akta diperlukan.',
        ]);

        $mSiasat = IntegritiAdmin::where('IN_CASEID', $CASEID)->first();
        $mSiasat->fill($request->all());
        $mSiasat->IN_COMPLETEDT = null;
        $mSiasat->IN_INVSTS = '2';
        if ($mSiasat->save()) {
//                return redirect()->route('siasat.edit')->with('success','Aduan telah berjaya dihantar');
            return redirect()->route('integritisiasat.edit#mak_aduan', $mSiasat->$CASEID);
        }
    }

    public function Save($request, $CASEID)
    {
        $mSiasat = IntegritiAdmin::where('IN_CASEID', $CASEID)->first();
        $data = $request->all();
        $data['IN_RECOMMENDTYP'] = implode(",",$data['IN_RECOMMENDTYP']);
        $mSiasat->fill($data);
        $mSiasat->IN_IPSTS = '03';
        $mSiasat->IN_COMPLETEBY = Auth::user()->id;
        $mSiasat->IN_COMPLETEDT = Carbon::now();
        if ($request->IN_IPSTS == '04') { // Penutupan (Rujuk Ke Agensi / Bahagian / Kementerian)
            $mAgensi = Agensi::where(['MI_MINCD' => $mSiasat->IN_MAGNCD])->first();
            /* if ($mAgensi->MI_EMAIL) {
                Mail::to($mAgensi->MI_EMAIL)->send(new RujukAgensiLain($mSiasat));
            } */
        }
        if ($mSiasat->save()) {

            /* $SuratPengadu = Letter::where(['letter_code' => $request->IN_INVSTS, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
            if (!empty($SuratPengadu))
                $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
            $ProfilPegawai = User::find($mSiasat->IN_INVBY);

            if (!empty($mSiasat->IN_STATECD)) {
                $StateNm = DB::table('sys_ref')->select('descr')->where(['cat' => '17', 'code' => $mSiasat->IN_STATECD])->first();
                if (!$StateNm) {
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat' => '334', 'code' => $mSiasat->IN_STATECD])->first();
                }
                $IN_STATECD = $StateNm->descr;
            } else {
                $IN_STATECD = '';
            }
            if (!empty($mSiasat->IN_DISTCD)) {
                $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat' => '18', 'code' => $mSiasat->IN_DISTCD])->first();
                if (!$DestrictNm) {
                    $IN_DISTCD = $mSiasat->IN_DISTCD;
                } else {
                    $IN_DISTCD = $DestrictNm->descr;
                }
            } else {
                $IN_DISTCD = '';
            }

            if ($mSiasat->IN_INVSTS == '3') // Siasatan Selesai
            {
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#JAWAPANKEPADAPENGADU#";
                $patternsPengadu[8] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[9] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $replacementsPengadu[1] = $mSiasat->IN_NAME;
                $replacementsPengadu[2] = $mSiasat->IN_ADDR != '' ? $mSiasat->IN_ADDR : '';
                $replacementsPengadu[3] = $mSiasat->IN_POSCD != '' ? $mSiasat->IN_POSCD : '';
                $replacementsPengadu[4] = $IN_DISTCD;
                $replacementsPengadu[5] = $IN_STATECD;
                $replacementsPengadu[6] = $CASEID;
                $replacementsPengadu[7] = $mSiasat->IN_ANSWER;
                $replacementsPengadu[8] = $ProfilPegawai->name;
                $replacementsPengadu[9] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
            } elseif ($mSiasat->IN_INVSTS == '4') // RUJUK KE KEMENTERIAN/AGENSI LAIN
            {
                $mAgensi = DB::table('sys_min')->select('*')->where('MI_MINCD', $mSiasat->IN_MAGNCD)->first();
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
                    . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $mAgensi->MI_POSCD . ' '
                    . \App\Ref::GetDescr('18', $mAgensi->MI_DISTCD, 'ms') . '<br />'
                    . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . \App\Ref::GetDescr('17', $mAgensi->MI_STATECD, 'ms') : '';
                $replacementsPengadu[3] = $CASEID;
                $replacementsPengadu[4] = $mSiasat->IN_SUMMARY;
                $replacementsPengadu[5] = $mSiasat->IN_ANSWER;
                $replacementsPengadu[6] = $ProfilPegawai->name;
                $replacementsPengadu[7] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[8] = $mSiasat->IN_NAME;
                $replacementsPengadu[9] = $mSiasat->IN_ADDR != '' ? $mSiasat->IN_ADDR : '';
                $replacementsPengadu[10] = $mSiasat->IN_POSCD != '' ? $mSiasat->IN_POSCD : '';
                $replacementsPengadu[11] = $IN_DISTCD;
                $replacementsPengadu[12] = $IN_STATECD;
                $replacementsPengadu[13] = !empty($mAgensi) ? $mAgensi->MI_TELNO : '';
                $replacementsPengadu[14] = !empty($mAgensi) ? $mAgensi->MI_EMAIL : '';
            } elseif ($mSiasat->IN_INVSTS == '5') // Rujuk Ke Tribunal
            {
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $replacementsPengadu[1] = $mSiasat->IN_NAME;
                $replacementsPengadu[2] = $mSiasat->IN_ADDR != '' ? $mSiasat->IN_ADDR : '';
                $replacementsPengadu[3] = $mSiasat->IN_POSCD != '' ? $mSiasat->IN_POSCD : '';
                $replacementsPengadu[4] = $IN_DISTCD;
                $replacementsPengadu[5] = $IN_STATECD;
                $replacementsPengadu[6] = $CASEID;
                $replacementsPengadu[7] = $ProfilPegawai->name;
                $replacementsPengadu[8] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
            } elseif ($mSiasat->IN_INVSTS == '6') // Pertanyaan
            {

            } elseif ($mSiasat->IN_INVSTS == '7') // Maklumat Tidak Lengkap
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
                $replacementsPengadu[1] = $mSiasat->IN_NAME;
                $replacementsPengadu[2] = $mSiasat->IN_ADDR != '' ? $mSiasat->IN_ADDR : '';
                // $replacementsPengadu[3] = $mPenugasan->IN_POSCD != ''? $mPenugasan->IN_POSCD : '';
                $replacementsPengadu[3] = $IN_DISTCD;
                $replacementsPengadu[4] = $IN_STATECD;
                $replacementsPengadu[5] = $CASEID;
                $replacementsPengadu[6] = $mSiasat->IN_ANSWER;
                $replacementsPengadu[7] = $ProfilPegawai->name;
                $replacementsPengadu[8] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
                // if($mSiasat->IN_EMAIL){
                //     Mail::to($mSiasat->IN_EMAIL)->send(new MaklumatAduanTaklengkap($mSiasat));
                // }
            } elseif ($mSiasat->IN_INVSTS == '8') // Diluar Bidang Kuasa
            {
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $replacementsPengadu[1] = $mSiasat->IN_NAME;
                $replacementsPengadu[2] = $mSiasat->IN_ADDR != '' ? $mSiasat->IN_ADDR : '';
                $replacementsPengadu[3] = $mSiasat->IN_POSCD != '' ? $mSiasat->IN_POSCD : '';
                $replacementsPengadu[4] = $IN_DISTCD;
                $replacementsPengadu[5] = $IN_STATECD;
                $replacementsPengadu[6] = $CASEID;
                $replacementsPengadu[7] = $ProfilPegawai->name;
                $replacementsPengadu[8] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
            }

            $date = date('Ymdhis');
            $userid = Auth::user()->id;

            if (!empty($SuratPengadu)) {
                $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
                $arr_repPengadu = array("#", "#");
                $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
                $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

                $filename = $userid . '_' . $CASEID . '_' . $date . '.pdf';
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
            } */

            $mCaseDetailOldInv = IntegritiAdminDetail::where(['ID_CASEID' => $CASEID, 'ID_ACTTYPE' => 'KICK'])->first();
            IntegritiAdminDetail::where(['ID_CASEID' => $CASEID, 'ID_CURSTS' => '1'])->update(['ID_CURSTS' => '0']);
            $mNewSiasatDetail = new IntegritiAdminDetail();
            $mNewSiasatDetail->ID_CASEID = $CASEID;
            if ($request->expectsJson()) {
                $mNewSiasatDetail->ID_TYPE = 'S'; // EZSTAR
            } else {
                $mNewSiasatDetail->ID_TYPE = 'D';
            }
            if (!empty($mCaseDetailOldInv)) {
                $mNewSiasatDetail->ID_ACTTYPE = 'INVAGAIN';
            } else {
                $mNewSiasatDetail->ID_ACTTYPE = null;
            }
            $mNewSiasatDetail->ID_DESC = $mSiasat->IN_RECOMMEND;
            $mNewSiasatDetail->ID_ANSWER = $mSiasat->IN_ANSWER;
            $mNewSiasatDetail->ID_INVSTS = $mSiasat->IN_INVSTS;
            $mNewSiasatDetail->ID_IPSTS = $mSiasat->IN_IPSTS;
            $mNewSiasatDetail->ID_CASESTS = $mSiasat->IN_CASESTS;
            $mNewSiasatDetail->ID_CURSTS = 1;
            // $mNewSiasatDetail->ID_DOCATTCHID_PUBLIC = $SuratPengaduId;
            if ($mNewSiasatDetail->save()) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function getattachment(Request $request, $CASEID)
    {
        $mSiasatDoc = PenyiasatanDoc::where('CC_CASEID', $CASEID)
            ->where('CC_IMG_CAT', 1);

        if ($request->mobile) {
            return response()->json(['data' => $mSiasatDoc->get()->toArray()]);
        }
        $datatables = Datatables::of($mSiasatDoc)
            ->addIndexColumn()
            ->editColumn('CC_IMG_NAME', function (PenyiasatanDoc $PenyiasatanDoc) {
                if ($PenyiasatanDoc->CC_IMG_NAME != '')
                    return '<a href=' . Storage::disk('bahanpath')->url($PenyiasatanDoc->CC_PATH . $PenyiasatanDoc->CC_IMG) . ' target="_blank">' . $PenyiasatanDoc->CC_IMG_NAME . '</a>';
                else
                    return '';
            })
            ->rawColumns(['CC_IMG_NAME']);

        return $datatables->make(true);
    }

    public function getAttachmentSiasat(Request $request, $CASEID)
    {
        if (strpos($CASEID,'_') !== false) {
            $CASEID = str_replace('_','/',$CASEID);
        } else {
            $mIntegriti = IntegritiAdmin::where('id', $CASEID)->first();
            $CASEID = $mIntegriti->IN_CASEID;
        }
        $mSiasatDoc = IntegritiAdminDoc::where(['IC_CASEID' => $CASEID, 'IC_DOCCAT' => 2])->get();

        if ($request->mobile) {
            return response()->json(['data' => $mSiasatDoc->toArray()]);
        }
        $datatables = Datatables::of($mSiasatDoc)
            ->addIndexColumn()
            ->editColumn('IC_DOCFULLNAME', function (IntegritiAdminDoc $PenyiasatanDoc) {
                if ($PenyiasatanDoc->IC_DOCFULLNAME != '')
                    return '<a href=' . Storage::disk('integritibahanpath')->url($PenyiasatanDoc->IC_PATH . $PenyiasatanDoc->IC_DOCNAME) . ' target="_blank">' . $PenyiasatanDoc->IC_DOCFULLNAME . '</a>';
                else
                    return '';
            })
            ->addColumn('action', function (IntegritiAdminDoc $PenyiasatanDoc) {
                return view('integriti.siasat.attach_siasat_action_btn', compact('PenyiasatanDoc'))->render();
            })
            ->rawColumns(['action', 'IC_DOCFULLNAME']);

        return $datatables->make(true);
    }


    public function CreateAttachSiasat($CASEID)
    {
        return view('integriti.siasat.create_attach_siasat', compact('CASEID'));
    }

    public function StoreAttachSiasat(Request $request, $CASEID)
    {
        $date = date('Ymdhis');
        if ($request->userid) {
            $userid = $request->userid;
        } else {
            $userid = Auth::user()->id;
        }
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');
        $mIntegriti = IntegritiAdmin::where('id', $CASEID)->first();
        $request->IC_CASEID = $mIntegriti->IN_CASEID;

        if ($file) {
            $filename = $userid . '_' . $request->IC_CASEID . '_' . $date . '.' . $file->getClientOriginalExtension();
            $directory = '/' . $Year . '/' . $Month . '/';

            if ($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $resize->stream();
                Storage::disk('integritibahan')->put($directory . $filename, $resize);
            } else {
                Storage::disk('integritibahan')->putFileAs('/' . $Year . '/' . $Month . '/', $request->file('file'), $filename);
            }

            $mPublicCaseDoc = new \App\Integriti\IntegritiAdminDoc();
            $mPublicCaseDoc->IC_CASEID = $request->IC_CASEID;
            $mPublicCaseDoc->IC_PATH = Storage::disk('integritibahan')->url($directory);
            $mPublicCaseDoc->IC_DOCNAME = $filename;
            $mPublicCaseDoc->IC_DOCFULLNAME = $file->getClientOriginalName();
            $mPublicCaseDoc->IC_REMARKS = $request->IC_REMARKS;
            $mPublicCaseDoc->IC_DOCCAT = 2;
            if ($mPublicCaseDoc->save()) {
                if ($request->userid) {
                    return response()->json(['data' => 'ok']);
                }
                return response()->json(['success']);
//                    return redirect()->route('siasat.edit',$request->CC_CASEID);
            }
        }
    }

    public function EditAttachSiasat($id)
    {
        $mPublicCaseDoc = IntegritiAdminDoc::where('id', $id)->first();
        return view('integriti.siasat.edit_attach_siasat', compact('mPublicCaseDoc'));
    }

    public function UpdateAttachSiasat(Request $request, $id)
    {
        $mPublicCaseDoc = IntegritiAdminDoc::where('id', $id)->first();

        $file = $request->file('file');
        $date = date('Ymdhis');
        $userid = Auth()->user()->id;
        $Year = date('Y');
        $Month = date('m');

        if ($file) {
            Storage::delete($mPublicCaseDoc->IC_PATH . $mPublicCaseDoc->IC_DOCNAME); // Delete old attachment
            $filename = $userid . '_' . $mPublicCaseDoc->IC_CASEID . '_' . $date . '.' . $file->getClientOriginalExtension(); // Store new attachment
            $directory = '/' . $Year . '/' . $Month . '/';

            if ($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $resize->stream();
                Storage::disk('integritibahan')->put($directory . $filename, $resize);
            } else {
                Storage::disk('integritibahan')->putFileAs('/' . $Year . '/' . $Month . '/', $request->file('file'), $filename);
            }
            // Update record
            $mPublicCaseDoc->IC_PATH = Storage::disk('integritibahan')->url($directory);
            $mPublicCaseDoc->IC_DOCNAME = $filename;
            $mPublicCaseDoc->IC_DOCFULLNAME = $file->getClientOriginalName();
            $mPublicCaseDoc->IC_REMARKS = $request->IC_REMARKS;
            // Save record
            if ($mPublicCaseDoc->save()) {
                return response()->json(['success']);
//                return redirect()->back();
            }
        } else {
            $mPublicCaseDoc->IC_REMARKS = $request->IC_REMARKS;
            if ($mPublicCaseDoc->save()) {
                return response()->json(['success']);
//                return redirect()->back();
            }
        }
    }

    public function DestroyAttachSiasat($id)
    {
        $model = IntegritiAdminDoc::where('id', $id)->first();
        Storage::delete($model->IC_PATH . $model->IC_DOCNAME);
        if ($model->delete()) {
            return response()->json(['success']);
//            return redirect()->route('siasat.edit',$model->CC_CASEID);
        }
    }

    public function getKesSiasat(Request $request, $CASEID)
    {
        if (strpos($CASEID,'_') !== false) {
            $CASEID = str_replace('_','/',$CASEID);
        } else {
            $mIntegriti = IntegritiAdmin::where('id', $CASEID)->first();
            $CASEID = $mIntegriti->IN_CASEID;
        }
        $mSiasatKes = IntegritiAct::where(['IT_CASEID' => $CASEID])->get();

        if ($request->mobile) {
            return response()->json(['data' => $mSiasatKes->toArray()]);
        }
        $datatables = Datatables::of($mSiasatKes)
            ->addIndexColumn()
            ->editColumn('IT_SUBAKTA', function (IntegritiAct $Siasatkes) {
                if ($Siasatkes->IT_SUBAKTA != '')
                    return $Siasatkes->subakta->descr;
                else
                    return '';
            })
            ->editColumn('IT_AKTA', function (IntegritiAct $Siasatkes) {
                if ($Siasatkes->IT_AKTA != '')
                    return $Siasatkes->akta->descr;
                else
                    return '';
            })
            ->addColumn('action', function (IntegritiAct $SiasatKes) {
                return view('integriti.siasat.kes_siasat_action_btn', compact('SiasatKes'))->render();
            })
        ;

        return $datatables->make(true);
    }

    public function CreateKesSiasat($CASEID)
    {
        return view('integriti.siasat.create_kes_siasat', compact('CASEID'));
    }

    public function storekessiasat($CASEID, Request $request)
    {
        $mKes = new IntegritiAct;
        $mKes->fill($request->all());
        $mKes->IT_CASEID = str_replace('_','/',$CASEID);
        if ($mKes->save()) {
            if ($request->expectsJson()) {
                return response()->json(['data' => 'Akta telah berjaya ditambah']);
            }
            return response()->json(['success']);
//           return redirect()->route('siasat.edit', $CASEID);
        }
//           echo $CASEID;
//        return view('aduan.siasat.create_kes_siasat', compact('CASEID'));
    }

    public function EditKesSiasat($id)
    {
        $SiasatKes = IntegritiAct::where('id', $id)->first();
        return view('integriti.siasat.edit_kes_siasat', compact('SiasatKes'));
    }

    public function UpdateKesSiasat($id, Request $request)
    {
        $mKes = IntegritiAct::where('id', $id)->first();
        $mKes->fill($request->all());
        if ($mKes->save()) {
            if ($request->expectsJson()) {
                return response()->json(['data' => 'Akta telah berjaya dikemaskini']);
            }
            return response()->json(['success']);
//            return back()->with('success','Akta telah berjaya dikemaskini');
        }
    }

    public function DestroyKesSiasat(Request $request, $id)
    {
        $mKes = IntegritiAct::where('id', $id)->first();
        if ($mKes->delete()) {
            if ($request->expectsJson()) {
                return response()->json(['data' => 'Akta telah berjaya dibuang']);
            }
            return response()->json(['success']);
//            return redirect()->route('siasat.edit',$mKes->CT_CASEID);
        }
    }

    public function GetGabung(Request $request, $CASEID)
    {
        $CheckRelation = GabungRelation::where('CR_CASEID', $CASEID)->value('CR_RELID');
        if ($CheckRelation)
            $RelId = $CheckRelation;
        else
            $RelId = '0';

        $mCaseRelation = GabungRelation::where('CR_RELID', $RelId);

        if ($request->mobile) {
            return response()->json(['data' => $mCaseRelation->get()->toArray()]);
        }
        $datatables = Datatables::of($mCaseRelation)
            ->addIndexColumn()
            ->editColumn('SUMMARY', function (GabungRelation $mCaseRelation) {
                return '<div style="max-height:80px; overflow:auto">' . $mCaseRelation->aduan->IN_SUMMARY . '</div>';
            })
            ->addColumn('action', '<a href="{{ route("siasat.edit", $CR_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
            ->rawColumns(['SUMMARY', 'action']);

        return $datatables->make(true);
    }

    public function gettransaction($CASEID)
    {
        $mSiasatDetail = PenyiasatanDetail::where(['CD_CASEID' => $CASEID])->orderBy('CD_CREDT', 'DESC');

        $datatables = Datatables::of($mSiasatDetail)
            ->addIndexColumn()
            ->editColumn('IN_INVSTS', function (PenyiasatanDetail $SiasatDetail) {
                if ($SiasatDetail->CD_INVSTS != '')
                    return $SiasatDetail->statusaduan->descr;
                else
                    return '';
            })
            ->editColumn('CD_ACTFROM', function (PenyiasatanDetail $SiasatDetail) {
                if ($SiasatDetail->CD_ACTFROM != '') {
                    if ($SiasatDetail->actfrom) {
                        return $SiasatDetail->actfrom->name;
                    } else {
                        return $SiasatDetail->CD_ACTFROM;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CD_ACTTO', function (PenyiasatanDetail $SiasatDetail) {
                if ($SiasatDetail->CD_ACTTO != '') {
                    if ($SiasatDetail->actto) {
                        return $SiasatDetail->actto->name;
                    } else {
                        return $SiasatDetail->CD_ACTTO;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CD_CREDT', function (PenyiasatanDetail $SiasatDetail) {
                if ($SiasatDetail->CD_CREDT != '')
                    return Carbon::parse($SiasatDetail->CD_CREDT)->format('d-m-Y h:i A');
                else
                    return '';
            })
            ->addColumn('SURAT', function (PenyiasatanDetail $SiasatDetail) {
                if ($SiasatDetail->CD_DOCATTCHID_PUBLIC != '' && $SiasatDetail->CD_DOCATTCHID_ADMIN != '') {
                    return '<ol>'
                        . '<li><a href=' . Storage::disk('letter')->url($SiasatDetail->suratpublic->file_name_sys) . ' target="_blank">' . $SiasatDetail->suratpublic->file_name . '</a></li>'
//                            .'<br />'
                        . '<li><a href=' . Storage::disk('letter')->url($SiasatDetail->suratadmin->file_name_sys) . ' target="_blank">' . $SiasatDetail->suratadmin->file_name . '</a></li>'
                        . '</ol>';
                } elseif ($SiasatDetail->CD_DOCATTCHID_PUBLIC != '' && $SiasatDetail->CD_DOCATTCHID_ADMIN == '') {
                    return '<ol><li><a href=' . Storage::disk('letter')->url($SiasatDetail->suratpublic->file_name_sys) . ' target="_blank">' . $SiasatDetail->suratpublic->file_name . '</a></li></ol>';
                } elseif ($SiasatDetail->CD_DOCATTCHID_PUBLIC == '' && $SiasatDetail->CD_DOCATTCHID_ADMIN != '') {
                    return '<ol><li><a href=' . Storage::disk('letter')->url($SiasatDetail->suratadmin->file_name_sys) . ' target="_blank">' . $SiasatDetail->suratadmin->file_name . '</a></li></ol>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['SURAT']);

        return $datatables->make(true);
    }

    public function destroy($IN_CASEID)
    {

    }

    public function getkeslist($akta)
    {
        $mRef = DB::table('sys_ref')
            ->where(['cat' => '714', 'status' => '1'])
            ->where('code', 'LIKE', "{$akta}%")
            ->orderBy('sort', 'asc')
            ->orderBy('descr', 'asc')
            ->pluck('code', 'descr');
        $mRef->prepend('', '-- SILA PILIH --');
        return $mRef;

    }

    public function pdf($IN_CASEID)
    {
        $mSiasat = Penyiasatan::find($IN_CASEID);
        $data = [
            'mSiasat' => $mSiasat,
        ];
        $pdf = PDF::loadView('aduan.Penyiasatan.siasatpdf', $data);
        return $pdf->stream('document.pdf');
    }

    public function ShowSummary($CASEID)
    {
        $model = Penyiasatan::where(['IN_CASEID' => $CASEID])->first();
        $trnsksi = PenyiasatanDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenyiasatanDoc::where(['CC_CASEID' => $CASEID])->get();
        $kes = PenyiasatanKes::where(['CT_CASEID' => $CASEID])->get();
        return view('aduan.siasat.show_summary_modal', compact('model', 'trnsksi', 'kes', 'img'));

//        $model = Penyiasatan::where(['IN_CASEID' => $CASEID])->first();
//        return view('aduan.siasat.show_summary_modal', compact('model'));
    }


    public function PrintSummary($CASEID)
    {
        $model = Penyiasatan::where(['IN_CASEID' => $CASEID])->first();
        $trnsksi = PenyiasatanDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenyiasatanDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('aduan.siasat.show_summary_modal', compact('model', 'trnsksi', 'img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }

    public function AjaxValidateKes(Request $request)
    {
        $Ip = $request->IT_IPNO;

        $validator = Validator::make($request->all(),
            [
//                'IT_IPNO' => 'required|max:20',
//                'IT_IPNO' => 'required_without_all:IT_EPNO|max:20',
//                'IT_EPNO' => 'required_without_all:IT_IPNO|max:20',
                'IT_IPNO' => 'max:20|required_without:IT_EPNO|both_not_filled:'.$request->get('IT_EPNO'),
                'IT_EPNO' => 'max:20|required_without:IT_IPNO|both_not_filled:'.$request->get('IT_IPNO'),
                'IT_AKTA' => 'required',
                'IT_SUBAKTA' => 'required',
            ],
            [
//                'IT_IPNO.required' => 'Ruangan No. Kertas Siasatan / EP diperlukan.',
                'IT_IPNO.required_without' => ' Sila isikan salah satu maklumat berikut: No. IP / No. EP',
                'IT_EPNO.required_without' => ' Sila isikan salah satu maklumat berikut: No. IP / No. EP',
                'IT_IPNO.both_not_filled' => ' Sila isikan salah satu maklumat berikut: No. IP / No. EP',
                'IT_EPNO.both_not_filled' => ' Sila isikan salah satu maklumat berikut: No. IP / No. EP',
                'IT_IPNO.max' => 'Ruangan No. IP mesti tidak melebihi :max aksara.',
                'IT_EPNO.max' => 'Ruangan No. EP mesti tidak melebihi :max aksara.',
                'IT_AKTA.required' => 'Ruangan Akta diperlukan.',
                'IT_SUBAKTA.required' => 'Ruangan Jenis Kesalahan diperlukan.',
            ]
        );


        if ($validator->fails()) {
            return response()->json(['fails' => $validator->getMessageBag()]);
        } else {
            return response()->json(['success']);
        }
    }

    public function AjaxvalidateEditkes(Request $request)
    {
        $Ip = $request->IT_IPNO;

        $validator = Validator::make($request->all(),
            [
//                'IT_IPNO' => 'required|max:20',
                'IT_IPNO' => 'required_without:IT_EPNO|max:20|both_not_filled:'.$request->get('IT_EPNO'),
                'IT_EPNO' => 'required_without:IT_IPNO|max:20|both_not_filled:'.$request->get('IT_IPNO'),
                'IT_AKTA' => 'required',
                'IT_SUBAKTA' => 'required',
            ],
            [
//                'IT_IPNO.required' => 'Ruangan No. Kertas Siasatan / EP diperlukan.',
                'IT_IPNO.required_without' => 'Sila isikan salah satu maklumat berikut: No. IP / No. EP',
                'IT_EPNO.required_without' => 'Sila isikan salah satu maklumat berikut: No. IP / No. EP',
                'IT_IPNO.both_not_filled' => ' Sila isikan salah satu maklumat berikut: No. IP / No. EP',
                'IT_EPNO.both_not_filled' => ' Sila isikan salah satu maklumat berikut: No. IP / No. EP',
                'IT_IPNO.max' => 'Ruangan No. IP mesti tidak melebihi :max aksara.',
                'IT_EPNO.max' => 'Ruangan No. EP mesti tidak melebihi :max aksara.',
                'IT_AKTA.required' => 'Ruangan Akta diperlukan.',
                'IT_SUBAKTA.required' => 'Ruangan Jenis Kesalahan diperlukan.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['fails' => $validator->getMessageBag()]);
        } else {
            return response()->json(['success']);
        }
    }

}

?>
