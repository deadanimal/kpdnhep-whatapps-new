<?php

namespace App\Http\Controllers\Aduan;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\PenutupanCaseDetail;
use App\Penutupan;
use App\PenutupanDoc;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use PDF;
use DB;
use App\Aduan\GabungRelation;

class TutupController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        return view('aduan.tutup.index');
    }

    public function getDataTable(DataTables $datatables, Request $request) {
//        $mPenutupan = Penutupan::whereBetween('CA_INVSTS', [3, 8])
//                ->whereNotIn('CA_INVSTS', [4, 5])
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $cawanganPutrajaya = \App\Branch::select('BR_BRNCD')
            ->where('BR_BRNCD', 'like', 'WHQR%')
            ->where('BR_STATUS', '1')->get()->toArray();
        if(Auth::user()->brn_cd == 'WHQR5'){
            $mPenutupan = Penutupan::whereIn('CA_INVSTS', [3, 12])
                ->whereIn('CA_BRNCD', $cawanganPutrajaya)
                ->orderBy('CA_CREDT', 'DESC');
        } else {
            $mPenutupan = Penutupan::whereIn('CA_INVSTS', [3, 12])
                ->where(['CA_BRNCD' => Auth::user()->brn_cd])
                ->orderBy('CA_CREDT', 'DESC');
        }
        $mUser = User::find(Auth::user()->id);
        
        if ($request->mobile) {
            return response()->json(['data' => $mPenutupan->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mPenutupan)
                ->addIndexColumn()
                ->editColumn('CA_SUMMARY', function(Penutupan $penutupan) {
                    if ($penutupan->CA_SUMMARY != '')
                        return implode(' ', array_slice(explode(' ', ucfirst($penutupan->CA_SUMMARY)), 0, 7)) . '...';
                    else
                        return '';
                })
                ->editColumn('CA_INVSTS', function(Penutupan $penutupan) {
                    if ($penutupan->CA_INVSTS != '')
                        return $penutupan->StatusAduan->descr;
                    else
                        return '';
                })
                ->editColumn('CA_RCVDT', function(Penutupan $penutupan) {
                    if ($penutupan->CA_RCVDT != '')
                        return Carbon::parse($penutupan->CA_RCVDT)->format('d-m-Y h:i A');
                    else
                        return '';
                })
                ->editColumn('CA_CASEID', function (Penutupan $penugasan) {
                      return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
                })
                ->editColumn('CA_INVBY', function (Penutupan $penutupan) {
                    if($penutupan->CA_INVBY != '') {
                        $Carian = $penutupan;
                        return view('aduan.carian.show_invby_link', compact('Carian'))->render();
                    }
                    else {
                        return '';
                    }
                })
                ->addColumn('tempoh', function(Penutupan $penutupan) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
                    $totalDuration = $penutupan->getduration($penutupan->CA_RCVDT, $penutupan->CA_CASEID);
                    // return $totalDuration;
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
                    <a href="{{ route("tutup.penutupanaduan", $CA_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
                ')
//                        <a href="{{ url(\'tutup\penutupan_aduan\', $CA_CASEID) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>')
                ->rawColumns(['CA_INVBY','CA_CASEID','CA_SUMMARY','tempoh','action'])
                ->filter(function ($query) use ($request) {
            if ($request->has('CA_CASEID')) {
                $query->where('CA_CASEID', 'LIKE', "%{$request->get('CA_CASEID')}%");
            }
            if ($request->has('CA_INVSTS')) {
                $query->where('CA_INVSTS', $request->get('CA_INVSTS'));
            }
            if ($request->has('CA_SUMMARY')) {
                $query->where('CA_SUMMARY', 'LIKE', "%{$request->get('CA_SUMMARY')}%");
            }
            if ($request->has('CA_AGAINSTNM')) {
                $query->where('CA_AGAINSTNM', 'LIKE', "%{$request->get('CA_AGAINSTNM')}%");
            }
        })
        ;
        return $datatables->make(true);
    }

    public function PenutupanAduan($CA_CASEID) {
        $mUser = User::find(Auth::User()->id);
        $mPenutupan = Penutupan::where(['CA_CASEID' => $CA_CASEID])->first();
//        $mPenutupan->CA_INVSTS = 9;
        $mPenutupanDetail = PenutupanCaseDetail::where(['CD_CASEID' => $CA_CASEID, 'CD_INVSTS' => '9'])->first();
        $mPenutupanDetailOld = PenutupanCaseDetail::where(['CD_CASEID' => $CA_CASEID, 'CD_CURSTS' => '1'])->first();
        $mGabungOne = DB::table('case_rel')->where(['CR_CASEID' => $CA_CASEID])->first();
        if ($mGabungOne) {
            $mGabungAll = DB::table('case_rel')->where(['CR_RELID' => $mGabungOne->CR_RELID])->get();
        } else {
            $mGabungAll = '';
        }
        return view('aduan.tutup.penutupan_aduan', compact('mUser', 'mPenutupan', 'CA_CASEID', 'mPenutupanDetail', 'mPenutupanDetailOld', 'mGabungAll'));
    }

    public function gettransaction($CA_CASEID) {
        $mPenutupanDetail = PenutupanCaseDetail::where(['CD_CASEID' => $CA_CASEID])->orderBy('CD_CREDT', 'DESC');

        $datatables = Datatables::of($mPenutupanDetail)
                ->addIndexColumn()
                ->editColumn('CD_INVSTS', function(PenutupanCaseDetail $mCaseDetail) {
                    if ($mCaseDetail->CD_INVSTS != '')
                        return $mCaseDetail->statusAduan->descr;
                    else
                        return '';
                })
                ->editColumn('CD_ACTFROM', function(PenutupanCaseDetail $mCaseDetail) {
//                    if ($mCaseDetail->CD_ACTFROM != '')
//                        return $mCaseDetail->userDaripada->name;
//                    else
//                        return '';
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
                ->editColumn('CD_ACTTO', function(PenutupanCaseDetail $mCaseDetail) {
//                    if ($mCaseDetail->CD_ACTTO != '')
//                        return $mCaseDetail->userKepada->name;
//                    else
//                        return '';
                    if ($mCaseDetail->CD_ACTTO != ''){
                        if($mCaseDetail->userKepada){
                            return $mCaseDetail->userKepada->name;
                        } else {
                            return $mCaseDetail->CD_ACTTO;
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('CD_CREDT', function(PenutupanCaseDetail $mCaseDetail) {
                    if ($mCaseDetail->CD_CREDT != '')
                        return Carbon::parse($mCaseDetail->CD_CREDT)->format('d-m-Y h:i A');
                    else
                        return '';
                })
                ->editColumn('CD_DOCATTCHID_PUBLIC', function(PenutupanCaseDetail $mCaseDetail) {
                    if ($mCaseDetail->CD_DOCATTCHID_PUBLIC != '')
                        return '<a href=' . Storage::disk('letter')->url($mCaseDetail->suratPublic->file_name_sys) . ' target="_blank">' . $mCaseDetail->suratPublic->file_name . '</a>';
                    else
                        return '';
                })
                ->editColumn('CD_DOCATTCHID_ADMIN', function(PenutupanCaseDetail $mCaseDetail) {
                    if ($mCaseDetail->CD_DOCATTCHID_ADMIN != '')
                        return '<a href=' . Storage::disk('letter')->url($mCaseDetail->suratAdmin->file_name_sys) . ' target="_blank">' . $mCaseDetail->suratAdmin->file_name . '</a>';
                    else
                        return '';
                })
                ->rawColumns(['CD_DOCATTCHID_ADMIN', 'CD_DOCATTCHID_PUBLIC']);

        return $datatables->make(true);
    }

    public function getattachment($CASEID) {
        $mPenutupDoc = PenutupanDoc::where('CC_CASEID', $CASEID)->where('CC_IMG_CAT', '1');
        $datatables = Datatables::of($mPenutupDoc)
                ->addIndexColumn()
//            ->editColumn('id', function(PenutupDoc $tutupDoc) {
//                if($tutupDoc->CC_DOCATTCHID != '')
//                    return $tutupDoc->attachment->id;
//                else
//                    return '';
//            })
//            ->editColumn('CC_IMG_NAME', function(PenutupDoc $tutupDoc) {
//                if($tutupDoc->CC_DOCATTCHID != '')
//                    return $tutupDoc->attachment->CC_IMG_NAME;
//                else
//                    return '';
//            })
//            ->editColumn('CC_REMARKS', function(PenutupDoc $tutupDoc) {
//                if($tutupDoc->CC_DOCATTCHID != '')
//                    return '<a href='.Storage::disk('local')->url($tutupDoc->attachment->file_name_sys).' target="_blank">'.$tutupDoc->attachment->file_name.'</a>';
//                else
//                    return '';
//            })
                ->editColumn('CC_IMG_NAME', function(PenutupanDoc $tutupDoc) {
                    if ($tutupDoc->CC_IMG_NAME != '')
                        return '<a href=' . Storage::disk('bahanpath')->url($tutupDoc->CC_PATH . $tutupDoc->CC_IMG) . ' target="_blank">' . $tutupDoc->CC_IMG_NAME . '</a>';
                    else
                        return '';
                })
                ->rawColumns(['CC_IMG_NAME'])
        ;
        return $datatables->make(true);
    }
    
    public function getGabungKes($CASEID) {
        $mPenutupan = Penutupan::where('CA_MERGE', $CASEID)->orderBy('CA_CREDT', 'desc');
        $datatables = Datatables::of($mPenutupan)
                ->addIndexColumn()
                ->editColumn('CA_SUMMARY', function(Penutupan $penutupan) {
                    if ($penutupan->CA_SUMMARY != '')
                        return implode(' ', array_slice(explode(' ', $penutupan->CA_SUMMARY), 0, 7)) . ' ...';
                    else
                        return '';
                })
                ->editColumn('CA_INVSTS', function (Penutupan $penutupan) {
                    if ($penutupan->CA_INVSTS != '')
                        return $penutupan->statusAduan->descr;
                    else
                        return '';
                })
                ->editColumn('CA_RCVDT', function (Penutupan $penutupan) {
            return $penutupan->CA_RCVDT ? with(new Carbon($penutupan->CA_RCVDT))->format('d-m-Y h:i A') : '';
        });
        return $datatables->make(true);
    }
    
    public function TutupAduan(Request $request, $CA_CASEID) {
//        $date = Carbon::now();
        $CheckRelation = DB::table('case_rel')->where('CR_CASEID',$CA_CASEID)->value('CR_RELID');
        if($CheckRelation) {
            $ArrCaseId = DB::table('case_rel')->where('CR_RELID',$CheckRelation)->pluck('CR_CASEID');
            foreach($ArrCaseId as $GetCaseId) {
                $Save = $this->Save($request, $GetCaseId);
            }
            if ($request->expectsJson()) {
                return response()->json(['data' => 'Aduan telah berjaya ditutup']);
            }
            $request->session()->flash('success', 'Aduan telah berjaya ditutup');
            return redirect('tutup');
        } else {
            $Save = $this->Save($request, $CA_CASEID);
            if($Save) {
                if ($request->expectsJson()) {
                    return response()->json(['data' => 'Aduan telah berjaya ditutup']);
                }
                $request->session()->flash('success', 'Aduan telah berjaya ditutup');
                return redirect('tutup');
            }
        }
//        $mPenutupan = Penutupan::where(['CA_CASEID' => $CA_CASEID])->first();
//        $mPenutupan->CA_INVBY = $request->CA_INVBY;
//        $mPenutupan->CA_ASGTO = $request->CA_INVBY;
//        $mPenutupan->CA_ASGDT = $date;
//        $mPenutupan->CA_INVSTS = 9;
//        $mPenutupan->CA_CLOSEBY = Auth::User()->id;
//        if ($mPenutupan->CA_INVSTS == 12) {
//            $mPenutupan->CA_INVSTS = 11;
//        } else {
//            $mPenutupan->CA_INVSTS = 9;
//        }
//        $mPenutupan->CA_CLOSEBY = $request->CA_CLOSEBY;
//        $mPenutupan->CA_CLOSEDT = Carbon::now();
//        if ($mPenutupan->save()) {
//            $mCaseDetailOld = PenutupanCaseDetail::where(['CD_CASEID' => $CA_CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
//            $mCaseDetail = new PenutupanCaseDetail;
//            $mCaseDetail->CD_CASEID = $mPenutupan->CA_CASEID;
//            if ($request->expectsJson()) {
//                $mCaseDetail->CD_TYPE = 'S'; // EZSTAR
//            } else {
//                $mCaseDetail->CD_TYPE = 'D';
//            }
//            $mCaseDetail->CD_DESC = $request->CD_DESC;
//            $mCaseDetail->CD_INVSTS = 9;    // PENGESAHAN PENUTUPAN
//            $mCaseDetail->CD_INVSTS = $mPenutupan->CA_INVSTS;
//            $mCaseDetail->CD_CASESTS = 2;
//            $mCaseDetail->CD_CURSTS = 1;
//            if ($mCaseDetail->save()) {
//                            event(new ComplaintClosed($user));    //Email PENUTUPAN
//                if ($request->expectsJson()) {
//                    return response()->json(['data' => 'Aduan telah berjaya ditutup']);
//                }
//                $request->session()->flash('success', 'Aduan telah berjaya ditutup');
//                return redirect('tutup');
//            }
//        }
    }
    
    public function Save($request, $CA_CASEID)
    {
        $model = Penutupan::where(['CA_CASEID' => $CA_CASEID])->first();
        if ($model->CA_INVSTS == 12) { // jika status : SELESAI (MAKLUMAT TIDAK LENGKAP)
            $model->CA_INVSTS = 11; // status : PENUTUPAN (MAKLUMAT TIDAK LENGKAP)
        } else {
            $model->CA_INVSTS = 9; // PENGESAHAN PENUTUPAN
        }
        $model->CA_CLOSEBY = $request->CA_CLOSEBY;
        $model->CA_CLOSEDT = Carbon::now();
        if ($model->save()) {
            PenutupanCaseDetail::where(['CD_CASEID' => $CA_CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
            $mCaseDetail = new PenutupanCaseDetail;
            $mCaseDetail->CD_CASEID = $model->CA_CASEID;
            if ($request->expectsJson()) {
                $mCaseDetail->CD_TYPE = 'S'; // EZSTAR
            } else {
                $mCaseDetail->CD_TYPE = 'D';
            }
            $mCaseDetail->CD_DESC = $request->CD_DESC;
            $mCaseDetail->CD_INVSTS = $model->CA_INVSTS;
            $mCaseDetail->CD_CASESTS = 2;
            $mCaseDetail->CD_CURSTS = 1;
            if ($mCaseDetail->save()) {
                return true;
            }
            return false;
        }
        return false;
    }
    
    public function getgabung(Request $request, $CA_CASEID)
    {
        $CheckRelation = GabungRelation::where('CR_CASEID', $CA_CASEID)->value('CR_RELID');
        if($CheckRelation) {
            $RelId = $CheckRelation;
        } else {
            $RelId = '0';
        }
        $mCaseRelation = GabungRelation::where('CR_RELID', $RelId);
        if ($request->mobile) {
            return response()->json(['data' => $mCaseRelation->get()->toArray()]);
        }
        $datatables = DataTables::of($mCaseRelation)
            ->addIndexColumn()
            ->editColumn('CA_SUMMARY', function(GabungRelation $gabungRelation) {
                return substr($gabungRelation->aduan->CA_SUMMARY, 0, 20) . '...';
            })
            ->editColumn('CA_RCVDT', function(GabungRelation $gabungRelation) {
                if ($gabungRelation->aduan->CA_RCVDT != '')
                    return Carbon::parse($gabungRelation->aduan->CA_RCVDT)->format('d-m-Y h:i A');
                else
                    return '';
            })
        ;
        return $datatables->make(true);
    }

    public function ShowSummary($CASEID) {
        $model = Penutupan::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PenutupanCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenutupanDoc::where(['CC_CASEID' => $CASEID])->get();
        return view('aduan.tutup.show_summary_modal', compact('model', 'trnsksi', 'img'));
    }

    public function PrintSummary($CASEID) {
        $model = Penutupan::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PenutupanCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenutupanDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('aduan.tutup.show_summary_modal', compact('model', 'trnsksi', 'img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }
    
    public function getdatatableuser(Request $request) {
        $mUser = User::with('role')
            ->select('id','username','name','state_cd','brn_cd')
            ->where(['user_cat' => '1', 'status' => '1']);
        
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
            ->editColumn('role.role_code', function (User $user) {
                if($user->role->role_code != '')
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
                if ($request->has('state_cd')) {
                    $query->where('state_cd', $request->get('state_cd'));
                }
                if ($request->has('brn_cd')) {
                    $query->where('brn_cd', $request->get('brn_cd'));
                }
                if ($request->has('role_code')) {
                    $query->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')->where('sys_user_access.role_code', $request->get('role_code'));
                }
            })
        ;
        return $datatables->make(true);
    }
    
    public function getuserdetail($id)
    {
        $userdetail = DB::table('sys_users')
            ->where('id', $id)
            ->pluck('id', 'name');
        return json_encode($userdetail);
    }
}
