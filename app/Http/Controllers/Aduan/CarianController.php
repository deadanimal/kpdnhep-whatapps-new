<?php

namespace App\Http\Controllers\Aduan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Aduan\Carian;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Penugasan;
use App\PenugasanDoc;
use App\PenugasanCaseDetail;
use DB;

class CarianController extends Controller
{
    public function __construct()
    {
//        $this->middleware(['auth']);
        $this->middleware(['auth', 'locale']);
    }

    public function admin()
    {
        return view('aduan.carian.admin');
    }

    public function GetDataTableAdmin(Request $request)
    { //dd($request);
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        if ($request['search'] == '1') {
            $mCarian = Carian::join('sys_brn as b', 'case_info.CA_BRNCD', '=', 'b.BR_BRNCD')
                ->leftJoin('sys_ref as c', 'c.code', '=', 'b.BR_STATECD')
                ->select('case_info.CA_STATUSPENGADU', 'case_info.CA_NATCD', 'case_info.CA_COUNTRYCD',
                    'case_info.CA_RCVTYP', 'case_info.CA_CMPLCAT', 'case_info.CA_SEXCD', 'case_info.CA_INVBY',
                    'case_info.CA_INVDT', 'case_info.CA_CASEID', 'case_info.CA_SUMMARY', 'case_info.CA_NAME',
                    'case_info.CA_DOCNO','case_info.CA_AGE',
                    'case_info.CA_INVSTS', 'case_info.CA_RCVDT', 'c.descr', 'b.BR_BRNNM',
                    'case_info.CA_FILEREF', 'case_info.CA_COMPLETEDT', 'case_info.CA_AGAINSTNM',
                    'case_info.CA_CLOSEDT', 'case_info.CA_CMPLCD', 'case_info.CA_ONLINECMPL_BANKCD',
                    'case_info.CA_ONLINECMPL_ACCNO', 'case_info.CA_ONLINECMPL_PROVIDER', 'case_info.CA_ONLINECMPL_PYMNTTYP',
                    'case_info.CA_CMPLKEYWORD', 'case_info.CA_AGAINST_PREMISE',
                    'case_info.CA_RESULT', 'case_info.CA_ANSWER', 'case_info.CA_SSP')
                ->where([
                    ['CA_CASEID', '<>', null],
                    ['CA_RCVTYP', '<>', null],
                    ['CA_RCVTYP', '<>', ''],
                    ['CA_CMPLCAT', '<>', ''],
                    ['c.cat', '=', 17],
                    ['CA_INVSTS', '!=', '10']
                ])
                ->orderBy('CA_RCVDT', 'desc');
        } else {
            $mCarian = [];
        }


        $datatables = DataTables::of($mCarian)
            ->addIndexColumn()
            ->editColumn('CA_CASEID', function (Carian $penugasan) {
                return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
            })
            ->editColumn('CA_INVSTS', function (Carian $Carian) {
                if ($Carian->CA_INVSTS != '')
                    return $Carian->StatusAduan->descr;
                else
                    return '';
            })
            ->editColumn('CA_COUNTRYCD', function (Carian $Carian) {
                if ($Carian->CA_COUNTRYCD != '')
                    return $Carian->CountryCd->descr;
                else
                    return '';
            })
            ->editColumn('CA_STATUSPENGADU', function (Carian $Carian) {
                if ($Carian->CA_STATUSPENGADU != '')
                    return $Carian->StatusPengadu->descr;
                else
                    return '';
            })
            ->editColumn('CA_RCVDT', function (Carian $Carian) {
                return $Carian->CA_RCVDT ? with(new Carbon($Carian->CA_RCVDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CA_COMPLETEDT', function (Carian $Carian) {
                return $Carian->CA_COMPLETEDT ? with(new Carbon($Carian->CA_COMPLETEDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CA_CLOSEDT', function (Carian $Carian) {
                return $Carian->CA_CLOSEDT ? with(new Carbon($Carian->CA_CLOSEDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CA_SUMMARY', function (Carian $Carian) {
//                    return '<div style="max-height:80px; overflow:auto">'.$Carian->CA_SUMMARY.'</div>';
                if ($Carian->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', ucfirst($Carian->CA_SUMMARY)), 0, 7)) . '...';
                else
                    return '';
            })
            ->addColumn('CA_SUMMARY_FULL', function (Carian $Carian) {
                if ($Carian->CA_SUMMARY != '')
                    return $Carian->CA_SUMMARY;
                else
                    return '';
            })
            ->editColumn('CA_INVBY', function (Carian $Carian) {
                if ($Carian->CA_INVBY != '')
                    return view('aduan.carian.show_invby_link', compact('Carian'))->render();
                else
                    return '';
            })
            ->editColumn('CA_SEXCD', function (Carian $Carian) {
                if ($Carian->CA_SEXCD != '')
                    return $Carian->SexCd->descr;
                else
                    return '';
            })
            ->editColumn('CA_CMPLCAT', function (Carian $Carian) {
//                    if($Carian->CA_CMPLCAT != '')
                if (!empty($Carian->CA_CMPLCAT))
                    if ($Carian->CmplCat)
                        return $Carian->CmplCat->descr;
                    else
                        $Carian->CA_CMPLCAT;
                else
                    return '';
            })
            ->editColumn('CA_CMPLCD', function (Carian $Carian) {
                if (!empty($Carian->CA_CMPLCD))
                    if ($Carian->CmplCd)
                        return $Carian->CmplCd->descr;
                    else
                        return $Carian->CA_CMPLCD;
                else
                    return '';
            })
            ->editColumn('CA_RCVTYP', function (Carian $Carian) {
                if ($Carian->CA_RCVTYP != '')
                    return $Carian->RcvTyp->descr;
                else
                    return '';
            })
            ->editColumn('CA_ONLINECMPL_BANKCD', function (Carian $Carian) {
                if (!empty($Carian->CA_ONLINECMPL_BANKCD)) {
                    if ($Carian->Bankcd) {
                        return $Carian->Bankcd->descr;
                    } else {
                        return $Carian->CA_ONLINECMPL_BANKCD;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CA_ONLINECMPL_PROVIDER', function (Carian $Carian) {
                if (!empty($Carian->CA_ONLINECMPL_PROVIDER)) {
                    if ($Carian->onlinecmplprovider) {
                        return $Carian->onlinecmplprovider->descr;
                    } else {
                        return $Carian->CA_ONLINECMPL_PROVIDER;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CA_ONLINECMPL_PYMNTTYP', function (Carian $Carian) {
                if (!empty($Carian->CA_ONLINECMPL_PYMNTTYP)) {
                    if ($Carian->onlinecmplpymnttyp) {
                        return $Carian->onlinecmplpymnttyp->descr;
                    } else {
                        return $Carian->CA_ONLINECMPL_PYMNTTYP;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CA_NATCD', function (Carian $Carian) {
                if ($Carian->CA_NATCD != '')
                    if ($Carian->CA_NATCD == 'mal')
                        return 'Warganegara';
                    else
                        return $Carian->NatCd->descr;
                else
                    return '';
            })
            ->editColumn('CA_CMPLKEYWORD', function (Carian $Carian) {
                if (!empty($Carian->CA_CMPLKEYWORD)) {
                    if ($Carian->cmplkeyword) {
                        return $Carian->cmplkeyword->descr;
                    } else {
                        return $Carian->CA_CMPLKEYWORD;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CA_NAME', function (Carian $Carian) {
                return $Carian->CA_NAME ?? '';
            })
            ->editColumn('CA_DOCNO', function (Carian $Carian) {
                return $Carian->CA_DOCNO ?? '';
            })
            ->editColumn('CA_AGE', function (Carian $Carian) {
                return $Carian->CA_AGE ?? '';
            })
            ->editColumn('CA_AGAINSTNM', function (Carian $Carian) {
                return $Carian->CA_AGAINSTNM ?? '';
            })
            ->editColumn('CA_FILEREF', function (Carian $Carian) {
                return $Carian->CA_FILEREF ?? '';
            })
            ->editColumn('CA_AGAINST_PREMISE', function (Carian $Carian) {
                if ($Carian->againstpremise) {
                    return $Carian->againstpremise->descr;
                } else {
                    return '';
                }
            })
            ->editColumn('CA_SSP', function (Carian $Carian) {
                switch ($Carian->CA_SSP) {
                    case 'YES':
                        return 'Ya';
                        break;
                    case 'NO':
                        return 'Tidak';
                        break;
                    default:
                        return '';
                        break;
                }
            })
            ->addColumn('tempoh', function (Carian $carian) use ($TempohPertama, $TempohKedua, $TempohKetiga) {
//                    return $Siasat->GetDuration($Siasat->CA_RCVDT, $Siasat->CA_CASEID);
                $mReceivedDetail = PenugasanCaseDetail::
                    where('CD_CASEID', $carian->CA_CASEID)
                    ->where('CD_INVSTS', '1')
                    ->orderBy('CD_CREDT', 'desc')
                    ->first();
                $totalDuration = $carian->getduration($mReceivedDetail->CD_CREDT ?? $carian->CA_RCVDT, $carian->CA_CASEID);
                if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                    return '<div style="background-color:#3F6; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                    return '<div style="background-color:#FF3;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                    return '<div style="background-color:#F0F; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohKetiga->code)
                    return '<div style="background-color:#F00; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else
                    return '<div style="background-color:#3F6; color: white;" align="center"><strong>' . 0 . '</strong></div>';
            })
//                ->addColumn('action', '<a href="{{ route("siasat.edit", $CA_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
            ->rawColumns(['CA_INVBY', 'CA_CASEID', 'CA_SUMMARY', 'tempoh'])
            ->filter(function ($query) use ($request) {
                if ($request->has('carian')) {
                    if ($request->get('carian') == 'CA_INVBY') {
                        $query->join('sys_users', 'case_info.CA_INVBY', '=', 'sys_users.id');
                        $query->where(function ($query) use ($request) {
                            $query->where('sys_users.name', 'LIKE', "%{$request->get('carian_text')}%");
                            $query->orWhere('sys_users.username', 'LIKE', "%{$request->get('carian_text')}%");
                        });
                    } elseif ($request->get('carian') == 'CA_ASGBY') {
                        $query->join('sys_users', 'case_info.CA_ASGBY', '=', 'sys_users.id')->where('sys_users.name', 'LIKE', "%{$request->get('carian_text')}%");
                    } elseif ($request->get('carian') == 'CA_RCVBY') {
                        $query->join('sys_users', 'case_info.CA_RCVBY', '=', 'sys_users.id')->where('sys_users.name', 'LIKE', "%{$request->get('carian_text')}%");
                    } elseif ($request->get('carian') == 'CA_SUMMARY') {
                        if (strpos($request->get('carian_text'), ',') !== false) {
                            $carian_text = str_replace(' ', '', $request->get('carian_text'));
                            $carian = explode(",", $carian_text);
                            $query->where(function ($query) use ($carian, $request) {
                                for ($i = 0; $i < count($carian); $i++) {
                                    if ($i == 0) {
                                        $query->where($request->get('carian'), 'LIKE', "%{$carian[$i]}");
                                    } else {
                                        $query->orWhere($request->get('carian'), 'LIKE', "%{$carian[$i]}");
                                    }
                                }
                            });
                        } else {
                            $query->where($request->get('carian'), 'LIKE', "%{$request->get('carian_text')}%");
                        }
                    } else {
                        $query->where($request->get('carian'), 'LIKE', "%{$request->get('carian_text')}%");
                    }
                }
                if ($request->has('test')) {
                    $newarray = [];
                    foreach ($request->get('test') as $key => $value) {
                        $fieldname = explode('~', $value);
                        $newarray[$fieldname[0]][] = $fieldname[1];
                    }
                    foreach ($newarray as $key => $newfield) {
                        $query->whereIn($key, $newfield);
                    }
                }
                if ($request->has('tarikh')) {
                    $query->where($request->get('tarikh'), '>=', date('Y-m-d 00:00:00', strtotime($request->get('date_start'))));
                    $query->where($request->get('tarikh'), '<=', date('Y-m-d 23:59:59', strtotime($request->get('date_end'))));
                }
                if ($request->has('state_cd')) {
                    $query->where('b.BR_STATECD', $request->get('state_cd'));
                }
                if ($request->has('brn_cd')) {
                    $query->where('b.BR_BRNCD', $request->get('brn_cd'));
                }
//                    if ($request->has('status')) {
//                        $status = explode('-', $request->get('status'));
//                        $query->where('status', $status[1]);
//                    }
//                    if ($request->has('role')) {
//                        $query->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')->where('sys_user_access.role_code', $request->get('role'));
//                    }
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

    public function ShowInvBy($id)
    {
//        dd('Berjaya');
        $model = \App\User::find($id);
//        $trnsksi = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->get();
//        $img = PenugasanDoc::where(['CC_CASEID' => $CASEID])->get();
        return view('aduan.carian.show_invby_modal', compact('model'));
    }

    public function GetSearchFieldCd($searchfieldcd)
    {
        $code = explode('~', $searchfieldcd);
        $mRef = DB::table('sys_ref')
            ->where(['cat' => $code[1], 'status' => 1])
            ->pluck('descr', 'code');
//        $mBrnList->prepend('-- SILA PILIH --', '');
//        dd($request);
//        $mBrnList = ['1'=>'Satu','2'=>'Dua','3'=>'Tiga'];
//        return json_encode($mRef);
        return $mRef;
    }

    public function negeri()
    {
        return view('aduan.carian.negeri');
    }

    public function GetDataTableNegeri()
    {

    }

    public function cawangan()
    {
        return view('aduan.carian.cawangan');
    }

    public function GetDataTableCawangan()
    {

    }

    public function ShowSummary($CASEID)
    {
        $model = Penugasan::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenugasanDoc::where(['CC_CASEID' => $CASEID])->get();
        return view('aduan.tugas.show_summary_modal', compact('model', 'trnsksi', 'img'));
    }

    public function PrintSummary($CASEID)
    {
        $model = Penugasan::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenugasanDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('aduan.tugas.show_summary_modal', compact('model', 'trnsksi', 'img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }

    public function GetDatatablePegawai(Datatables $datatables, Request $request)
    {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mCarian = Carian::join('sys_brn as b', 'case_info.CA_BRNCD', '=', 'b.BR_BRNCD')
            ->whereYear('CA_RCVDT', date('Y'))
            ->whereIn('CA_INVSTS', [1, 2, 3])
//                ->where('CA_BRNCD', Auth::user()->brn_cd)
            // ->orderBy('CA_RCVDT', 'DESC')
        ;

        $datatables = Datatables::of($mCarian)
            ->addIndexColumn()
            ->editColumn('CA_CASEID', function (Carian $penugasan) {
                return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
            })
            ->editColumn('CA_SUMMARY', function (Carian $Carian) {
                if ($Carian->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', ucfirst($Carian->CA_SUMMARY)), 0, 7)) . '...';
                else
                    return '';
            })
            ->editColumn('CA_INVBY', function (Carian $Carian) {
                if ($Carian->CA_INVBY != '')
                    return view('aduan.carian.show_invby_link', compact('Carian'))->render();
                else
                    return '';
            })
            ->editColumn('CA_INVSTS', function (Carian $Carian) {
                // if($Carian->CA_INVSTS != '')
                return $Carian->StatusAduan
                    ? $Carian->StatusAduan->descr
                    : '';
                // else
                //     return '';
            })
            ->editColumn('CA_RCVDT', function (Carian $Carian) {
                return $Carian->CA_RCVDT ? with(new Carbon($Carian->CA_RCVDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CA_BRNCD', function (Carian $Carian) {
                if ($Carian->CA_BRNCD != '') {
                    if ($Carian->BrnCd) {
                        return $Carian->BrnCd->BR_BRNNM;
                    } else {
                        return $Carian->CA_BRNCD;
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('tempoh', function (Carian $carian) use ($TempohPertama, $TempohKedua, $TempohKetiga) {
                $totalDuration = $carian->getduration($carian->CA_RCVDT, $carian->CA_CASEID);
                if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                    return '<div style="background-color:#3F6; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                    return '<div style="background-color:#FF3;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                    return '<div style="background-color:#F0F; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                else if ($totalDuration > $TempohKetiga->code)
                    return '<div style="background-color:#F00; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
            })
            ->rawColumns(['tempoh', 'CA_CASEID', 'CA_INVBY'])
            ->filter(function ($query) use ($request) {
                if ($request->has('CA_INVSTS')) {
                    $query->where('CA_INVSTS', $request->get('CA_INVSTS'));
                }
                if ($request->has('state')) {
                    $query->where('b.BR_STATECD', $request->get('state'));
                }
                if ($request->has('branch')) {
                    $query->where('b.BR_BRNCD', $request->get('branch'));
                }
                if ($request->has('tempoh_aduan')) {
                    $arrCA_CASEID = \App\Aduan\Carian::gettempoh($request->get('tempoh_aduan'));
                    $query->whereIn('CA_CASEID', $arrCA_CASEID);
                }
            });
        return $datatables->make(true);
    }
}
