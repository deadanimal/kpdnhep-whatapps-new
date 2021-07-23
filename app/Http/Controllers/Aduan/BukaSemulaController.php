<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\BukaSemula;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Aduan\BukaSemulaDetail;
use App\Aduan\BukaSemulaDoc;
use App\Aduan\BukaSemulaForward;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Letter;
use App\Attachment;
use PDF;
use App\Repositories\RunnerRepository;

class BukaSemulaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('aduan.bukasemula.index');
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
//    public function edit(BukaSemula $BukaSemula)
    public function edit(Request $request, $CASEID)
    {
        $mBukaSemula = BukaSemula::where('CA_CASEID', $CASEID)->firstOrFail();
//        if ($mBukaSemula->CA_RCVTYP == 'S28') {
//            $NewNoAduan = RunnerRepository::generateAppNumber('X', date('y'), '00');
//        } elseif ($mBukaSemula->CA_RCVTYP == 'S29') {
//            $NewNoAduan = RunnerRepository::generateAppNumber('X', date('y'), '000');
//        } elseif (in_array($mBukaSemula->CA_RCVTYP, ['S01','S02','S04','S05','S13','S14','S34','S35'])) {
//            $NewNoAduan = RunnerRepository::generateAppNumber('X', date('y'), 'SAS0');
//        } else {
//            $NewNoAduan = RunnerRepository::generateAppNumber('X', date('y'), '0');
//        }
//        $NewNoAduan = BukaSemula::getNoAduan($mBukaSemula->CA_RCVTYP);
        
//        if ($request->expectsJson()) {
//            return response()->json(['data' => $NewNoAduan]);
//        }
//        dd($mBukaSemula);
//        $mBukaSemulaForward = BukaSemulaForward::where('CF_FWRD_CASEID', $CASEID)->first();
//        $countBukaSemulaForward = DB::table('case_forward')->where('CF_FWRD_CASEID', $CASEID)->count('CF_FWRD_CASEID');
//        $countCaseDetail = DB::table('case_dtl')->where('CD_CASEID', $CASEID)->count('CD_CASEID');
//        return view('aduan.bukasemula.edit', compact('mBukaSemula', 'mBukaSemulaForward', 'countBukaSemulaForward', 'countCaseDetail'));
        return view('aduan.bukasemula.edit_1', compact('mBukaSemula',''));
    }

    public function update(Request $request, $CASEID)
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
        
        $OldBukaSemula = BukaSemula::find($CASEID);
        $NewBukaSemula = new BukaSemula();
        $NewBukaSemula->fill($OldBukaSemula['attributes']);
        if ($OldBukaSemula->CA_RCVTYP == 'S28') {
            $NewBukaSemula->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), '00');
        } elseif ($OldBukaSemula->CA_RCVTYP == 'S29') {
            $NewBukaSemula->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), '000');
        } elseif (in_array($OldBukaSemula->CA_RCVTYP, ['S01','S02','S04','S05','S13','S14','S34','S35'])) {
            $NewBukaSemula->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), 'SAS0');
        } else {
            $NewBukaSemula->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), '0');
        }
//        $NewBukaSemula->CA_CASEID = BukaSemula::getNoAduan($OldBukaSemula->CA_RCVTYP);
        $NewBukaSemula->CA_INVSTS = 1; // Aduan Diterima
        $NewBukaSemula->CA_CASESTS = 1; 
        $NewBukaSemula->CA_RCVBY = Auth::User()->id;
        $NewBukaSemula->CA_RCVDT = Carbon::now();
        $NewBukaSemula->CA_INVBY = NULL;
        $NewBukaSemula->CA_INVDT = NULL;
        $NewBukaSemula->CA_ASGBY = NULL;
        $NewBukaSemula->CA_ASGDT = NULL;
        $NewBukaSemula->CA_COMPLETEBY = NULL;
        $NewBukaSemula->CA_COMPLETEDT = NULL;
        $NewBukaSemula->CA_CLOSEBY = NULL;
        $NewBukaSemula->CA_CLOSEDT = NULL;
