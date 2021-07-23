<?php

namespace App\Http\Controllers\Integriti;

use App\Attachment;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
use App\Integriti\IntegritiForward;
use App\Letter;
use App\Repositories\RunnerRepository;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class IntegritiBukaSemulaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('integriti.bukasemula.index');
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
     * @param  \App\BukaSemula  $bukaSemula
     * @return \Illuminate\Http\Response
     */
    public function show(BukaSemula $bukaSemula)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BukaSemula  $bukaSemula
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $mIntegriti = IntegritiAdmin::where('id', $id)->first();
        $mBukaSemula = IntegritiAdmin::where('IN_CASEID', $mIntegriti->IN_CASEID)->firstOrFail();
        return view('integriti.bukasemula.edit_1', compact('mBukaSemula',''));
    }

    public function update(Request $request, $id)
    {
//        $this->validate($request, [
//            'CA_INVBY' => 'required',
//            'CA_RESULT' => 'required',
//            'CA_SSP' => 'required',
//            'CA_IPNO' => 'required_if:CA_SSP,YES',
//            'CA_AKTA' => 'required_if:CA_SSP,YES',
//            'CA_SUBAKTA' => 'required_if:CA_SSP,YES',
//            'CD_DESC' => 'required',
//        ],
//        [
//            'CA_INVBY.required' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan.',
//            'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan.',
//            'CA_SSP.required' => 'Ruangan Kes SSP diperlukan.',
//            'CA_IPNO.required_if' => 'Ruangan No. IP diperlukan jika Kes SSP adalah Ya.',
//            'CA_AKTA.required_if' => 'Ruangan Akta diperlukan jika Kes SSP adalah Ya.',
//            'CA_SUBAKTA.required_if' => 'Ruangan Kod Akta diperlukan jika Kes SSP adalah Ya.',
//            'CD_DESC.required' => 'Ruangan Saranan diperlukan.',
//        ]);
        

        $OldBukaSemula = IntegritiAdmin::where('id', $id)->first();
        $NewBukaSemula = new IntegritiAdmin();
        $NewBukaSemula->fill($OldBukaSemula['attributes']);
        $NewBukaSemula->IN_CASEID = RunnerRepository::generateAppNumber('INT', date('m'), date('Y'));
        $NewBukaSemula->IN_INVSTS = '01'; // Aduan Diterima
        $NewBukaSemula->IN_IPSTS = NULL;
        $NewBukaSemula->IN_CASESTS = 1; 
        $NewBukaSemula->IN_RCVBY = Auth::User()->id;
        $NewBukaSemula->IN_RCVDT = Carbon::now();
        $NewBukaSemula->IN_INVBY = NULL;
        $NewBukaSemula->IN_INVDT = NULL;
        $NewBukaSemula->IN_ASGTO = NULL;
        $NewBukaSemula->IN_ASGBY = NULL;
        $NewBukaSemula->IN_ASGDT = NULL;
        $NewBukaSemula->IN_COMPLETEBY = NULL;
        $NewBukaSemula->IN_COMPLETEDT = NULL;
        $NewBukaSemula->IN_CLOSEBY = NULL;
        $NewBukaSemula->IN_CLOSEDT = NULL;
        $NewBukaSemula->IN_ACCESSIND = NULL;
        $NewBukaSemula->IN_ACCESSBY = NULL;
        $NewBukaSemula->IN_ACCESSDATE = NULL;
        $NewBukaSemula->IN_IPNO = NULL;
        $NewBukaSemula->IN_MEETINGNUM = NULL;
        
        if($NewBukaSemula->save()) {
            $mBukaSemulaDetail = new IntegritiAdminDetail;
            $mBukaSemulaDetail->ID_CASEID = $NewBukaSemula->IN_CASEID;
            $mBukaSemulaDetail->ID_DESC = 'Aduan Dibuka Semula';
            $mBukaSemulaDetail->ID_INVSTS = $NewBukaSemula->IN_INVSTS;
            $mBukaSemulaDetail->ID_CASESTS = $NewBukaSemula->IN_CASESTS;
            $mBukaSemulaDetail->ID_CURSTS = '1';
            $mBukaSemulaDetail->ID_ACTFROM = Auth::User()->id;
            if($mBukaSemulaDetail->save()) {
                $mBukaSemulaForward = new IntegritiForward;
                $mBukaSemulaForward->IF_CASEID = $OldBukaSemula->IN_CASEID;
                $mBukaSemulaForward->IF_FORWARD_CASEID = $NewBukaSemula->IN_CASEID;
                if($mBukaSemulaForward->save()) {
                    if ($request->expectsJson()) {
                        return response()->json(['data' => 'Aduan baru '.$NewBukaSemula->IN_CASEID.' telah berjaya dibuka semula. (Aduan lama: '.$OldBukaSemula->IN_CASEID.')']);
                    }
                    return redirect()->route('integritibukasemula.index')->with('success', 'Aduan baru '.$NewBukaSemula->IN_CASEID.' telah berjaya dibuka semula. (Aduan lama: '.$OldBukaSemula->IN_CASEID.')');
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BukaSemula  $bukaSemula
     * @return \Illuminate\Http\Response
     */
    public function destroy(BukaSemula $bukaSemula)
    {
        //
    }
    
    public function getdatatablecase(Request $request) {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
//        $mBukaSemula = BukaSemula::where(['CA_BRNCD' => Auth::user()->brn_cd])->whereIn('CA_INVSTS',[4,5,7,8,9])->orderBy('CA_RCVDT', 'DESC');
        $role = DB::table('sys_user_access')->where('user_id', Auth::user()->id)->first();
        if ($role->role_code == '120' || $role->role_code == '800') {
            $mBukaSemula = IntegritiAdmin::leftJoin('integriti_case_forward', 'integriti_case_info.IN_CASEID', '=', 'integriti_case_forward.IF_CASEID')
                ->select('integriti_case_info.*')
                ->whereNull('integriti_case_forward.IF_CASEID')
                ->where(function ($query){
                    $query->whereIn('IN_INVSTS',['04','05','06','08','011']);
                    $query->orWhere(function ($query){
                        $query->whereIn('IN_IPSTS', ['04', '05', '09']);
                    });
                })
                ->orderBy('IN_RCVDT', 'DESC');
        } else {
            $mBukaSemula = IntegritiAdmin::leftJoin('integriti_case_forward', 'integriti_case_info.IN_CASEID', '=', 'integriti_case_forward.IF_CASEID')
            ->select('integriti_case_info.*')
            ->whereNull('integriti_case_forward.IF_CASEID')
            ->where(['IN_BRNCD' => Auth::user()->brn_cd])
            ->where(function ($query){
                $query->whereIn('IN_INVSTS',['04','05','06','08','011']);
                $query->orWhere(function ($query){
                    $query->whereIn('IN_IPSTS', ['04', '05', '09']);
                });
            })
            ->orderBy('IN_RCVDT', 'DESC');
        }
        
        if ($request->mobile) {
            return response()->json(['data' => $mBukaSemula->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mBukaSemula)
            ->addIndexColumn()
            ->editColumn('IN_SUMMARY', function(IntegritiAdmin $bukaSemula) {
                if($bukaSemula->IN_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $bukaSemula->IN_SUMMARY), 0, 7)).' ...';
                else
                    return '';
            })
            ->editColumn('IN_INVSTS', function (IntegritiAdmin $bukaSemula) {
                if($bukaSemula->IN_INVSTS != '')
                    return $bukaSemula->invsts->descr;
                else
                    return '';
            })
            ->editColumn('IN_IPSTS', function (IntegritiAdmin $bukaSemula) {
                if($bukaSemula->IN_IPSTS != '')
                    return $bukaSemula->ipsts->descr;
                else
                    return '';
            })
            ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                return view('integriti.base.summarylink', compact('integriti'))->render();
            })
            ->editColumn('IN_RCVDT', function (IntegritiAdmin $bukaSemula) {
                return $bukaSemula->IN_RCVDT ? with(new Carbon($bukaSemula->IN_RCVDT))->format('d-m-Y h:i A') : '';
            })
            /* ->addColumn('tempoh', function (IntegritiAdmin $bukaSemula) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
                $totalDuration = $bukaSemula->getduration($bukaSemula->CA_RCVDT, $bukaSemula->CA_CASEID);
                if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                            return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                        else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                            return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
                        else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                            return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                        else if ($totalDuration > $TempohKetiga->code)
                            return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            }) */
            ->addColumn('action', '
                <a href="{{ url("integritibukasemula/{$id}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-pencil"></i></a>
            ')
            ->rawColumns(['IN_SUMMARY','IN_CASEID','action']) //'tempoh'
            ->filter(function ($query) use ($request) {
                if ($request->has('IN_CASEID')) {
                    $query->where('IN_CASEID', $request->get('IN_CASEID'));
                }
                if ($request->has('IN_SUMMARY')) {
                    $query->where('IN_SUMMARY', 'LIKE', "%{$request->get('IN_SUMMARY')}%");
                }
                if ($request->has('IN_AGAINSTNM')) {
                    $query->where('IN_AGAINSTNM', 'LIKE', "%{$request->get('IN_AGAINSTNM')}%");
                }
                if ($request->has('IN_RCVDT')) {
                    $query->where('IN_RCVDT', Carbon::parse($request->get('IN_RCVDT'))->format('Y-m-d'));
                }
                if ($request->has('IN_INVSTS')) {
                    $query->where('IN_INVSTS', $request->get('IN_INVSTS'));
                }
                if ($request->has('IN_IPSTS')) {
                    $query->where('IN_IPSTS', $request->get('IN_IPSTS'));
                }
            })
        ;
        return $datatables->make(true);
    }
    
    public function getdatatableattachment($CASEID)
    {
        $mBukaSemulaDoc = BukaSemulaDoc::where('CC_CASEID', $CASEID);
        $datatables = Datatables::of($mBukaSemulaDoc)
            ->addIndexColumn()
//            ->editColumn('doc_title', function(BukaSemulaDoc $bukaSemulaDoc) {
//                if($bukaSemulaDoc->CC_DOCATTCHID != '')
//                    return $bukaSemulaDoc->attachment->doc_title;
//                else
//                    return '';
//            })
//            ->editColumn('file_name_sys', function(BukaSemulaDoc $bukaSemulaDoc) {
//                if($bukaSemulaDoc->CC_DOCATTCHID != '')
//                    return '<a href='.Storage::disk('local')->url($bukaSemulaDoc->attachment->file_name_sys).' target="_blank">'.$bukaSemulaDoc->attachment->file_name.'</a>';
//                else
//                    return '';
//            })
            ->editColumn('CC_IMG_NAME', function(BukaSemulaDoc $bukaSemulaDoc) {
                if($bukaSemulaDoc->CC_IMG_NAME != '')
                    return '<a href='.Storage::disk('bahanpath')->url($bukaSemulaDoc->CC_PATH.$bukaSemulaDoc->CC_IMG).' target="_blank">'.$bukaSemulaDoc->CC_IMG_NAME.'</a>';
                else
                    return '';
            })
//            ->editColumn('updated_at', function(BukaSemulaDoc $bukaSemulaDoc) {
//                if($bukaSemulaDoc->CC_DOCATTCHID != '')
//                    return $bukaSemulaDoc->attachment->updated_at ? with(new Carbon($bukaSemulaDoc->attachment->updated_at))->format('d-m-Y h:i A') : '';
//                else
//                    return '';
//            })
            ->editColumn('updated_at', function(BukaSemulaDoc $bukaSemulaDoc) {
                if($bukaSemulaDoc->updated_at != '')
                    return $bukaSemulaDoc->updated_at ? with(new Carbon($bukaSemulaDoc->updated_at))->format('d-m-Y h:i A') : '';
                else
                    return '';
            })
            ->rawColumns(['CC_IMG_NAME'])
        ;
        return $datatables->make(true);
    }
    
    public function getdatatablemergecase($CASEID) {
        $mBukaSemula = BukaSemula::where('CA_MERGE', $CASEID)->orderBy('CA_CREDT', 'desc');
        $datatables = Datatables::of($mBukaSemula)
            ->addIndexColumn()
            ->editColumn('CA_SUMMARY', function(BukaSemula $bukaSemula) {
                if($bukaSemula->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $bukaSemula->CA_SUMMARY), 0, 7)).' ...';
                else
                    return '';
            })
            ->editColumn('CA_RCVDT', function(BukaSemula $bukaSemula) {
                return $bukaSemula->CA_RCVDT ? with(new Carbon($bukaSemula->CA_RCVDT))->format('d-m-Y h:i A') : '';
            })
        ;
        return $datatables->make(true);
    }
    
    public function getdatatabletransaction($CASEID)
    {
        $mBukaSemulaDetail = BukaSemulaDetail::where('CD_CASEID', $CASEID);
        $datatables = Datatables::of($mBukaSemulaDetail)
            ->addIndexColumn()
            ->editColumn('CD_INVSTS', function(BukaSemulaDetail $bukaSemulaDetail) {
                if($bukaSemulaDetail->CD_INVSTS != '')
                    return $bukaSemulaDetail->statusaduan->descr;
                else
                    return '';
            })
            ->editColumn('CD_ACTFROM', function(BukaSemulaDetail $bukaSemulaDetail) {
//                if($bukaSemulaDetail->CD_ACTFROM != '')
//                    return $bukaSemulaDetail->actfrom->name;
//                else
//                    return '';
                if ($bukaSemulaDetail->CD_ACTFROM != ''){
                    if ($bukaSemulaDetail->actfrom){
                        return $bukaSemulaDetail->actfrom->name;
                    } else {
                        return $bukaSemulaDetail->CD_ACTFROM;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CD_ACTTO', function(BukaSemulaDetail $bukaSemulaDetail) {
//                if($bukaSemulaDetail->CD_ACTTO != '')
//                    return $bukaSemulaDetail->actto->name;
//                else
//                    return '';
                if ($bukaSemulaDetail->CD_ACTTO != ''){
                    if($bukaSemulaDetail->actto){
                        return $bukaSemulaDetail->actto->name;
                    } else {
                        return $bukaSemulaDetail->CD_ACTTO;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CD_CREDT', function (BukaSemulaDetail $bukaSemulaDetail) {
                return $bukaSemulaDetail->CD_CREDT ? with(new Carbon($bukaSemulaDetail->CD_CREDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CD_DESC', function(BukaSemulaDetail $bukaSemulaDetail) {
                if($bukaSemulaDetail->CD_CASEID != '')
                    return $bukaSemulaDetail->CD_DESC;
                else
                    return '';
            })
            ->editColumn('CD_DOCATTCHID_PUBLIC', function(BukaSemulaDetail $bukaSemulaDetail) {
                if($bukaSemulaDetail->CD_DOCATTCHID_PUBLIC != '')
                    return '<a href='.Storage::disk('letter')->url($bukaSemulaDetail->suratpublic->file_name_sys).' target="_blank">'.$bukaSemulaDetail->suratpublic->file_name.'</a>';
                else
                    return '';
            })
            ->editColumn('CD_DOCATTCHID_ADMIN', function(BukaSemulaDetail $bukaSemulaDetail) {
                if($bukaSemulaDetail->CD_DOCATTCHID_ADMIN != '')
                    return '<a href='.Storage::disk('letter')->url($bukaSemulaDetail->suratadmin->file_name_sys).' target="_blank">'.$bukaSemulaDetail->suratadmin->file_name.'</a>';
                else
                    return '';
            })
            ->rawColumns(['CD_DOCATTCHID_PUBLIC', 'CD_DOCATTCHID_ADMIN'])
        ;
        return $datatables->make(true);
    }
    
    public function getdatatableuser(Request $request) {
        $mUser = User::where('user_cat', '1');

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
            ->addColumn('action', '<a class="btn btn-xs btn-primary" onclick="carianpenyiasat({{ $id }})"><i class="fa fa-arrow-down"></i></a>')
            ->filter(function ($query) use ($request) {
                if ($request->has('icnew')) {
                    $query->where('icnew', 'LIKE', "%{$request->get('icnew')}%");
                }
                if ($request->has('name')) {
                    $query->where('name', 'LIKE', "%{$request->get('name')}%");
                }
                if ($request->has('state_cd')) {
                    $query->where('state_cd', $request->get('state_cd'));
                }
                if ($request->has('brn_cd')) {
                    $query->where('brn_cd', $request->get('brn_cd'));
                }
            })
            ;

        return $datatables->make(true);
    }
    
    public function getuserdetail($id)
    {
        $UserDetail = DB::table('sys_users')
            ->where('id', $id)
            ->pluck('id', 'name');
        return json_encode($UserDetail);
    }
    
    public function getsubaktalist($AKTA)
    {
        $mRef = DB::table('sys_ref')
            ->where('cat', "714")
            ->where('code', 'LIKE', "$AKTA%")
            ->orderBy('descr')
            ->pluck('code', 'descr')
            ->prepend('', '-- SILA PILIH --');
        return json_encode($mRef);
    }
}
