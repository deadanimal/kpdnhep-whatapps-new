<?php
namespace App\Http\Controllers\Aduan;

use App\Aduan\GabungRelation;
use App\Aduan\Penyiasatan;
use App\Aduan\PenyiasatanDetail;
use App\Aduan\PenyiasatanDoc;
use App\Aduan\PenyiasatanKes;
use App\Agensi;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\Letter;
use App\Mail\RujukAgensiLain;
use App\Mail\SiasatLuarBidang;
use App\Mail\SiasatMaklumatTakLengkap;
use App\Models\Cases\CaseReasonTemplate;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Image;
use PDF;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class SiasatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('aduan.siasat.index');
    }

    public function GetDataTable(Request $request)
    {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mSiasat = Penyiasatan::where([
            ['CA_CASEID', '<>', null],
            ['CA_INVSTS', '=', 2],
            ['CA_BRNCD', '=', Auth::user()->brn_cd],
            ['CA_INVBY', '=', Auth::user()->id],
        ])
        // ->orderBy('CA_RCVDT', 'DESC')
        ;

        if ($request->mobile) {
            return response()->json(['data' => $mSiasat->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = Datatables::of($mSiasat)
            ->addIndexColumn()
            ->editColumn('CA_CASEID', function (Penyiasatan $penugasan) {
//                    return view('aduan.siasat.show_summary_link', compact('siasat'))->render();
                return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
            })
            ->editColumn('CA_INVSTS', function (Penyiasatan $Siasat) {
                if ($Siasat->CA_INVSTS != '')
                    return $Siasat->statusAduan->descr;
                else
                    return '';
            })
            ->editColumn('CA_RCVDT', function (Penyiasatan $Siasat) {
                if ($Siasat->CA_RCVDT != '')
                    return date('d-m-Y', strtotime($Siasat->CA_RCVDT));
                else
                    return '';
            })
            ->editColumn('CA_SUMMARY', function (Penyiasatan $Siasat) {
                if ($Siasat->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $Siasat->CA_SUMMARY), 0, 10)) . ' ...';
                else
                    return '';
            })
            ->addColumn('tempoh', function (Penyiasatan $Siasat) use ($TempohPertama, $TempohKedua, $TempohKetiga) {
//                    return $Siasat->GetDuration($Siasat->CA_RCVDT, $Siasat->CA_CASEID);
                $totalDuration = $Siasat->GetDuration($Siasat->CA_RCVDT, $Siasat->CA_CASEID);
                if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                    return '<div style="background-color:#3F6; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                    return '<div style="background-color:#FF3;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                    return '<div style="background-color:#F0F; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohKetiga->code)
                    return '<div style="background-color:#F00; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
            })
            ->addColumn('count_duration', function (Penyiasatan $Siasat) {
                $count_duration = $Siasat->GetDuration($Siasat->CA_ASGDT, $Siasat->CA_CASEID);
                return '<div align="center"><strong>' . $count_duration . '</strong></div>';
            })
            ->addColumn('action', '<a href="{{ route("siasat.edit", $CA_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
            ->rawColumns(['CA_CASEID', 'tempoh', 'count_duration', 'action'])
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
            });
//        if ($CA_CASEID = $datatables->request->get('CA_CASEID')) {
//            $datatables->where('CA_CASEID', 'LIKE', "%$CA_CASEID%");
//        }
//        if ($CA_SUMMARY = $datatables->request->get('CA_SUMMARY')) {
//            $datatables->where('CA_SUMMARY', 'LIKE', "%$CA_SUMMARY%");
//        }
//        if ($CA_NAME = $datatables->request->get('CA_NAME')) {
//            $datatables->where('CA_NAME', 'LIKE', "%$CA_NAME%");
//        }
//        if ($CA_INVSTS = $datatables->request->get('CA_INVSTS')) {
//        $datatables->where('CA_INVSTS', 'LIKE', "%$CA_INVSTS%");
//        }
//         if ($CA_RCVDT = $datatables->request->get('CA_RCVDT')) {
//            $datatables->whereDate('CA_RCVDT', Carbon::parse($CA_RCVDT)->format('Y-m-d'));
//        }

        return $datatables->make(true);
    }

    public function PenyiasatanAduan($CA_CASEID)
    {

        $mUser = User::find(Auth::User()->id);
        $mSiasat = Penyiasatan::where(['CA_CASEID' => $CA_CASEID])->first();
        return view('aduan.Penyiasatan.penyiasatan_aduan', compact('mUser', 'mSiasat'));


    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {

    }

    public function store(Request $CA_CASEID)
    {
        $mSiasatDetail = new SiasatDetail;
        $mSiasatDetail->CD_DESC = request('CD_DESC');
    }

    public function show($CA_CASEID)
    {
    }

    public function edit($CA_CASEID)
    {
        $mSiasat = Penyiasatan::where('CA_CASEID', $CA_CASEID)->first();
        if (empty($mSiasat)) {
            return redirect()->route('siasat.index');
        }
        $mAttachSiasatCount = DB::table('case_doc')
            ->where(['CC_CASEID' => $CA_CASEID, 'CC_IMG_CAT' => 2])
            ->count('CC_CASEID');
        $mKesSiasatCount = DB::table('case_act')
            ->where(['CT_CASEID' => $CA_CASEID])
            ->count('CT_CASEID');
        $AttachmentCount = DB::table('case_doc')
            ->where('CC_CASEID', $CA_CASEID)
            ->where('CC_IMG_CAT', '<>', 2)
            ->count('CC_CASEID');
        $mSiasatDetail = PenyiasatanDetail::where(['CD_CASEID' => $CA_CASEID, 'CD_INVSTS' => 2])->first();
        $mGabungOne = DB::table('case_rel')->where(['CR_CASEID' => $CA_CASEID])->first();
        $countDurationReceivedIndicator = false;
        if ($mGabungOne) {
            $mGabungAll = DB::table('case_rel')->where(['CR_RELID' => $mGabungOne->CR_RELID])->get();
            foreach($mGabungAll as $mergeRow) {
                $caseInfo[$mergeRow->CR_CASEID] = Penyiasatan::where('CA_CASEID', $mergeRow->CR_CASEID)->first();
                $countDurationReceived[$mergeRow->CR_CASEID] = Penyiasatan::GetDuration($caseInfo[$mergeRow->CR_CASEID]->CA_RCVDT, $mergeRow->CR_CASEID);
                $countDuration[$mergeRow->CR_CASEID] = Penyiasatan::GetDuration($caseInfo[$mergeRow->CR_CASEID]->CA_ASGDT, $mergeRow->CR_CASEID);
                if($countDurationReceived[$mergeRow->CR_CASEID] >= 22) {
                    $countDurationReceivedIndicator = true;
                }
            }
        } else {
            $mGabungAll = '';
            $countDurationReceived = Penyiasatan::GetDuration($mSiasat->CA_RCVDT, $mSiasat->CA_CASEID);
            $countDuration = Penyiasatan::GetDuration($mSiasat->CA_ASGDT, $mSiasat->CA_CASEID);
            if($countDurationReceived >= 22) {
                $countDurationReceivedIndicator = true;
            }
        }
        $caseReasonTemplates = CaseReasonTemplate::where(['category' => 'AD52', 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code');
        return view('aduan.siasat.edit', compact('mSiasat', 'AttachmentCount', 'mKesSiasatCount', 'mSiasatDetail', 'mAttachSiasatCount', 'mGabungAll', 'countDuration', 'countDurationReceived', 'countDurationReceivedIndicator', 'caseReasonTemplates'));
    }

    public function GetSubAkta($Akta)
    {
        $mRef = DB::table('sys_ref')->where(['cat' => '714', 'status' => '1'])->where('code', 'LIKE', "{$Akta}%")->orderBy('sort', 'asc')->orderBy('descr', 'asc')->pluck('code', 'descr');

        $mRef->prepend('', '-- SILA PILIH --');
        return $mRef;
    }

    public function update(Request $request, $CASEID)
    {
//        dd($request);
        if ($request->hantar) {
            if ($request->CA_INVSTS == '3' && $request->CA_SSP == 'YES') {
                // $this->validate($request, [
                $v = Validator::make($request->all(), [
                    'CA_AREACD' => 'required',
                    'CA_INVSTS' => 'required',
                    'CA_MAGNCD' => 'required_if:CA_INVSTS,4',
                    'CA_RESULT' => 'required_unless:CA_INVSTS,7',
                    'CA_RECOMMEND' => 'required_unless:CA_INVSTS,7',
                    'CA_ANSWER' => 'required',
                    'CA_SSP' => 'required_if:CA_INVSTS,3',
                    'countakta' => 'required_if:CA_INVSTS,2|required_if:CA_SSP,YES|min:1|numeric',
                    // 'CA_IPNO' => 'required_if:CA_SSP,YES',
                    // 'CA_AKTA' => 'required_if:CA_SSP,YES',
                    // 'CA_SUBAKTA' => 'required_if:CA_SSP,YES',
                ],
                [
                    'CA_AREACD.required' => 'Ruangan Kawasan Kes diperlukan.',
                    'CA_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
                    'CA_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
                    'CA_RESULT.required_unless' => 'Ruangan Hasil Siasatan diperlukan.',
                    'CA_RECOMMEND.required_unless' => 'Ruangan Saranan diperlukan.',
                    'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
                    'CA_SSP.required_if' => 'Ruangan Lanjutan Siasatan (IP / EP) diperlukan.',
                    'countakta.min' => 'Jumlah Akta mesti sekurang-kurangnya :min.',
                    'CD_REASON.required' => 'Ruangan Alasan diperlukan.',
                    'CD_REASON_DATE_FROM.required' => 'Ruangan Tarikh Dari diperlukan.',
                    'CD_REASON_DATE_TO.required' => 'Ruangan Tarikh Hingga diperlukan.',
                    // 'CA_IPNO.required_if' => 'Ruangan No.IP diperlukan.',
                    // 'CA_AKTA.required_if' => 'Ruangan Akta diperlukan.',
                    // 'CA_SUBAKTA.required_if' => 'Ruangan Kod Akta diperlukan.',
                ]);
            } else {
                // $this->validate($request, [
                $v = Validator::make($request->all(), [
                    'CA_AREACD' => 'required',
                    'CA_INVSTS' => 'required',
                    'CA_MAGNCD' => 'required_if:CA_INVSTS,4',
                    'CA_RESULT' => 'required_unless:CA_INVSTS,7',
                    'CA_RECOMMEND' => 'required_unless:CA_INVSTS,7',
                    'CA_ANSWER' => 'required',
                    'CA_SSP' => 'required_if:CA_INVSTS,3',
                    // 'CA_IPNO' => 'required_if:CA_SSP,YES',
                    // 'CA_AKTA' => 'required_if:CA_SSP,YES',
                    // 'CA_SUBAKTA' => 'required_if:CA_SSP,YES',
                ],
                [
                    'CA_AREACD.required' => 'Ruangan Kawasan Kes diperlukan.',
                    'CA_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
                    'CA_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
                    'CA_RESULT.required_unless' => 'Ruangan Hasil Siasatan diperlukan.',
                    'CA_RECOMMEND.required_unless' => 'Ruangan Saranan diperlukan.',
                    'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
                    'CA_SSP.required_if' => 'Ruangan Lanjutan Siasatan (IP / EP) diperlukan.',
                    'CD_REASON.required' => 'Ruangan Alasan diperlukan.',
                    'CD_REASON_DATE_FROM.required' => 'Ruangan Tarikh Dari diperlukan.',
                    'CD_REASON_DATE_TO.required' => 'Ruangan Tarikh Hingga diperlukan.',
                    // 'CA_IPNO.required_if' => 'Ruangan No.IP diperlukan.',
                    // 'CA_AKTA.required_if' => 'Ruangan Akta diperlukan.',
                    // 'CA_SUBAKTA.required_if' => 'Ruangan Kod Akta diperlukan.',
                ]);
            }
            $countDurationIndicator = false;
            if(is_array($request->countDurationReceived)) {
                $arrayCount = $request->countDurationReceived;
                foreach($arrayCount as $key => $value) {
                    if($request->countDurationReceived[$key] >= 22) {
                        $countDurationIndicator = true;
                    }
                    if($countDurationIndicator) {
                        $v->sometimes('CD_REASON', 'required', function ($input) use ($key) {
                            return !empty($input->CA_INVSTS);
                        });
                        $v->sometimes(['CD_REASON_DATE_FROM', 'CD_REASON_DATE_TO'], 'required', function ($input) use ($key) {
                            return $input->CD_REASON == 'S2' && !empty($input->CA_INVSTS);
                        });
                    }
                }
            } else {
                $v->sometimes('CD_REASON', 'required', function ($input) {
                    return $input->countDurationReceived >= 22 && !empty($input->CA_INVSTS);
                });
                $v->sometimes(['CD_REASON_DATE_FROM', 'CD_REASON_DATE_TO'], 'required', function ($input) {
                    return $input->countDurationReceived >= 22 && $input->CD_REASON == 'S2' && !empty($input->CA_INVSTS);
                });
            }
            $v->validate();
        }

        if ($request->hantar) {
            $CheckRelation = GabungRelation::where('CR_CASEID', $CASEID)->value('CR_RELID');

            if ($CheckRelation) {
                $ArrCaseId = GabungRelation::where('CR_RELID', $CheckRelation)->pluck('CR_CASEID');
                foreach ($ArrCaseId as $GetCaseId) {
                    $Save = $this->Save($request, $GetCaseId);
                }
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Aduan telah berjaya dihantar']);
                }
                return redirect()->route('siasat.index')->with('success', 'Aduan telah berjaya dihantar');
            } else {
                $Save = $this->Save($request, $CASEID);
                if ($Save) {
                    if ($request->expectsJson()) {
                        return response()->json(['message' => 'Aduan telah berjaya dihantar']);
                    }
                    return redirect()->route('siasat.index')->with('success', 'Aduan telah berjaya dihantar');
                } else
                    return back()->with('alert', 'Masalah Teknikal');
            }
        } else {
            $mSiasat = Penyiasatan::find($CASEID);
            $mSiasat->fill($request->all());
            $mSiasat->CA_COMPLETEDT = null;
            $mSiasat->CA_INVSTS = '2';
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
            'CA_AREACD' => 'required',
            'CA_INVSTS' => 'required',
            'CA_MAGNCD' => 'required_if:CA_INVSTS,4',
            'CA_RESULT' => 'required',
            'CA_RECOMMEND' => 'required',
            'CA_ANSWER' => 'required',
            'CA_SSP' => 'required',
            'CA_IPNO' => 'required_if:CA_SSP,YES',
            'CA_AKTA' => 'required_if:CA_SSP,YES',
            'CA_SUBAKTA' => 'required_if:CA_SSP,YES',
        ],
            [
                'CA_AREACD.required' => 'Ruangan Kawasan Kes diperlukan.',
                'CA_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
                'CA_MAGNCD.required_if' => 'Ruangan Kementerian/Agensi diperlukan.',
                'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan.',
                'CA_RECOMMEND.required' => 'Ruangan Saranan diperlukan.',
                'CA_ANSWER.required' => 'Ruangan Jawapan Pada Pengadu diperlukan.',
                'CA_SSP.required' => 'Ruangan Kes SPP diperlukan.',
                'CA_IPNO.required_if' => 'Ruangan No.IP diperlukan.',
                'CA_AKTA.required_if' => 'Ruangan Akta diperlukan.',
                'CA_SUBAKTA.required_if' => 'Ruangan Kod Akta diperlukan.',
            ]);

        $mSiasat = Penyiasatan::find($CASEID);
        $mSiasat->fill($request->all());
        $mSiasat->CA_COMPLETEDT = null;
        $mSiasat->CA_INVSTS = '2';
        if ($mSiasat->save()) {
//                return redirect()->route('siasat.edit')->with('success','Aduan telah berjaya dihantar');
            return redirect()->route('siasat.edit#mak_aduan', $mSiasat->$CASEID);
        }
    }

    public function Save($request, $CASEID)
    {
        $mSiasat = Penyiasatan::find($CASEID);
        $mSiasat->fill($request->all());
        $mSiasat->CA_INVSTS = $request->CA_INVSTS;
        if ($request->CA_INVSTS == '3') { // Siasatan Selesai
            $mSiasat->CA_COMPLETEBY = Auth::user()->id;
            $mSiasat->CA_COMPLETEDT = Carbon::now();
        } elseif ($request->CA_INVSTS == '7') { // Maklumat Tidak Lengkap
            $mSiasat->CA_COMPLETEDT = null;
            if ($mSiasat->CA_EMAIL) {
                try {
                    Mail::to($mSiasat->CA_EMAIL)->send(new SiasatMaklumatTakLengkap($mSiasat));
                } catch (Exception $e) {
                    
                }
            }
        } elseif ($request->CA_INVSTS == '8') { // Penutupan (Diluar Bidang Kuasa)
            $mSiasat->CA_COMPLETEDT = null;
            if ($mSiasat->CA_EMAIL) {
                try {
                    Mail::to($mSiasat->CA_EMAIL)->send(new SiasatLuarBidang($mSiasat));
                } catch (Exception $e) {
                    
                }
            }
            // Auto update CA_COMPLETEBY dan CA_COMPLETEDT selepas 7 hari
        } elseif ($request->CA_INVSTS == '4') { // Penutupan (Rujuk Agensi Di Bawah KPDNKK)
            $mSiasat->CA_COMPLETEDT = null;
            $mAgensi = Agensi::where(['MI_MINCD' => $mSiasat->CA_MAGNCD, 'MI_STS' => '1'])->first();
            // if ($mAgensi->MI_EMAIL) {
            //     Mail::to($mAgensi->MI_EMAIL)->send(new RujukAgensiLain($mSiasat));
            // }
        } else {
            $mSiasat->CA_COMPLETEBY = Auth::user()->id;
            $mSiasat->CA_COMPLETEDT = Carbon::now();
            $mSiasat->CA_CLOSEDT = Carbon::now();
        }
//        $mSiasat->CA_INVDT = Carbon::now();
        if ($mSiasat->save()) {

            $SuratPengadu = Letter::where(['letter_code' => $request->CA_INVSTS, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
            if (!empty($SuratPengadu))
                $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
            $ProfilPegawai = User::find($mSiasat->CA_INVBY);

            if (!empty($mSiasat->CA_STATECD)) {
                $StateNm = DB::table('sys_ref')->select('descr')->where(['cat' => '17', 'code' => $mSiasat->CA_STATECD])->first();
                if (!$StateNm) {
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat' => '334', 'code' => $mSiasat->CA_STATECD])->first();
                }
                $CA_STATECD = $StateNm->descr;
            } else {
                $CA_STATECD = '';
            }
            if (!empty($mSiasat->CA_DISTCD)) {
                $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat' => '18', 'code' => $mSiasat->CA_DISTCD])->first();
                if (!$DestrictNm) {
                    $CA_DISTCD = $mSiasat->CA_DISTCD;
                } else {
                    $CA_DISTCD = $DestrictNm->descr;
                }
            } else {
                $CA_DISTCD = '';
            }

            if ($mSiasat->CA_INVSTS == '3') // Siasatan Selesai
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
                $patternsPengadu[10] = "#TARIKHSIASATANSELESAI#";
                $replacementsPengadu[1] = $mSiasat->CA_NAME;
                $replacementsPengadu[2] = $mSiasat->CA_ADDR != '' ? $mSiasat->CA_ADDR : '';
                $replacementsPengadu[3] = $mSiasat->CA_POSCD != '' ? $mSiasat->CA_POSCD : '';
                $replacementsPengadu[4] = $CA_DISTCD;
                $replacementsPengadu[5] = $CA_STATECD;
                $replacementsPengadu[6] = $CASEID;
                $replacementsPengadu[7] = $mSiasat->CA_ANSWER;
                $replacementsPengadu[8] = $ProfilPegawai->name;
                $replacementsPengadu[9] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[10] = Carbon::now()->format('d/m/Y');
            } elseif ($mSiasat->CA_INVSTS == '4') // RUJUK KE KEMENTERIAN/AGENSI LAIN
            {
                $mAgensi = DB::table('sys_min')->select('*')->where('MI_MINCD', $mSiasat->CA_MAGNCD)->first();
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
                    . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $mAgensi->MI_POSCD . ' '
                    . \App\Ref::GetDescr('18', $mAgensi->MI_DISTCD, 'ms') . '<br />'
                    . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . \App\Ref::GetDescr('17', $mAgensi->MI_STATECD, 'ms') : '';
                $replacementsPengadu[3] = $CASEID;
                $replacementsPengadu[4] = $mSiasat->CA_SUMMARY;
                $replacementsPengadu[5] = $mSiasat->CA_ANSWER;
                $replacementsPengadu[6] = $ProfilPegawai->name;
                $replacementsPengadu[7] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[8] = $mSiasat->CA_NAME;
                $replacementsPengadu[9] = $mSiasat->CA_ADDR != '' ? $mSiasat->CA_ADDR : '';
                $replacementsPengadu[10] = $mSiasat->CA_POSCD != '' ? $mSiasat->CA_POSCD : '';
                $replacementsPengadu[11] = $CA_DISTCD;
                $replacementsPengadu[12] = $CA_STATECD;
                $replacementsPengadu[13] = !empty($mAgensi) ? $mAgensi->MI_TELNO : '';
                $replacementsPengadu[14] = !empty($mAgensi) ? $mAgensi->MI_EMAIL : '';
                $replacementsPengadu[15] = Carbon::now()->format('d/m/Y');
                if ($mAgensi->MI_EMAIL) {
                    try {
                        if (App::environment(['production'])) {
                            Mail::to($mAgensi->MI_EMAIL)
                                ->cc($ProfilPegawai->email)
                                ->send(new RujukAgensiLain($mSiasat));
                        } else {
                            Mail::to($ProfilPegawai->email)
                                ->cc($ProfilPegawai->email)
                                ->send(new RujukAgensiLain($mSiasat));
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            } elseif ($mSiasat->CA_INVSTS == '5') // Rujuk Ke Tribunal
            {
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[8] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $patternsPengadu[9] = "#TARIKHRUJUKTRIBUNAL#";
                $replacementsPengadu[1] = $mSiasat->CA_NAME;
                $replacementsPengadu[2] = $mSiasat->CA_ADDR != '' ? $mSiasat->CA_ADDR : '';
                $replacementsPengadu[3] = $mSiasat->CA_POSCD != '' ? $mSiasat->CA_POSCD : '';
                $replacementsPengadu[4] = $CA_DISTCD;
                $replacementsPengadu[5] = $CA_STATECD;
                $replacementsPengadu[6] = $CASEID;
                $replacementsPengadu[7] = $ProfilPegawai->name;
                $replacementsPengadu[8] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[9] = Carbon::now()->format('d/m/Y');
            } elseif ($mSiasat->CA_INVSTS == '6') // Pertanyaan
            {

            } elseif ($mSiasat->CA_INVSTS == '7') // Maklumat Tidak Lengkap
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
                $replacementsPengadu[1] = $mSiasat->CA_NAME;
                $replacementsPengadu[2] = $mSiasat->CA_ADDR != '' ? $mSiasat->CA_ADDR : '';
                // $replacementsPengadu[3] = $mPenugasan->CA_POSCD != ''? $mPenugasan->CA_POSCD : '';
                $replacementsPengadu[3] = $CA_DISTCD;
                $replacementsPengadu[4] = $CA_STATECD;
                $replacementsPengadu[5] = $CASEID;
                $replacementsPengadu[6] = $mSiasat->CA_ANSWER;
                $replacementsPengadu[7] = $ProfilPegawai->name;
                $replacementsPengadu[8] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[9] = Carbon::now()->format('d/m/Y');
                /* if($mSiasat->CA_EMAIL){
                    Mail::to($mSiasat->CA_EMAIL)->send(new MaklumatAduanTaklengkap($mSiasat));
                } */
            } elseif ($mSiasat->CA_INVSTS == '8') // Diluar Bidang Kuasa
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
                $replacementsPengadu[1] = $mSiasat->CA_NAME;
                $replacementsPengadu[2] = $mSiasat->CA_ADDR != '' ? $mSiasat->CA_ADDR : '';
                $replacementsPengadu[3] = $mSiasat->CA_POSCD != '' ? $mSiasat->CA_POSCD : '';
                $replacementsPengadu[4] = $CA_DISTCD;
                $replacementsPengadu[5] = $CA_STATECD;
                $replacementsPengadu[6] = $CASEID;
                $replacementsPengadu[7] = $ProfilPegawai->name;
                $replacementsPengadu[8] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' '
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />'
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[9] = Carbon::now()->format('d/m/Y');
            }

            $date = date('YmdHis');
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
            }

            $mOldSiasatDetail = PenyiasatanDetail::where('CD_CURSTS', 1)->first();
            if ($mOldSiasatDetail) {
                $mOldSiasatDetail->CD_CURSTS = '0';
                $mOldSiasatDetail->save();
            }
            PenyiasatanDetail::where(['CD_CASEID' => $CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
            $mNewSiasatDetail = new PenyiasatanDetail();
            $mNewSiasatDetail->CD_CASEID = $CASEID;
            if ($request->expectsJson()) {
                $mNewSiasatDetail->CD_TYPE = 'S'; // EZSTAR
            } else {
                $mNewSiasatDetail->CD_TYPE = 'D';
            }
            $mNewSiasatDetail->CD_DESC = $mSiasat->CA_RECOMMEND;
            $mNewSiasatDetail->CD_INVSTS = $mSiasat->CA_INVSTS;
            $mNewSiasatDetail->CD_CASESTS = $mSiasat->CA_CASESTS;
            $mNewSiasatDetail->CD_CURSTS = 1;
            $mNewSiasatDetail->CD_DOCATTCHID_PUBLIC = $SuratPengaduId;
            if(is_array($request->CD_REASON_DURATION)) {
                $mNewSiasatDetail->CD_REASON_DURATION = $request->CD_REASON_DURATION["'$CASEID'"];
            } else {
                $mNewSiasatDetail->CD_REASON_DURATION = $request->CD_REASON_DURATION;
            }
            $mNewSiasatDetail->CD_REASON = $request->CD_REASON;
            if($request->CD_REASON == 'S2'){
                $mNewSiasatDetail->CD_REASON_DATE_FROM = Carbon::parse($request->CD_REASON_DATE_FROM);
                $mNewSiasatDetail->CD_REASON_DATE_TO = Carbon::parse($request->CD_REASON_DATE_TO);
            }
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
        $mSiasatDoc = PenyiasatanDoc::where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 2])->get();

        if ($request->mobile) {
            return response()->json(['data' => $mSiasatDoc->toArray()]);
        }
        $datatables = Datatables::of($mSiasatDoc)
            ->addIndexColumn()
            ->editColumn('CC_IMG_NAME', function (PenyiasatanDoc $PenyiasatanDoc) {
                if ($PenyiasatanDoc->CC_IMG_NAME != '')
                    return '<a href=' . Storage::disk('bahanpath')->url($PenyiasatanDoc->CC_PATH . $PenyiasatanDoc->CC_IMG) . ' target="_blank">' . $PenyiasatanDoc->CC_IMG_NAME . '</a>';
                else
                    return '';
            })
            ->addColumn('action', function (PenyiasatanDoc $PublicCaseDoc) {
                return view('aduan.siasat.attach_siasat_action_btn', compact('PublicCaseDoc'))->render();
            })
            ->rawColumns(['action', 'CC_IMG_NAME']);

        return $datatables->make(true);
    }


    public function CreateAttachSiasat($CASEID)
    {
        return view('aduan.siasat.create_attach_siasat', compact('CASEID'));
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

        if ($file) {
            $filename = $userid . '_' . $request->CC_CASEID . '_' . $date . '.' . $file->getClientOriginalExtension();
            $directory = '/' . $Year . '/' . $Month . '/';

            if ($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $resize->stream();
                Storage::disk('bahan')->put($directory . $filename, $resize);
            } else {
                Storage::disk('bahan')->putFileAs('/' . $Year . '/' . $Month . '/', $request->file('file'), $filename);
            }

            $mPublicCaseDoc = new \App\Aduan\PublicCaseDoc();
            $mPublicCaseDoc->CC_CASEID = $request->CC_CASEID;
            $mPublicCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mPublicCaseDoc->CC_IMG = $filename;
            $mPublicCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mPublicCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mPublicCaseDoc->CC_IMG_CAT = 2;
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
        $mPublicCaseDoc = PenyiasatanDoc::find($id);
        return view('aduan.siasat.edit_attach_siasat', compact('mPublicCaseDoc'));
    }

    public function UpdateAttachSiasat(Request $request, $id)
    {
        $mPublicCaseDoc = PenyiasatanDoc::find($id);

        $file = $request->file('file');
        $date = date('Ymdhis');
        $userid = Auth()->user()->id;
        $Year = date('Y');
        $Month = date('m');

        if ($file) {
            Storage::delete($mPublicCaseDoc->CC_PATH . $mPublicCaseDoc->CC_IMG); // Delete old attachment
            $filename = $userid . '_' . $mPublicCaseDoc->CC_CASEID . '_' . $date . '.' . $file->getClientOriginalExtension(); // Store new attachment
            $directory = '/' . $Year . '/' . $Month . '/';

            if ($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $resize->stream();
                Storage::disk('bahan')->put($directory . $filename, $resize);
            } else {
                Storage::disk('bahan')->putFileAs('/' . $Year . '/' . $Month . '/', $request->file('file'), $filename);
            }
            // Update record
            $mPublicCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mPublicCaseDoc->CC_IMG = $filename;
            $mPublicCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mPublicCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            // Save record
            if ($mPublicCaseDoc->save()) {
                return response()->json(['success']);
//                return redirect()->back();
            }
        } else {
            $mPublicCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            if ($mPublicCaseDoc->save()) {
                return response()->json(['success']);
//                return redirect()->back();
            }
        }
    }

    public function DestroyAttachSiasat($id)
    {
        $model = \App\Aduan\PublicCaseDoc::find($id);
        Storage::delete($model->CC_PATH . $model->CC_IMG);
        if ($model->delete()) {
            return response()->json(['success']);
//            return redirect()->route('siasat.edit',$model->CC_CASEID);
        }
    }

    public function getKesSiasat(Request $request, $CASEID)
    {
        $mSiasatKes = PenyiasatanKes::where(['CT_CASEID' => $CASEID])->get();

        if ($request->mobile) {
            return response()->json(['data' => $mSiasatKes->toArray()]);
        }
        $datatables = Datatables::of($mSiasatKes)
            ->addIndexColumn()
            ->editColumn('CT_SUBAKTA', function (PenyiasatanKes $Siasatkes) {
                if ($Siasatkes->CT_SUBAKTA != '')
                    return $Siasatkes->SubAkta->descr;
                else
                    return '';
            })
            ->editColumn('CT_AKTA', function (PenyiasatanKes $Siasatkes) {
                if ($Siasatkes->CT_AKTA != '')
                    return $Siasatkes->Akta->descr;
                else
                    return '';
            })
            ->addColumn('action', function (PenyiasatanKes $SiasatKes) {
                return view('aduan.siasat.kes_siasat_action_btn', compact('SiasatKes'))->render();
            })//
        ;

        return $datatables->make(true);
    }

    public function CreateKesSiasat($CASEID)
    {
        return view('aduan.siasat.create_kes_siasat', compact('CASEID'));
    }

    public function storekessiasat($CASEID, Request $request)
    {
        $mKes = new PenyiasatanKes;
        $mKes->fill($request->all());
        $mKes->CT_CASEID = $CASEID;
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
        $SiasatKes = PenyiasatanKes::find($id);
        return view('aduan.siasat.edit_kes_siasat', compact('SiasatKes'));
    }

    public function UpdateKesSiasat($id, Request $request)
    {
        $mKes = PenyiasatanKes::find($id);
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
        $mKes = PenyiasatanKes::find($id);
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
                return '<div style="max-height:80px; overflow:auto">' . $mCaseRelation->aduan->CA_SUMMARY . '</div>';
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
            ->editColumn('CA_INVSTS', function (PenyiasatanDetail $SiasatDetail) {
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

    public function destroy($CA_CASEID)
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

    public function pdf($CA_CASEID)
    {
        $mSiasat = Penyiasatan::find($CA_CASEID);
        $data = [
            'mSiasat' => $mSiasat,
        ];
        $pdf = PDF::loadView('aduan.Penyiasatan.siasatpdf', $data);
        return $pdf->stream('document.pdf');
    }

    public function ShowSummary($CASEID)
    {
        $model = Penyiasatan::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PenyiasatanDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenyiasatanDoc::where(['CC_CASEID' => $CASEID])->get();
        $kes = PenyiasatanKes::where(['CT_CASEID' => $CASEID])->get();
        return view('aduan.siasat.show_summary_modal', compact('model', 'trnsksi', 'kes', 'img'));

//        $model = Penyiasatan::where(['CA_CASEID' => $CASEID])->first();
//        return view('aduan.siasat.show_summary_modal', compact('model'));
    }


    public function PrintSummary($CASEID)
    {
        $model = Penyiasatan::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PenyiasatanDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenyiasatanDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('aduan.siasat.show_summary_modal', compact('model', 'trnsksi', 'img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }

    public function AjaxValidateKes(Request $request)
    {
        $Ip = $request->CT_IPNO;


        $validator = Validator::make($request->all(),
            [
//                'CT_IPNO' => 'required|max:20',
//                'CT_IPNO' => 'required_without_all:CT_EPNO|max:20',
//                'CT_EPNO' => 'required_without_all:CT_IPNO|max:20',
                'CT_IPNO' => 'max:20|required_without:CT_EPNO|both_not_filled:'.$request->get('CT_EPNO'),
                'CT_EPNO' => 'max:20|required_without:CT_IPNO|both_not_filled:'.$request->get('CT_IPNO'),
                'CT_AKTA' => 'required',
                'CT_SUBAKTA' => 'required',
            ],
            [
//                'CT_IPNO.required' => 'Ruangan No. Kertas Siasatan / EP diperlukan.',
                'CT_IPNO.required_without' => ' Sila isikan salah satu maklumat berikut: No. Kertas Siasatan / No. EP',
                'CT_EPNO.required_without' => ' Sila isikan salah satu maklumat berikut: No. Kertas Siasatan / No. EP',
                'CT_IPNO.both_not_filled' => ' Sila isikan salah satu maklumat berikut: No. Kertas Siasatan / No. EP',
                'CT_EPNO.both_not_filled' => ' Sila isikan salah satu maklumat berikut: No. Kertas Siasatan / No. EP',
                'CT_IPNO.max' => 'Ruangan No. Kertas Siasatan / EP mesti tidak melebihi :max aksara.',
                'CT_EPNO.max' => 'Ruangan No. EP mesti tidak melebihi :max aksara.',
                'CT_AKTA.required' => 'Ruangan Akta diperlukan.',
                'CT_SUBAKTA.required' => 'Ruangan Jenis Kesalahan diperlukan.',
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
        $Ip = $request->CT_IPNO;

        $validator = Validator::make($request->all(),
            [
//                'CT_IPNO' => 'required|max:20',
                'CT_IPNO' => 'required_without_all:CT_EPNO|max:20',
                'CT_EPNO' => 'required_without_all:CT_IPNO|max:20',
                'CT_AKTA' => 'required',
                'CT_SUBAKTA' => 'required',
            ],
            [
//                'CT_IPNO.required' => 'Ruangan No. Kertas Siasatan / EP diperlukan.',
                'CT_IPNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: No. Kertas Siasatan / No. EP',
                'CT_EPNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: No. Kertas Siasatan / No. EP',
                'CT_IPNO.max' => 'Ruangan No. Kertas Siasatan / EP mesti tidak melebihi :max aksara.',
                'CT_EPNO.max' => 'Ruangan No. EP mesti tidak melebihi :max aksara.',
                'CT_AKTA.required' => 'Ruangan Akta diperlukan.',
                'CT_SUBAKTA.required' => 'Ruangan Jenis Kesalahan diperlukan.',
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