//        dd($NewBukaSemula);
        
        if($NewBukaSemula->save()) {
            $mBukaSemulaDetail = new BukaSemulaDetail;
            $mBukaSemulaDetail->CD_CASEID = $NewBukaSemula->CA_CASEID;
            $mBukaSemulaDetail->CD_DESC = 'Aduan Dibuka Semula';
            $mBukaSemulaDetail->CD_INVSTS = $NewBukaSemula->CA_INVSTS;
            $mBukaSemulaDetail->CD_CASESTS = $NewBukaSemula->CA_CASESTS;
            $mBukaSemulaDetail->CD_CURSTS = '1';
            $mBukaSemulaDetail->CD_ACTFROM = Auth::User()->id;
            if($mBukaSemulaDetail->save()) {
                $mBukaSemulaForward = new BukaSemulaForward;
                $mBukaSemulaForward->CF_CASEID = $OldBukaSemula->CA_CASEID;
                $mBukaSemulaForward->CF_FWRD_CASEID = $NewBukaSemula->CA_CASEID;
                if($mBukaSemulaForward->save()) {
                    if ($request->expectsJson()) {
                        return response()->json(['data' => 'Aduan baru '.$NewBukaSemula->CA_CASEID.' telah berjaya dibuka semula. (Aduan lama: '.$OldBukaSemula->CA_CASEID.')']);
                    }
                    return redirect()->route('bukasemula.index')->with('success', 'Aduan baru '.$NewBukaSemula->CA_CASEID.' telah berjaya dibuka semula. (Aduan lama: '.$OldBukaSemula->CA_CASEID.')');
                }
            }
        }
        
