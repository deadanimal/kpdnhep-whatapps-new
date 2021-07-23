<?php

namespace App\Http\Controllers\Integriti;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
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
use App\Attachment;
use App\Letter;

class IntegritiTutupController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        return view('integriti.tutup.index');
    }

    public function getDataTable(DataTables $datatables, Request $request) {

        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mPenutupan = IntegritiAdmin::whereIn('IN_IPSTS', ['03'])
            ->orderBy('IN_CREATED_AT', 'DESC');
        $mUser = User::find(Auth::user()->id);
        
        if ($request->mobile) {
            return response()->json(['data' => $mPenutupan->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mPenutupan)
                ->addIndexColumn()
                ->editColumn('IN_SUMMARY', function(IntegritiAdmin $penutupan) {
                    if ($penutupan->IN_SUMMARY != '')
                        return implode(' ', array_slice(explode(' ', ucfirst($penutupan->IN_SUMMARY)), 0, 7)) . '...';
                    else
                        return '';
                })
                ->editColumn('IN_IPSTS', function(IntegritiAdmin $penutupan) {
                    if ($penutupan->IN_IPSTS != '')
                        return $penutupan->ipsts->descr;
                    else
                        return '';
                })
                ->editColumn('IN_RCVDT', function(IntegritiAdmin $penutupan) {
                    if ($penutupan->IN_RCVDT != '')
                        return Carbon::parse($penutupan->IN_RCVDT)->format('d-m-Y h:i A');
                    else
                        return '';
                })
                ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                    return view('integriti.base.summarylink', compact('integriti'))->render();
                })
                ->editColumn('IN_INVBY', function (IntegritiAdmin $penutupan) {
                    if($penutupan->IN_INVBY != '') {
                        $Carian = $penutupan;
                        return view('aduan.carian.show_invby_link', compact('Carian'))->render();
                    }
                    else {
                        return '';
                    }
                })
                /* ->addColumn('tempoh', function(Penutupan $penutupan) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
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
                }) */
                ->addColumn('action', '
                    <a href="{{ route("integrititutup.penutupanaduan", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
                ')
                ->rawColumns(['IN_INVBY','IN_CASEID','IN_SUMMARY','action']) //'tempoh'
                ->filter(function ($query) use ($request) {
            if ($request->has('IN_CASEID')) {
                $query->where('IN_CASEID', 'LIKE', "%{$request->get('IN_CASEID')}%");
            }
            if ($request->has('IN_IPSTS')) {
                $query->where('IN_IPSTS', $request->get('IN_IPSTS'));
            }
            if ($request->has('IN_SUMMARY')) {
                $query->where('IN_SUMMARY', 'LIKE', "%{$request->get('IN_SUMMARY')}%");
            }
            if ($request->has('IN_AGAINSTNM')) {
                $query->where('IN_AGAINSTNM', 'LIKE', "%{$request->get('IN_AGAINSTNM')}%");
            }
        })
        ;
        return $datatables->make(true);
    }

    public function PenutupanAduan($id) {
        $mIntegriti = IntegritiAdmin::where(['id' => $id])->first();
        $IN_CASEID = $mIntegriti->IN_CASEID;
        $mUser = User::find(Auth::User()->id);
        $mPenutupan = IntegritiAdmin::where(['IN_CASEID' => $IN_CASEID])->first();
//        $mPenutupan->CA_INVSTS = 9;
        $mPenutupanDetail = IntegritiAdminDetail::where(['ID_CASEID' => $IN_CASEID, 'ID_INVSTS' => '9'])->first();
        $mPenutupanDetailOld = IntegritiAdminDetail::where(['ID_CASEID' => $IN_CASEID, 'ID_CURSTS' => '1'])->first();
        $mGabungOne = DB::table('integriti_case_rel')->where(['IR_CASEID' => $IN_CASEID])->first();
        if ($mGabungOne) {
            $mGabungAll = DB::table('integriti_case_rel')->where(['IR_RELID' => $mGabungOne->IR_RELID])->get();
        } else {
            $mGabungAll = '';
        }
        return view('integriti.tutup.penutupan_aduan', compact('mUser', 'mPenutupan', 'IN_CASEID', 'mPenutupanDetail', 'mPenutupanDetailOld', 'mGabungAll'));
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
    
    public function TutupAduan(Request $request, $id) {

        $this->validate($request, [
            'IN_ANSWER' => 'required_if:IN_CLOSE,YES',
        ],
        [
            'IN_ANSWER.required_if' => 'Ruangan Jawapan Kepada Pengadu diperlukan.',
        ]);

        $mIntegriti = IntegritiAdmin::where(['id' => $id])->first();
        $IN_CASEID = $mIntegriti->IN_CASEID;
        $CheckRelation = DB::table('integriti_case_rel')->where('IR_CASEID',$IN_CASEID)->value('IR_RELID');
        if($CheckRelation) {
            $ArrCaseId = DB::table('integriti_case_rel')->where('IR_RELID',$CheckRelation)->pluck('IR_CASEID');
            foreach($ArrCaseId as $GetCaseId) {
                $Save = $this->Save($request, $GetCaseId);
            }
            if ($request->expectsJson()) {
                return response()->json(['data' => 'Aduan telah berjaya ditutup']);
            }
            $request->session()->flash('success', 'Aduan telah berjaya ditutup');
            return redirect('integrititutup');
        } else {
            $Save = $this->Save($request, $IN_CASEID);
            if($Save) {
                if ($request->expectsJson()) {
                    return response()->json(['data' => 'Aduan telah berjaya ditutup']);
                }
                if ($request->IN_CLOSE == 'YES') {
                    $request->session()->flash('success', 'Aduan telah berjaya ditutup');
                } elseif ($request->IN_CLOSE == 'NO') {
                    $request->session()->flash('success', 'Aduan telah berjaya ditolak');
                }
                return redirect('integrititutup');
            }
        }
    }
    
    public function Save($request, $IN_CASEID)
    {
        $model = IntegritiAdmin::where(['IN_CASEID' => $IN_CASEID])->first();
        if ($request->IN_CLOSE == 'YES') { // keputusan : tutup
            $model->IN_IPSTS = '09'; // PENGESAHAN PENUTUPAN
            $model->IN_CLOSEBY = $request->IN_CLOSEBY;
            $model->IN_CLOSEDT = Carbon::now();
            $model->IN_ANSWER = $request->IN_ANSWER;
            $acttype = null;
            // generate surat makluman keputusan terhadap aduan
            $mResultJMA = IntegritiAdminDetail::where(['ID_CASEID' => $IN_CASEID, 'ID_INVSTS' => '02'])
                ->whereNull('ID_IPSTS')
                ->first();
            $SuratPengadu = Letter::
                where(['letter_code' => $model->IN_IPSTS, 'letter_type' => '01', 'status' => '1'])
                ->first();
            if($SuratPengadu){
                $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
            } else {
                $ContentSuratPengadu = '';
            }
            if($model->indistcd){
                $IN_DISTCD = $model->indistcd->descr;
            } else {
                $IN_DISTCD = '';
            }
            if($model->instatecd){
                $IN_STATECD = $model->instatecd->descr;
            } else {
                $IN_STATECD = '';
            }
            // Status siasatan : pengesahan penutupan
            if ($model->IN_IPSTS == '09')
            {
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
                $patternsPengadu[7] = "#JAWAPANKEPADAPENGADU#";
                $replacementsPengadu[1] = $model->IN_NAME;
                $replacementsPengadu[2] = 
                $model->IN_ADDR 
                    ? nl2br(htmlspecialchars($model->IN_ADDR)) 
                    : '';
                $replacementsPengadu[3] = $model->IN_POSTCD
                    // != ''? $model->IN_POSTCD : ''
                    ;
                $replacementsPengadu[4] = $IN_DISTCD;
                $replacementsPengadu[5] = $IN_STATECD;
                $replacementsPengadu[6] = $model->IN_CASEID;
                $replacementsPengadu[7] = 
                    $model->IN_ANSWER
                    ? nl2br(htmlspecialchars($model->IN_ANSWER)) 
                    : '';
            }
            $date = date('YmdHis');
            $userid = Auth::user()->id;
            if(!empty($SuratPengadu)) {
                $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
                $arr_repPengadu = array("#", "#");
                $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
                $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

                $filename = $userid . '_' . $model->id . '_' . $date . '_1.pdf';
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
        } else { // keputusan : tolak
            $model->IN_IPSTS = '02'; // PENUGASAN (DALAM SIASATAN)
            $model->IN_COMPLETEBY = null;
            $model->IN_COMPLETEDT = null;
            $acttype = 'KICK';
            $SuratPengaduId = NULL;
        }
        if ($model->save()) {
            IntegritiAdminDetail::where(['ID_CASEID' => $IN_CASEID, 'ID_CURSTS' => '1'])->update(['ID_CURSTS' => '0']);
            $mCaseDetail = new IntegritiAdminDetail;
            $mCaseDetail->ID_CASEID = $model->IN_CASEID;
            if ($request->expectsJson()) {
                $mCaseDetail->ID_TYPE = 'S'; // EZSTAR
            } else {
                $mCaseDetail->ID_TYPE = 'D';
            }
            $mCaseDetail->ID_DESC = $request->ID_DESC;
            $mCaseDetail->ID_ANSWER = $request->IN_ANSWER;
            $mCaseDetail->ID_ACTTYPE = $acttype;
            $mCaseDetail->ID_INVSTS = $model->IN_INVSTS;
            $mCaseDetail->ID_IPSTS = $model->IN_IPSTS;
            $mCaseDetail->ID_CASESTS = 2;
            $mCaseDetail->ID_CURSTS = 1;
            $mCaseDetail->ID_DOCATACHID_PUBLIC = $SuratPengaduId;
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
