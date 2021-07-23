<?php

namespace App\Http\Controllers\Integriti;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
use App\Integriti\IntegritiAdminDoc;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class IntegritiCarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('integriti.carian.index');
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

    public function __construct()
    {
//        $this->middleware(['auth']);
        $this->middleware(['auth','locale']);
    }

    public function getdatatable(Request $request)
    {
        // $TempohPertama = \App\Ref::find(1244);
        // $TempohKedua = \App\Ref::find(1245);
        // $TempohKetiga = \App\Ref::find(1246);
        if($request['search'] == '1') {
            $mCarian = IntegritiAdmin::select(
                'integriti_case_info.id','integriti_case_info.IN_STATUSPENGADU',
                'integriti_case_info.IN_NATCD','integriti_case_info.IN_COUNTRYCD',
                'integriti_case_info.IN_RCVTYP','integriti_case_info.IN_CMPLCAT',
                'integriti_case_info.IN_SEXCD','integriti_case_info.IN_INVBY',
                'integriti_case_info.IN_INVDT','integriti_case_info.IN_CASEID',
                'integriti_case_info.IN_SUMMARY','integriti_case_info.IN_NAME',
                'integriti_case_info.IN_INVSTS','integriti_case_info.IN_RCVDT',
                // 'c.descr','b.BR_BRNNM',
                'integriti_case_info.IN_COMPLETEDT','integriti_case_info.IN_AGAINSTNM',
                'integriti_case_info.IN_CLOSEDT','integriti_case_info.IN_CMPLCD',
                'integriti_case_info.IN_SUMMARY_TITLE','integriti_case_info.IN_EMAIL'
                )
                // ->join('sys_brn as b','case_info.IN_BRNCD','=','b.BR_BRNCD')
                // ->leftJoin('sys_ref as c','c.code','=','b.BR_STATECD')
                ->where([
                    ['IN_CASEID','<>',null],
                    ['IN_CASEID','<>',''],
                    // ['IN_RCVTYP','<>',null],
                    // ['IN_RCVTYP','<>',''],
                //     ['IN_CMPLCAT','<>',''],
                //     // ['c.cat','=',17],
                    ['IN_INVSTS','!=','010']
                ])
                ->orderBy('IN_RCVDT', 'desc');
        }else{
            $mCarian = [];
        }
        
        
        $datatables = DataTables::of($mCarian)
            ->addIndexColumn()
//                 ->editColumn('IN_CASEID', function (Carian $penugasan) {
//                     return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
//                 })
            ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                return view('integriti.base.summarylink', compact('integriti'))->render();
            })
            ->editColumn('IN_INVSTS', function (IntegritiAdmin $Carian) {
                // if($Carian->StatusAduan){
                if($Carian->invsts){
                    // return $Carian->StatusAduan->descr;
                    return $Carian->invsts->descr;
                } else {
                    return $Carian->IN_INVSTS;
                }
            })
            ->editColumn('IN_COUNTRYCD', function (IntegritiAdmin $Carian) {
                // if($Carian->IN_COUNTRYCD != '')
                if($Carian->countrycd)
                    return $Carian->countrycd->descr;
                else
                    return $Carian->IN_COUNTRYCD;
            })
            ->editColumn('IN_STATUSPENGADU', function (IntegritiAdmin $Carian) {
                // if($Carian->IN_STATUSPENGADU != '')
                if($Carian->statuspengadu != '')
                    return $Carian->statuspengadu->descr;
                else
                    return $Carian->IN_STATUSPENGADU;
            })
            ->editColumn('IN_RCVDT', function (IntegritiAdmin $Carian) {
                return $Carian->IN_RCVDT ? with(new Carbon($Carian->IN_RCVDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('IN_COMPLETEDT', function (IntegritiAdmin $Carian) {
                return $Carian->IN_COMPLETEDT ? with(new Carbon($Carian->IN_COMPLETEDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('IN_CLOSEDT', function (IntegritiAdmin $Carian) {
                return $Carian->IN_CLOSEDT ? with(new Carbon($Carian->IN_CLOSEDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('IN_SUMMARY', function(IntegritiAdmin $Carian) {
                // return '<div style="max-height:80px; overflow:auto">'.$Carian->IN_SUMMARY.'</div>';
                if($Carian->IN_SUMMARY != ''){
                    return implode(' ', array_slice(explode(' ', ucfirst($Carian->IN_SUMMARY)), 0, 7)).'...';
                }
                else{
                    return '';
                }
            })
//                 ->addColumn('IN_SUMMARY_FULL', function(Carian $Carian) {
//                     if($Carian->IN_SUMMARY != '')
//                         return $Carian->IN_SUMMARY;
//                     else
//                         return '';
//                 })
//                 ->editColumn('IN_INVBY', function(Carian $Carian) {
//                     if($Carian->IN_INVBY != '')
//                         return view('aduan.carian.show_invby_link', compact('Carian'))->render();
//                     else
//                         return '';
//                 })
            ->editColumn('IN_INVBY', function (IntegritiAdmin $penugasanSemula) {
                if($penugasanSemula->invby) {
                    return $penugasanSemula->invby->name;
                } else {
                    return $penugasanSemula->IN_INVBY;
                }
            })
            ->editColumn('IN_SEXCD', function(IntegritiAdmin $Carian) {
                // if($Carian->jantina){
                if($Carian->sexcd){
                    // return $Carian->jantina->descr;
                    return $Carian->sexcd->descr;
                } else {
                    return $Carian->IN_SEXCD;
                }
            })
            ->editColumn('IN_CMPLCAT', function(IntegritiAdmin $Carian) {
                // if($Carian->IN_CMPLCAT != '')
                // if(!empty($Carian->IN_CMPLCAT)){
                    if($Carian->cmplcat){
                        return $Carian->cmplcat->descr;
                    } else {
                        return $Carian->IN_CMPLCAT;
                    }
                // } else {
                    // return '';
                // }
            })
//                 ->editColumn('IN_CMPLCD', function(Carian $Carian) {
//                     if(!empty($Carian->IN_CMPLCD))
//                         if($Carian->CmplCd)
//                             return $Carian->CmplCd->descr;
//                         else
//                             return $Carian->IN_CMPLCD;
//                     else
//                         return '';
//                 })
                ->editColumn('IN_RCVTYP', function(IntegritiAdmin $Carian) {
                    if($Carian->rcvtyp){
                        return $Carian->rcvtyp->descr;
                    } else {
                        return $Carian->IN_RCVTYP;
                    }
                })
//                 ->editColumn('IN_ONLINECMPL_BANKCD', function(Carian $Carian) {
//                     if(!empty($Carian->IN_ONLINECMPL_BANKCD)){
//                         if($Carian->Bankcd){
//                             return $Carian->Bankcd->descr;
//                         } else {
//                             return $Carian->IN_ONLINECMPL_BANKCD;
//                         }
//                     } else {
//                         return '';
//                     }
//                 })
//                 ->editColumn('IN_NATCD', function(Carian $Carian) {
//                     if($Carian->IN_NATCD != '')
//                         if($Carian->IN_NATCD == 'mal')
//                             return 'Warganegara';
//                         else
//                             return $Carian->NatCd->descr;
//                     else
//                         return '';
//                 })
                ->editColumn('IN_NATCD', function(IntegritiAdmin $Carian) {
                    if($Carian->IN_NATCD != ''){
                        if($Carian->IN_NATCD == 'mal'){
                            return 'Warganegara';
                        } else if($Carian->natcd){
                            // return $Carian->NatCd->descr;
                            return $Carian->natcd->descr;
                        } else {
                            return $Carian->IN_NATCD;
                        }
                    } else {
                        return '';
                    }
                })
//                 ->addColumn('tempoh', function(Carian $carian) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
// //                    return $Siasat->GetDuration($Siasat->IN_RCVDT, $Siasat->IN_CASEID);
//                     $totalDuration = $carian->getduration($carian->IN_RCVDT, $carian->IN_CASEID);
//                     if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
//                         return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
//                     else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
//                         return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
//                     else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
//                         return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
//                     else if ($totalDuration > $TempohKetiga->code)
//                         return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
//                 })
// //                ->addColumn('action', '<a href="{{ route("siasat.edit", $IN_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
                ->rawColumns([
                    // 'IN_INVBY',
                    'IN_CASEID',
                    // 'IN_SUMMARY',
                    // 'tempoh'
                ])
                ->filter(function ($query) use ($request) {
                    if ($request->has('carian')) {
                        if($request->get('carian') == 'IN_INVBY') {
                            $query->join('sys_users', 'integriti_case_info.IN_INVBY', '=', 'sys_users.id');
                            $query->where(function ($query) use ($request){
                                $query->where('sys_users.name', 'LIKE', "%{$request->get('carian_text')}%" );
                                $query->orWhere('sys_users.username', 'LIKE', "%{$request->get('carian_text')}%" );
                            });
                        }elseif($request->get('carian') == 'IN_ASGBY') {
                            $query->join('sys_users', 'case_info.IN_ASGBY', '=', 'sys_users.id')->where('sys_users.name', 'LIKE', "%{$request->get('carian_text')}%" );
                        }elseif($request->get('carian') == 'IN_RCVBY') {
                            $query->join('sys_users', 'case_info.IN_RCVBY', '=', 'sys_users.id')->where('sys_users.name', 'LIKE', "%{$request->get('carian_text')}%" );
                        }elseif($request->get('carian') == 'IN_SUMMARY'){
                            if (strpos($request->get('carian_text'), ',') !== false) {
                                $carian_text = str_replace(' ', '', $request->get('carian_text'));
                                $carian = explode(",", $carian_text);
                                $query->where(function($query) use ($carian, $request){
                                    for($i = 0; $i < count($carian); $i++){
                                        if($i == 0){
                                            $query->where($request->get('carian'), 'LIKE', "%{$carian[$i]}");
                                        } else {
                                            $query->orWhere($request->get('carian'), 'LIKE', "%{$carian[$i]}");
                                        }
                                    }
                                });
                            } else {
                                $query->where($request->get('carian'), 'LIKE', "%{$request->get('carian_text')}%" );
                            }
                        }else{
                            $query->where($request->get('carian'), 'LIKE', "%{$request->get('carian_text')}%" );
                        }
                    }
                    if ($request->has('test')) {
                        $newarray = [];
                        foreach($request->get('test') as $key => $value) {
                            $fieldname = explode('~', $value);
                            $newarray[$fieldname[0]][] = $fieldname[1];
                        }
                        foreach($newarray as $key => $newfield) {
                            $query->whereIn($key, $newfield);
                        }
                    }
                    if ($request->has('tarikh')) {
                        $query->where($request->get('tarikh'), '>=', date('Y-m-d 00:00:00', strtotime($request->get('date_start'))));
                        $query->where($request->get('tarikh'), '<=', date('Y-m-d 23:59:59', strtotime($request->get('date_end'))));
                    }
                    if ($request->has('state_cd')) {
                        $query->join('sys_brn as b','integriti_case_info.IN_BRNCD','=','b.BR_BRNCD');
                        $query->leftJoin('sys_ref as c','c.code','=','b.BR_STATECD');
                        $query->where('b.BR_STATECD', $request->get('state_cd'));
                        $query->where('c.cat', '17');
                        if ($request->has('brn_cd')) {
                            $query->where('b.BR_BRNCD', $request->get('brn_cd'));
                        }
                    }
                    // if ($request->has('brn_cd')) {
                    //     $query->where('b.BR_BRNCD', $request->get('brn_cd'));
                    // }
//                    if ($request->has('status')) {
//                        $status = explode('-', $request->get('status'));
//                        $query->where('status', $status[1]);
//                    }
//                    if ($request->has('role')) {
//                        $query->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')->where('sys_user_access.role_code', $request->get('role'));
//                    }
                })
                ;
               
//        if ($IN_CASEID = $datatables->request->get('IN_CASEID')) {
//            $datatables->where('IN_CASEID', 'LIKE', "%$IN_CASEID%");
//        }
//        if ($IN_SUMMARY = $datatables->request->get('IN_SUMMARY')) {
//            $datatables->where('IN_SUMMARY', 'LIKE', "%$IN_SUMMARY%");
//        }
//        if ($IN_NAME = $datatables->request->get('IN_NAME')) {
//            $datatables->where('IN_NAME', 'LIKE', "%$IN_NAME%");
//        }
//        if ($IN_INVSTS = $datatables->request->get('IN_INVSTS')) {
//        $datatables->where('IN_INVSTS', 'LIKE', "%$IN_INVSTS%");
//        }
//         if ($IN_RCVDT = $datatables->request->get('IN_RCVDT')) {
//            $datatables->whereDate('IN_RCVDT', Carbon::parse($IN_RCVDT)->format('Y-m-d'));
//        }
        return $datatables->make(true);
    }
}
