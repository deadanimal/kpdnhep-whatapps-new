<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\PenyiasatanKes;
use App\Agensi;
use App\Attachment;
use App\Events\ComplaintReceived;
use App\Events\TaskAssigned;
use App\Http\Controllers\Controller;
use App\Letter;
use App\Mail\AduanLuarBidang;
use App\Mail\MaklumatAduanTaklengkap;
use App\Mail\RujukAgensiLain;
use App\Models\Cases\CaseReasonTemplate;
use App\Penugasan;
use App\PenugasanCaseDetail;
use App\PenugasanDoc;
use App\Ref;
use App\Repositories\WordGeneratorRepository;
use App\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;
use Validator;
use Yajra\DataTables\DataTables;

class TugasController extends Controller
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
        return view('aduan.tugas.index');
    }
    
    public function getDataTable(Datatables $datatables, Request $request)
    {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mPenugasan = Penugasan::where(['CA_CASESTS'=>1,'CA_BRNCD'=>Auth::user()->brn_cd])->whereIn('CA_INVSTS',[0,1])->orderBy('CA_CREDT', 'DESC');
        $mUser = User::find(Auth::user()->id);
        
        if ($request->mobile) {
            return response()->json(['data' => $mPenugasan->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = Datatables::of($mPenugasan)
                ->addIndexColumn()
                ->addColumn('check', '<input type="checkbox" class="i-checks" name="CASEID[]" value="{{ $CA_CASEID }}" onclick="anyCheck()">')
                ->editColumn('CA_CASEID', function (Penugasan $penugasan) {
                    return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
                })
                ->editColumn('CA_SUMMARY', function(Penugasan $penugasan) {
                        if($penugasan->CA_SUMMARY != '')
                        return implode(' ', array_slice(explode(' ', ucfirst($penugasan->CA_SUMMARY)), 0, 7)).'...';
                        else
                        return '';
                    })
                    ->editColumn('CA_INVSTS', function(Penugasan $penugasan) {
                        if($penugasan->CA_INVSTS != '')
                        return $penugasan->StatusAduan->descr;
                        else
                        return '';
                    })
                    ->editColumn('CA_RCVDT', function(Penugasan $penugasan) {
                        if($penugasan->CA_RCVDT != '')
                        return Carbon::parse($penugasan->CA_RCVDT)->format('d-m-Y h:i A');
                        else
                        return '';
                    })
                    ->addColumn('tempoh', function(Penugasan $penugasan) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
                        $mPindahCaseDetail = PenugasanCaseDetail::
                            where('CD_CASEID', $penugasan->CA_CASEID)
                            ->where('CD_INVSTS', '0')
                            ->orderBy('CD_CREDT', 'desc')
                            ->first();
                        $mReceivedDetail = PenugasanCaseDetail::
                            where('CD_CASEID', $penugasan->CA_CASEID)
                            ->where('CD_INVSTS', '1')
                            ->orderBy('CD_CREDT', 'desc')
                            ->first();
                        if($mPindahCaseDetail){
                            $totalDuration = $penugasan->GetDuration($mPindahCaseDetail->CD_CREDT ?? $mReceivedDetail->CD_CREDT ?? $penugasan->CA_RCVDT, $penugasan->CA_CASEID);
                        } else {
                            $totalDuration = $penugasan->GetDuration($mReceivedDetail->CD_CREDT ?? $penugasan->CA_RCVDT, $penugasan->CA_CASEID);
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
                        <a href="{{ route("tugas.penugasanaduan", $CA_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
                    ')
//                        <a href="{{ url(\'tugas\penugasan_aduan\', $CA_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
                    ->rawColumns(['check','CA_CASEID','action','tempoh'])
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
                    });
                    
//        if ($CA_CASEID = $datatables->request->get('CA_CASEID')) {
//            $datatables->where('CA_CASEID', 'like', "%$CA_CASEID%");
//        }
//        if ($CA_SUMMARY = $datatables->request->get('CA_SUMMARY')) {
//            $datatables->where('CA_SUMMARY', 'like', "%$CA_SUMMARY%");
//        }
//        if ($CA_AGAINSTNM = $datatables->request->get('CA_AGAINSTNM')) {
//            $datatables->where('CA_AGAINSTNM', 'like', "%$CA_AGAINSTNM%");
//        }
//        if ($CA_RCVDT = $datatables->request->get('CA_RCVDT')) {
//            $datatables->where('CA_RCVDT', 'like', "%$CA_RCVDT%");
//        }
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
    
    public function gettransaction($CA_CASEID)
    {
        $mPenugasanDetail = PenugasanCaseDetail::where(['CD_CASEID'=>$CA_CASEID])->orderBy('CD_CREDT', 'DESC');
        
        $datatables = Datatables::of($mPenugasanDetail)
                ->addIndexColumn()
                ->editColumn('CD_INVSTS', function(PenugasanCaseDetail $mCaseDetail) {
                    if($mCaseDetail->CD_INVSTS != '')
                        return $mCaseDetail->statusAduan->descr;
                    else
                        return '';
                })
                ->editColumn('CD_ACTFROM', function(PenugasanCaseDetail $mCaseDetail) {
//                    if($mCaseDetail->CD_ACTFROM != '')
//                    return $mCaseDetail->userDaripada->name;
//                    else
//                    return '';
                    if ($mCaseDetail->CD_ACTFROM != ''){
                        if ($mCaseDetail->userDaripada){
                            return $mCaseDetail->userDaripada->name;
                        } else {
                            return $mCaseDetail->CD_ACTFROM;
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('CD_ACTTO', function(PenugasanCaseDetail $mCaseDetail) {
//                    if($mCaseDetail->CD_ACTTO != '')
//                    return $mCaseDetail->userKepada->name;
//                    else
//                    return '';
                    if ($mCaseDetail->CD_ACTTO != ''){
                        if ($mCaseDetail->userKepada){
                            return $mCaseDetail->userKepada->name;
                        } else {
                            return $mCaseDetail->CD_ACTTO;
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('CD_CREDT', function(PenugasanCaseDetail $mCaseDetail) {
                    if($mCaseDetail->CD_CREDT != '')
                        return Carbon::parse($mCaseDetail->CD_CREDT)->format('d-m-Y h:i A');
                        else
                        return '';
                })
                ->editColumn('CD_DOCATTCHID_PUBLIC', function(PenugasanCaseDetail $mCaseDetail) {
                    if($mCaseDetail->CD_DOCATTCHID_PUBLIC != '')
                        return '<a href='.Storage::disk('letter')->url($mCaseDetail->suratPublic->file_name_sys).' target="_blank">'.$mCaseDetail->suratPublic->file_name.'</a>';
                        else
                        return '';
                })
                ->editColumn('CD_DOCATTCHID_ADMIN', function(PenugasanCaseDetail $mCaseDetail) {
                    if($mCaseDetail->CD_DOCATTCHID_ADMIN != '')
                        return '<a href='.Storage::disk('letter')->url($mCaseDetail->suratAdmin->file_name_sys).' target="_blank">'.$mCaseDetail->suratAdmin->file_name.'</a>';
                        else
                        return '';
                })
                ->rawColumns(['CD_DOCATTCHID_ADMIN','CD_DOCATTCHID_PUBLIC']);
        
        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PenugasanAduan($CA_CASEID)
    {
        $mUser = User::find(Auth::User()->id);
        $mPenugasan = Penugasan::where(['CA_CASEID' => $CA_CASEID])->first();
        $mPenugasanDetail = PenugasanCaseDetail::where(['CD_CASEID' => $CA_CASEID,'CD_INVSTS' => '2'])->first();
        $mBukaSemula = DB::table('case_forward')->where(['CF_FWRD_CASEID' => $CA_CASEID])->first();
        $caseReasonTemplates = CaseReasonTemplate::where(['category' => 'AD51', 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->pluck('descr', 'code');
        $mPindahCaseDetail = PenugasanCaseDetail::
            where('CD_CASEID', $CA_CASEID)
            ->where('CD_INVSTS', '0')
            ->orderBy('CD_CREDT', 'desc')
            ->first();
        $mReceivedDetail = PenugasanCaseDetail::
            where('CD_CASEID', $CA_CASEID)
            ->where('CD_INVSTS', '1')
            ->orderBy('CD_CREDT', 'desc')
            ->first();
        if($mPindahCaseDetail){
            $countDuration = Penugasan::GetDuration($mPindahCaseDetail->CD_CREDT ?? $mReceivedDetail->CD_CREDT ?? $mPenugasan->CA_RCVDT, $CA_CASEID);
        } else {
            $countDuration = Penugasan::GetDuration($mReceivedDetail->CD_CREDT ?? $mPenugasan->CA_RCVDT, $CA_CASEID);
        }
        return view('aduan.tugas.penugasan_aduan', compact('mUser', 'mPenugasan','CA_CASEID','mPenugasanDetail','mBukaSemula','caseReasonTemplates','countDuration'));
    }
    
    public function getDataTableMultisUser(Request $request) {
        $mUser = User::with('role')
                ->select('id','username','name', 'state_cd','brn_cd',DB::raw('(select count(CA_CASEID) from case_info where CA_INVBY = sys_users.id AND CA_INVSTS = 2) as count_case'))
                ->where(['user_cat' => '1', 'status' => '1', 'brn_cd' => Auth::user()->brn_cd]);
        
        if ($request->mobile) {
            return response()->json(['data' => $mUser->get()->toArray()]);
        }
        $datatables = Datatables::of($mUser)
                ->addIndexColumn()
                ->editColumn('state_cd', function(User $user) {
                    if($user->state_cd != '')
                    return $user->Negeri->descr;
                    else
                    return '';
                })
                ->editColumn('brn_cd', function(User $user) {
                    if($user->brn_cd != '')
                    return $user->Cawangan->BR_BRNNM;
                    else
                    return '';
                })
//                ->editColumn('tugas', function(User $user) {
//                    return Penugasan::GetCountTugas($user->id);
//                })
                ->editColumn('role.role_code', function (User $user) {
                    if(count($user->role) == 1)
                        return User::ShowRoleName($user->role->role_code);
                    else
                        return '';
                })
                ->addColumn('action', function (User $user) {
                return view('aduan.tugas.action_btn_multiuser', compact('user'))->render();
                })
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
    
    public function getDataTableUser(Request $request) {
        $mUser = User::with('role')
                ->select('id','username','name', 'state_cd','brn_cd',DB::raw('(select count(CA_CASEID) from case_info where CA_INVBY = sys_users.id AND CA_INVSTS = 2) as count_case'))
                ->where(['user_cat' => '1', 'status' => '1', 'brn_cd' => Auth::user()->brn_cd]);
        
        if ($request->mobile) {
            return response()->json(['data' => $mUser->get()->toArray()]);
        }
        $datatables = Datatables::of($mUser)
                ->addIndexColumn()
                ->editColumn('state_cd', function(User $user) {
                    if($user->state_cd != '')
                    return $user->Negeri->descr;
                    else
                    return '';
                })
                ->editColumn('brn_cd', function(User $user) {
                    if($user->brn_cd != '')
                    return $user->Cawangan->BR_BRNNM;
                    else
                    return '';
                })
//                ->editColumn('tugas', function(User $user) {
//                    return Penugasan::GetCountTugas($user->id);
//                })
                ->editColumn('role.role_code', function (User $user) {
//                    if($user->role->role_code != '')
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
    
    public function GetUserDetail($id)
    {
        $UserDetail = DB::table('sys_users')
            ->where('id', $id)
            ->pluck('id', 'name');
        return json_encode($UserDetail);
    }
    
    public function getattachment($CASEID)
    {
//        $mPenugasanDoc = PenugasanDoc::where('CC_CASEID', $CASEID);
//        $datatables = Datatables::of($mPenugasanDoc)
//            ->addIndexColumn()
//            ->editColumn('id', function(PenugasanDoc $tugasDoc) {
//                if($tugasDoc->CC_DOCATTCHID != '')
//                    return $tugasDoc->attachment->id;
//                else
//                    return '';
//            })
//            ->editColumn('doc_title', function(PenugasanDoc $tugasDoc) {
//                if($tugasDoc->CC_DOCATTCHID != '')
//                    return $tugasDoc->attachment->doc_title;
//                else
//                    return '';
//            })
//            ->editColumn('file_name_sys', function(PenugasanDoc $tugasDoc) {
//                if($tugasDoc->CC_DOCATTCHID != '')
//                    return '<a href='.Storage::disk('local')->url($tugasDoc->attachment->file_name_sys).' target="_blank">'.$tugasDoc->attachment->file_name.'</a>';
//                else
//                    return '';
//            })
//            ->rawColumns(['file_name_sys']) ;
           $mPenugasanDoc = PenugasanDoc::where('CC_CASEID', $CASEID)->where('CC_IMG_CAT', '1');
        $datatables = Datatables::of($mPenugasanDoc)
                ->addIndexColumn()
                ->editColumn('CC_IMG_NAME', function(PenugasanDoc $tugasDoc) {
                    if($tugasDoc->CC_IMG_NAME != '')
                    return '<a href='.Storage::disk('bahanpath')->url($tugasDoc->CC_PATH.$tugasDoc->CC_IMG).' target="_blank">'.$tugasDoc->CC_IMG_NAME.'</a>';
                    else
                    return '';
                })
                ->rawColumns(['CC_IMG_NAME']);
        
        return $datatables->make(true);
//        return $datatables->make(true);
    }
    
    public function getGabungKes($CASEID) {
        $mPenugasan = Penugasan::where('CA_MERGE', $CASEID)->orderBy('CA_CREDT', 'desc');
        $datatables = Datatables::of($mPenugasan)
            ->addIndexColumn()
            ->editColumn('CA_SUMMARY', function(Penugasan $penugasan) {
                if($penugasan->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $penugasan->CA_SUMMARY), 0, 7)).' ...';
                else
                    return '';
            })
            ->editColumn('CA_INVSTS', function (Penugasan $penugasan) {
                if($penugasan->CA_INVSTS != '')
                    return $penugasan->statusAduan->descr;
                else
                    return '';
            })
            ->editColumn('CA_RCVDT', function (Penugasan $penugasan) {
                return $penugasan->CA_RCVDT ? with(new Carbon($penugasan->CA_RCVDT))->format('d-m-Y h:i A') : '';
            });
            
        return $datatables->make(true);
    }
    
    public function TugasKepada(Request $request, $CA_CASEID) {
        // $this->validate($request, [
        $v = Validator::make($request->all(), [
            'CD_DESC' => 'required_if:CA_INVSTS,2',
            'CA_INVBY' => 'required_if:CA_INVSTS,2',
            'CA_CMPLCAT' => 'required_if:CA_INVSTS,2',
            'CA_CMPLCD' => 'required_if:CA_INVSTS,2',
            'CA_ANSWER' => 'required_unless:CA_INVSTS,2',
            'CA_MAGNCD' => 'required_if:CA_INVSTS,4',
            'CA_INVSTS' => 'required',
            'CA_SUMMARY' => 'required',
        ],
        [
            'CD_DESC.required_if' => 'Ruangan Saranan diperlukan.',
            'CA_INVBY.required_if' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan.',
            'CA_CMPLCAT.required_if' => 'Ruangan Kategori diperlukan.',
            'CA_CMPLCD.required_if' => 'Ruangan SubKategori diperlukan.',
            'CA_ANSWER.required_unless' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
            'CA_MAGNCD.required_if' => 'Ruangan Agensi diperlukan.',
            'CA_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
            'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
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
        $mPenugasan = Penugasan::where(['CA_CASEID' => $CA_CASEID])->first();
        if($request->CA_INVSTS == '2')
        {
            $mPenugasan->CA_INVBY = $request->CA_INVBY;
            $mPenugasan->CA_ASGTO = $request->CA_INVBY;
            $mPenugasan->CA_CMPLCAT = $request->CA_CMPLCAT;
            $mPenugasan->CA_CMPLCD = $request->CA_CMPLCD;
            $mPenugasan->CA_INVDT = Carbon::now();
        }
        elseif($request->CA_INVSTS == '4') // Penutupan (Rujuk Agensi Di Bawah KPDNKK)
        {
            $mPenugasan->CA_MAGNCD = $request->CA_MAGNCD;
            $mPenugasan->CA_ANSWER = $request->CA_ANSWER;
        }
        else
        {
//            $mPenugasan->CA_INVBY = '';
//            $mPenugasan->CA_ASGTO = '';
            $mPenugasan->CA_ANSWER = $request->CA_ANSWER;
        }
        $mPenugasan->CA_ASGDT = Carbon::now();
        $mPenugasan->CA_CASESTS = 2;
        $mPenugasan->CA_INVSTS = $request->CA_INVSTS;
        $mPenugasan->CA_ASGBY = Auth::User()->id;
        $mPenugasan->CA_FILEREF = $request->CA_FILEREF;
//        $mPenugasan->CA_CMPLCAT = $request->CA_CMPLCAT;
//        $mPenugasan->CA_CMPLCD = $request->CA_CMPLCD;
        $mPenugasan->CA_SUMMARY = $request->CA_SUMMARY;
        if ($mPenugasan->save()) {
            
            $SuratPengadu = Letter::where(['letter_code' => $request->CA_INVSTS, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
            $SuratPegawai = Letter::where(['letter_code' => $request->CA_INVSTS, 'letter_type' => '02', 'status' => '1'])->first(); // Templete Surat Kepada Pegawai
            
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
                $replacementsPengadu[7] = $ProfilPegawai->cawangan->BR_TELNO;
                $replacementsPengadu[8] = $ProfilPegawai->email;
                $replacementsPengadu[9] = $ProfilPegawai->name;
                $replacementsPengadu[10] = $ProfilPegawai->cawangan->BR_BRNNM . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR1 . '<br />'
                    . $ProfilPegawai->cawangan->BR_ADDR2 . '<br />'
                    . $ProfilPegawai->cawangan->BR_POSCD . ' ' 
                    . $ProfilPegawai->cawangan->DaerahPegawai->descr . '<br />' 
                    . $ProfilPegawai->cawangan->NegeriPegawai->descr;
                $replacementsPengadu[11] = Carbon::now()->format('d/m/Y');

//                $tarikhPenerimaan = date('d/m/Y', strtotime($mPenugasan->CA_RCVDT));
//                $kodHariPenerimaan = date('N', strtotime($mPenugasan->CA_RCVDT));
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
//                $replacementsPegawai[4] = date('h:i A', strtotime($mPenugasan->CA_RCVDT));
                $replacementsPegawai[4] = '';
                $replacementsPegawai[5] = $ProfilPegawai->name;
                $replacementsPegawai[6] = $CA_CASEID;
                    
            } elseif ($request->CA_INVSTS == '4') { // RUJUK KE KEMENTERIAN/AGENSI LAIN
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
                
                $mAgensiData = Agensi::where(['MI_MINCD' => $mPenugasan->CA_MAGNCD, 'MI_STS' => '1'])->first();
                if ($mAgensiData->MI_EMAIL) {
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
            } elseif ($request->CA_INVSTS == '5') { // Rujuk Ke Tribunal
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
            else if ($request->CA_INVSTS == '7') { //  MAKLUMAT TIDAK LENGKAP
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
            } else if($request->CA_INVSTS == '8') { //  LUAR BIDANG KUASA
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
            } else if($request->CA_INVSTS == '6') { //  PERTANYAAN
                   
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
                $SuratPegawaiFinal = str_replace($arr_repPegawai, "", $SuratPegawaiReplace); // SuratPegawai in HTML
                $SuratPegawaiHtml = PDF::loadHTML($SuratPegawaiFinal); // Generate PDF from HTML

                $filenameSuratPegawai = $userid . '_' . $CA_CASEID . '_' . $date . '_2.pdf';
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
            $mCaseDetail->CD_REASON = $request->CD_REASON;
            $mCaseDetail->CD_REASON_DURATION = $request->CD_REASON_DURATION;
            if($request->CD_REASON == 'P2'){
                $mCaseDetail->CD_REASON_DATE_FROM =
                    Carbon::parse($request->CD_REASON_DATE_FROM);
                $mCaseDetail->CD_REASON_DATE_TO =
                    Carbon::parse($request->CD_REASON_DATE_TO);
            }
            if ($mCaseDetail->save()) {
                if ($request->expectsJson()) {
                    return response()->json(['data' => 'Aduan telah berjaya diberi penugasan']);
                }
                $request->session()->flash('success', 'Aduan telah berjaya diberi Penugasan');
                return redirect('tugas');
            }
        }
    }

    public function CetakSuratPenugasan($CA_CASEID){
        $mPenugasan = Penugasan::where(['CA_CASEID' => $CA_CASEID])->first();
        $mSurat = Letter::where(['letter_type' => '02'])->first();
        $content  = $mSurat->body;
        $new_Content = str_replace(['#NOADUAN', '#CA_AGAINSTNM'], [$CA_CASEID, $mPenugasan->CA_AGAINSTNM], $content);
        $mSurat->body = $new_Content;
        $data = ['mSurat' => $mSurat,'new_Content' => $new_Content];
        $pdf = PDF::loadView('aduan.tugas.surat_penugasan', $data);
        return $pdf->stream('document.pdf');
    }
    
    public function CetakSuratPenerimaan($CA_CASEID){
        $mPenugasan = Penugasan::where(['CA_CASEID' => $CA_CASEID])->first();
        $mProfilPenyiasat = User::find($mPenugasan->CA_INVBY);
        $mProfilPengadu = User::where(['icnew'=>$mPenugasan->CA_DOCNO])->first();
        $alamat = '';
        $postcode = '';
        $distrinct = '';
        $state_cd = '';
        if((!empty($mProfilPengadu->address)) && (!empty($mProfilPengadu->postcode)) && (!empty($mProfilPengadu->distrinct_cd)) && (!empty($mProfilPengadu->state_cd))){
            $alamat = $mProfilPengadu->address;
            $postcode = $mProfilPengadu->postcode;
            $distrinct = $mProfilPengadu->distrinct_cd;
            $state_cd = $mProfilPengadu->state_cd;
        }
        $mSurat = Letter::where(['letter_type' => '01'])->first();
        $content  = $mSurat->body;
        $header = $mSurat->header;
        $new_header = str_replace(['#NAMA_PENGADU', '#ALAMAT','#POSKOD','#NEGERI'], 
                       [$mPenugasan->CA_NAME,$alamat,$postcode,$distrinct,$state_cd], $header);
        $new_Content = str_replace(['#NAMA_PENGADU','#NOADUAN', '#CA_AGAINSTNM','#INVBY'], 
                       [$mPenugasan->CA_NAME, $CA_CASEID, $mPenugasan->CA_AGAINSTNM,$mProfilPenyiasat->name], $content);
        $mSurat->body = $new_Content;
        $mSurat->header = $new_header;
        $data = ['mSurat' => $mSurat,'new_Content' => $new_Content];
        $pdf = PDF::loadView('aduan.tugas.surat_penerimaan', $data);
        return $pdf->stream('document.pdf');
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
    public function edit($CA_CASEID)
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
    
    public function ShowSummary($CASEID)
    {
        $model = Penugasan::where(['CA_CASEID' => $CASEID])->first();
        if (Auth::user()->user_cat == '1') {
            $trnsksi = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->orderBy('CD_CREDT')->get();
            $saranan = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->whereNotIn('CD_INVSTS', [1, 10])->orderBy('CD_CREDT')->get();
        } else if (Auth::user()->user_cat == '2') {
            $trnsksi = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->whereIn('CD_CREDT', function($query) use ($CASEID){
                $query->select(DB::raw('MAX(CD_CREDT)'))
                ->from('case_dtl')
                ->where('CD_CASEID', $CASEID)
                ->groupBy('CD_INVSTS');
            })->orderBy('CD_CREDT')->get();
            $saranan = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->whereNotIn('CD_INVSTS', [1, 10])->whereIn('CD_CREDT', function($query) use ($CASEID){
                $query->select(DB::raw('MAX(CD_CREDT)'))
                ->from('case_dtl')
                ->where('CD_CASEID', $CASEID)
                ->groupBy('CD_INVSTS');
            })->orderBy('CD_CREDT')->get();
        }
        $img = PenugasanDoc::
            //where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 1])
            where(['CC_CASEID' => $CASEID])
            ->where(function ($query){
                $query->whereNull('CC_IMG_CAT')
                    ->orWhere('CC_IMG_CAT', '1');
            })
            ->get();
        $buktisiasatan = PenugasanDoc::where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 2])->get();
        $kes = PenyiasatanKes::where(['CT_CASEID'=>$CASEID])->get();
        $mBukaSemula = DB::table('case_forward')->where(['CF_FWRD_CASEID' => $CASEID])->first();
        $mGabungOne = DB::table('case_rel')->where(['CR_CASEID' => $CASEID])->first();
        if ($mGabungOne) {
            $mGabungAll = DB::table('case_rel')->where(['CR_RELID' => $mGabungOne->CR_RELID])->get();
        } else {
            $mGabungAll = '';
        }
        return view('aduan.tugas.show_summary_modal', compact('model','trnsksi','img','saranan','kes', 'buktisiasatan', 'mBukaSemula','mGabungAll'));
    }
    
    public function PrintSummary($CASEID)
    {
        $model = Penugasan::where(['CA_CASEID' => $CASEID])->first();
        if (Auth::user()->user_cat == '1') {
            $trnsksi = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->orderBy('CD_CREDT')->get();
            $saranan = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->whereNotIn('CD_INVSTS', [1, 10])->orderBy('CD_CREDT')->get();
        } else if (Auth::user()->user_cat == '2') {
            $trnsksi = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->whereIn('CD_CREDT', function($query) use ($CASEID){
                $query->select(DB::raw('MAX(CD_CREDT)'))
                ->from('case_dtl')
                ->where('CD_CASEID', $CASEID)
                ->groupBy('CD_INVSTS');
            })->orderBy('CD_CREDT')->get();
            $saranan = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->whereNotIn('CD_INVSTS', [1, 10])->whereIn('CD_CREDT', function($query) use ($CASEID){
                $query->select(DB::raw('MAX(CD_CREDT)'))
                ->from('case_dtl')
                ->where('CD_CASEID', $CASEID)
                ->groupBy('CD_INVSTS');
            })->orderBy('CD_CREDT')->get();
        }
        $img = PenugasanDoc::where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 1])->get();
        $mBukaSemula = DB::table('case_forward')->where(['CF_FWRD_CASEID' => $CASEID])->first();
        $buktisiasatan = PenugasanDoc::where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 2])->get();
        $kes = PenyiasatanKes::where(['CT_CASEID'=>$CASEID])->get();
        $mGabungOne = DB::table('case_rel')->where(['CR_CASEID' => $CASEID])->first();
        if ($mGabungOne) {
            $mGabungAll = DB::table('case_rel')->where(['CR_RELID' => $mGabungOne->CR_RELID])->get();
        } else {
            $mGabungAll = '';
        }
        $GeneratePdfSummary = PDF::loadView('aduan.tugas.show_summary_pdf', 
            compact('model','trnsksi','img','mBukaSemula','saranan','buktisiasatan','kes','mGabungAll'), 
            [], ['default_font_size' => 7, 'title' => config('app.name')]
        );
        $GeneratePdfSummary->stream();
    }

    public function TutupAduan()
    {
        $TempohAutoClose = Ref::where(['cat' => '1247', 'status' => 1])->first();
        $model = DB::table('case_info')
                        ->leftJoin('case_dtl', 'case_info.CA_CASEID', '=', 'case_dtl.CD_CASEID')
                        ->whereIn('CD_INVSTS', ['7', '8'])
                        ->where('CD_CURSTS', 1)->get();
        if (!empty($model)) {
            foreach ($model as $models) {
                $totalDuration = (new Penugasan)->GetDuration($models->CD_CREDT, $models->CA_CASEID);
                if ($totalDuration > $TempohAutoClose->code) {
                    if ($models->CA_INVBY != '') { // TELAH DITUGASKAN
                        $mCaseInfo = Penugasan::where('CA_CASEID', $models->CA_CASEID)->first();
                        $mCaseInfo->update(['CA_INVSTS'=> 3]); // UPDATE SIASATAN SELESAI
                        DB::table('case_dtl')->where(['CD_CASEID'=> $models->CA_CASEID, 'CD_CURSTS' => 1])->update(['CD_CURSTS' => 0]);
                        $mPenugasanDetail = PenugasanCaseDetail::create([
                                        'CD_CASEID' => $models->CA_CASEID,
                                        'CD_TYPE' => 'D',
                                        'CD_DESC' => 'Aduan Dikemaskini oleh sistem',
                                        'CD_INVSTS' => $mCaseInfo->CA_INVSTS,
                                        'CD_CASESTS' => $models->CA_CASESTS,
                                        'CD_CURSTS' => 1]);
                    } else {
                        $mCaseInfo = Penugasan::where('CA_CASEID', $models->CA_CASEID)->first();
                        $mCaseInfo->update(['CA_INVSTS'=> 9]); // UPDATE PENGESAHAN PENUTUPAN
                        DB::table('case_dtl')->where(['CD_CASEID'=> $models->CA_CASEID, 'CD_CURSTS' => 1])->update(['CD_CURSTS' => 0]);
                        $mPenugasanDetail = PenugasanCaseDetail::create([
                                        'CD_CASEID' => $models->CA_CASEID,
                                        'CD_TYPE' => 'D',
                                        'CD_DESC' => 'Aduan Dikemaskini oleh sistem',
                                        'CD_INVSTS' => $mCaseInfo->CA_INVSTS,
                                        'CD_CASESTS' => $models->CA_CASESTS,
                                        'CD_CURSTS' => 1]);
                    }
                }
            }
        }
    }
    
    public static function generateword($CA_CASEID, $CD_ACTTO){
        WordGeneratorRepository::generateFormDownload($CA_CASEID, $CD_ACTTO);
    }
}
    