//        $mBukaSemula = BukaSemula::find($CASEID);
//        $mBukaSemula->fill($request->all());
//        $mBukaSemula->CA_ASGBY = Auth::User()->id;
//        $mBukaSemula->CA_INVBY = $request->CA_INVBY;
//        $mBukaSemula->CA_ASGDT = Carbon::now();
//        if ($mBukaSemula->CA_SSP == 'NO') {
//            $mBukaSemula->CA_IPNO = null;
//            $mBukaSemula->CA_AKTA = null;
//            $mBukaSemula->CA_SUBAKTA = null;
//        }
//        if ($mBukaSemula->save()) {
//            $mBukaSemulaKesBaru = new BukaSemula;
////            $mBukaSemulaKesBaru = $mBukaSemula->replicate();
//            $mBukaSemulaKesBaru->fill($request->all());
//            $mBukaSemulaKesBaru->CA_CASEID = BukaSemula::getNoAduan($mBukaSemula->CA_RCVTYP);
//            $mBukaSemulaKesBaru->CA_DEPTCD = $mBukaSemula->CA_DEPTCD;
//            $mBukaSemulaKesBaru->CA_DOCTYP = $mBukaSemula->CA_DOCTYP;
//            $mBukaSemulaKesBaru->CA_INVSTS = 9;
//            $mBukaSemulaKesBaru->CA_CASESTS = 2;
//            $mBukaSemulaKesBaru->CA_RCVTYP = $mBukaSemula->CA_RCVTYP;
//            $mBukaSemulaKesBaru->CA_RCVBY = $mBukaSemula->CA_RCVBY;
//            $mBukaSemulaKesBaru->CA_RCVDT = Carbon::now();
//            $mBukaSemulaKesBaru->CA_ASGTO = $mBukaSemula->CA_ASGTO;
//            $mBukaSemulaKesBaru->CA_ASGBY = $mBukaSemula->CA_ASGBY;
//            $mBukaSemulaKesBaru->CA_ASGDT = $mBukaSemula->CA_ASGDT;
//            $mBukaSemulaKesBaru->CA_INVBY = $mBukaSemula->CA_INVBY;
//            $mBukaSemulaKesBaru->CA_COMPLETEDT = Carbon::now();
//            $mBukaSemulaKesBaru->CA_CLOSEBY = $mBukaSemula->CA_CLOSEBY;
//            $mBukaSemulaKesBaru->CA_CLOSEDT = Carbon::now();
//            $mBukaSemulaKesBaru->CA_BRNCD = $mBukaSemula->CA_BRNCD;
//            $mBukaSemulaKesBaru->CA_CMPLCAT = $mBukaSemula->CA_CMPLCAT;
//            $mBukaSemulaKesBaru->CA_CMPLCD = $mBukaSemula->CA_CMPLCD;
//            $mBukaSemulaKesBaru->CA_SUMMARY = $mBukaSemula->CA_SUMMARY;
//            $mBukaSemulaKesBaru->CA_SSP = $mBukaSemula->CA_SSP;
//            $mBukaSemulaKesBaru->CA_IPNO = $mBukaSemula->CA_IPNO;
//            $mBukaSemulaKesBaru->CA_AKTA = $mBukaSemula->CA_AKTA;
//            $mBukaSemulaKesBaru->CA_SUBAKTA = $mBukaSemula->CA_SUBAKTA;
//            $mBukaSemulaKesBaru->CA_SEXCD = $mBukaSemula->CA_SEXCD;
//            $mBukaSemulaKesBaru->CA_NAME = $mBukaSemula->CA_NAME;
//            $mBukaSemulaKesBaru->CA_DOCNO = $mBukaSemula->CA_DOCNO;
//            $mBukaSemulaKesBaru->CA_AGE = $mBukaSemula->CA_AGE;
//            $mBukaSemulaKesBaru->CA_ADDR = $mBukaSemula->CA_ADDR;
//            $mBukaSemulaKesBaru->CA_DISTCD = $mBukaSemula->CA_DISTCD;
//            $mBukaSemulaKesBaru->CA_POSCD = $mBukaSemula->CA_POSCD;
//            $mBukaSemulaKesBaru->CA_STATECD = $mBukaSemula->CA_STATECD;
//            $mBukaSemulaKesBaru->CA_NATCD = $mBukaSemula->CA_NATCD;
//            $mBukaSemulaKesBaru->CA_COUNTRYCD = $mBukaSemula->CA_COUNTRYCD;
//            $mBukaSemulaKesBaru->CA_TELNO = $mBukaSemula->CA_TELNO;
//            $mBukaSemulaKesBaru->CA_FAXNO = $mBukaSemula->CA_FAXNO;
//            $mBukaSemulaKesBaru->CA_EMAIL = $mBukaSemula->CA_EMAIL;
//            $mBukaSemulaKesBaru->CA_MOBILENO = $mBukaSemula->CA_MOBILENO;
//            $mBukaSemulaKesBaru->CA_RACECD = $mBukaSemula->CA_RACECD;
//            $mBukaSemulaKesBaru->CA_AGAINSTNM = $mBukaSemula->CA_AGAINSTNM;
//            $mBukaSemulaKesBaru->CA_AGAINSTADD = $mBukaSemula->CA_AGAINSTADD;
//            $mBukaSemulaKesBaru->CA_AGAINST_POSTCD = $mBukaSemula->CA_AGAINST_POSTCD;
//            $mBukaSemulaKesBaru->CA_AGAINST_TELNO = $mBukaSemula->CA_AGAINST_TELNO;
//            $mBukaSemulaKesBaru->CA_AGAINST_FAXNO = $mBukaSemula->CA_AGAINST_FAXNO;
//            $mBukaSemulaKesBaru->CA_AGAINST_MOBILENO = $mBukaSemula->CA_AGAINST_MOBILENO;
//            $mBukaSemulaKesBaru->CA_AGAINST_PREMISE = $mBukaSemula->CA_AGAINST_PREMISE;
//            $mBukaSemulaKesBaru->CA_AGAINST_DISTCD = $mBukaSemula->CA_AGAINST_DISTCD;
//            $mBukaSemulaKesBaru->CA_AGAINST_STATECD = $mBukaSemula->CA_AGAINST_STATECD;
//            $mBukaSemulaKesBaru->CA_AGAINST_EMAIL = $mBukaSemula->CA_AGAINST_EMAIL;
//            if($mBukaSemulaKesBaru->save()) {
//                $mBukaSemulaDetail = new BukaSemulaDetail;
//                $mBukaSemulaDetail->CD_CASEID = $mBukaSemula->CA_CASEID;
//                $mBukaSemulaDetail->CD_DESC = $request->CD_DESC;
//                $mBukaSemulaDetail->CD_INVSTS = $mBukaSemula->CA_INVSTS;
//                $mBukaSemulaDetail->CD_CASESTS = $mBukaSemula->CA_CASESTS;
//                $mBukaSemulaDetail->CD_CURSTS = '1';
//                $mBukaSemulaDetail->CD_ACTFROM = $mBukaSemula->CA_ASGBY;
//                $mBukaSemulaDetail->CD_ACTTO = $mBukaSemula->CA_INVBY;
//                if($mBukaSemulaDetail->save()) {
//                    $mBukaSemulaForward = new BukaSemulaForward;
//                    $mBukaSemulaForward->CF_CASEID = $CASEID;
//                    $mBukaSemulaForward->CF_FWRD_CASEID = $mBukaSemulaKesBaru->CA_CASEID;
//                    if($mBukaSemulaForward->save()) {
//                        $request->session()->flash('success', 'Aduan telah berjaya dibuka semula');
//                        return redirect('bukasemula');
////                        return redirect()->back();
////                        return redirect('bukasemula/'.$request->CA_CASEID.'/edit#transaction');
//                    }
//                }
//            }
//        }
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
            $mBukaSemula = BukaSemula::leftJoin('case_forward', 'case_info.CA_CASEID', '=', 'case_forward.CF_CASEID')->select('case_info.*')->whereNull('case_forward.CF_CASEID')->whereIn('CA_INVSTS',[4,5,6,8,9,11])->orderBy('CA_RCVDT', 'DESC');
        } else {
            $mBukaSemula = BukaSemula::leftJoin('case_forward', 'case_info.CA_CASEID', '=', 'case_forward.CF_CASEID')->select('case_info.*')->whereNull('case_forward.CF_CASEID')->where(['CA_BRNCD' => Auth::user()->brn_cd])->whereIn('CA_INVSTS',[4,5,6,8,9,11])->orderBy('CA_RCVDT', 'DESC');
        }
        
        if ($request->mobile) {
            return response()->json(['data' => $mBukaSemula->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mBukaSemula)
            ->addIndexColumn()
            ->editColumn('CA_SUMMARY', function(BukaSemula $bukaSemula) {
//                return '<div style="max-height:80px; overflow:auto">'.$bukaSemula->CA_SUMMARY.'</div>';
                if($bukaSemula->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $bukaSemula->CA_SUMMARY), 0, 7)).' ...';
                else
                    return '';
            })
            ->editColumn('CA_INVSTS', function (BukaSemula $bukaSemula) {
                if($bukaSemula->CA_INVSTS != '')
                    return $bukaSemula->statusAduan->descr;
                else
                    return '';
            })
            ->editColumn('CA_CASEID', function (BukaSemula $penugasan) {
                          return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
                })
            ->editColumn('CA_RCVDT', function (BukaSemula $bukaSemula) {
                return $bukaSemula->CA_RCVDT ? with(new Carbon($bukaSemula->CA_RCVDT))->format('d-m-Y h:i A') : '';
            })
            ->addColumn('tempoh', function (BukaSemula $bukaSemula) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
                $totalDuration = $bukaSemula->getduration($bukaSemula->CA_RCVDT, $bukaSemula->CA_CASEID);
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
                <a href="{{ url("bukasemula/{$CA_CASEID}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-pencil"></i></a>
            ')
            ->rawColumns(['CA_SUMMARY','CA_CASEID', 'tempoh', 'action'])
            ->filter(function ($query) use ($request) {
                if ($request->has('CA_CASEID')) {
                    $query->where('CA_CASEID', $request->get('CA_CASEID'));
                }
                if ($request->has('CA_SUMMARY')) {
                    $query->where('CA_SUMMARY', 'LIKE', "%{$request->get('CA_SUMMARY')}%");
                }
                if ($request->has('CA_AGAINSTNM')) {
                    $query->where('CA_AGAINSTNM', 'LIKE', "%{$request->get('CA_AGAINSTNM')}%");
                }
                if ($request->has('CA_RCVDT')) {
                    $query->where('CA_RCVDT', Carbon::parse($request->get('CA_RCVDT'))->format('Y-m-d'));
                }
                if ($request->has('CA_INVSTS')) {
                    $query->where('CA_INVSTS', $request->get('CA_INVSTS'));
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
         public function ShowSummary($CASEID)
    {
        $model = BukaSemula::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = BukaSemulaDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = BukaSemulaDoc::where(['CC_CASEID' => $CASEID])->get();
        return view('aduan.bukasemula.show_summary_modal', compact('model','trnsksi','img'));
        
    }
    
   
         public function PrintSummary($CASEID)
    {
        $model = BukaSemula::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = BukaSemulaDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = BukaSemulaDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('aduan.bukasemula.show_summary_modal', compact('model','trnsksi','img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }
}
