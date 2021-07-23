<?php

namespace App\Http\Controllers\Integriti;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
use App\Integriti\IntegritiAdminDoc;
use App\User;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class IntegritiTugasSemulaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('integriti.tugassemula.index');
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
        $model = IntegritiAdmin::find($id);
        if ($model) {
            $mPenugasanSemula = IntegritiAdmin::
            // where(['CA_CASEID' => $CASEID])
            where(function ($query) use ($id, $model) {
                $query->where('IN_CASEID', $id)
                    ->orWhere('IN_CASEID', $model->IN_CASEID);
            })
            ->first();
            if ($mPenugasanSemula->IN_RCVBY == 'Sistem Online' || $mPenugasanSemula->IN_RCVBY == 'EzADU App') {
                $RcvBy = $mPenugasanSemula->IN_RCVBY;
            } else {
                $mUser = User::find($mPenugasanSemula->IN_RCVBY);
                if($mUser) {
                    $RcvBy = $mUser->name;
                } else {
                    $RcvBy = '';
                }
            }
            // dd($RcvBy);
            // $mGabungOne = DB::table('case_rel')->where(['CR_CASEID' => $CASEID])->first();
            $mGabungOne = DB::table('integriti_case_rel')->where(['IR_CASEID' => $model->IN_CASEID])->first();
            if ($mGabungOne) {
                // $mGabungAll = DB::table('case_rel')->where(['CR_RELID' => $mGabungOne->CR_RELID])->get();
                $mGabungAll = DB::table('integriti_case_rel')
                    ->where(['IR_RELID' => $mGabungOne->IR_RELID])
                    ->get();
            } else {
                $mGabungAll = '';
            }
            return view('integriti.tugassemula.edit', compact('mPenugasanSemula', 'RcvBy', 'mGabungAll'));
        } else {
            return redirect()->route('integrititugassemula.index');
        }
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
        // dd($request, $id);
        $this->validate($request, [
            'IN_CMPLCAT' => 'required',
            // 'CA_CMPLCD' => 'required',
            'ID_DESC' => 'required',
            'IN_INVBY' => 'required',
            // 'CA_INVSTS' => 'required',
        ],
        [
            'IN_CMPLCAT.required' => 'Ruangan Kategori Aduan diperlukan.',
            // 'CA_CMPLCD.required' => 'Ruangan Subkategori Aduan diperlukan.',
            'ID_DESC.required' => 'Ruangan Saranan diperlukan.',
            'IN_INVBY.required' => 'Ruangan Pegawai Penyiasat / Serbuan diperlukan.',
            // 'CA_INVSTS.required' => 'Ruangan Status Aduan diperlukan.',
        ]);
        $mPenugasanSemula = IntegritiAdmin::find($id);
        $mPenugasanSemula->IN_CMPLCAT = $request->input('IN_CMPLCAT');
        // $mPenugasanSemula->CA_CMPLCD = $request->input('CA_CMPLCD');
        $mPenugasanSemula->IN_INVBY = $request->input('IN_INVBY');
        // dd($request, $id, $mPenugasanSemula);
        if ($mPenugasanSemula->save()) {
            
    //            $mLetter = Letter::find('3'); // Contoh Surat Penugasan Semula
    //            $data = ['mLetter' => $mLetter];
    //            $pdf = PDF::loadView('ref.pdf', $data);
    //            
    //            $date = date('Ymdhis');
    //            $userid = Auth::user()->id;
    //            $filename = $CASEID.'_'.$userid.'_'.$date.'.pdf';
    //            Storage::disk('letter')->put($filename, $pdf->output());
    //            
    //            $mAttachment = new Attachment();
    //            $mAttachment->doc_title = 'Surat Penugasan Semula';
    //            $mAttachment->file_name = $filename;
    //            $mAttachment->file_name_sys = $filename;
            
            // $SuratPengadu = Letter::where(['letter_code' => $mPenugasanSemula->CA_INVSTS, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
            // $SuratPegawai = Letter::where(['letter_code' => $mPenugasanSemula->CA_INVSTS, 'letter_type' => '02', 'status' => '1'])->first(); // Templete Surat Kepada Pegawai
            // $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
            // $ContentSuratPegawai = $SuratPegawai->header . $SuratPegawai->body . $SuratPegawai->footer;
            // $ProfilPegawai = User::find($mPenugasanSemula->CA_INVBY);
            
            // if($mPenugasanSemula->CA_STATECD != ''){
            //     $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mPenugasanSemula->CA_STATECD])->first();
            //     $CA_STATECD = $StateNm->descr;
            // } else {
            //     $CA_STATECD = '';
            // }
            // if($mPenugasanSemula->CA_DISTCD != ''){
            //     $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mPenugasanSemula->CA_DISTCD])->first();
            //     $CA_DISTCD = $DestrictNm->descr;
            // } else {
            //     $CA_DISTCD = '';
            // }
            
            // if ($mPenugasanSemula->CA_INVSTS == '2') //  DALAM SIASATAN
            //     {    
            //         $patternsPengadu[1] = "#NAMAPENGADU#";
            //         $patternsPengadu[2] = "#ALAMATPENGADU#";
            //         $patternsPengadu[3] = "#POSKODPENGADU#";
            //         $patternsPengadu[4] = "#DAERAHPENGADU#";
            //         $patternsPengadu[5] = "#NEGERIPENGADU#";
            //         $patternsPengadu[6] = "#NOADUAN#";
            //         $patternsPengadu[7] = "#EMAILPEGAWAIPENYIASAT#";
            //         $patternsPengadu[8] = "#NAMAPEGAWAIPENYIASAT#";
            //         $patternsPengadu[9] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
            //         $replacementsPengadu[1] = $mPenugasanSemula->CA_NAME;
            //         $replacementsPengadu[2] = $mPenugasanSemula->CA_ADDR != ''? $mPenugasanSemula->CA_ADDR : '';
            //         $replacementsPengadu[3] = $mPenugasanSemula->CA_POSCD != ''? $mPenugasanSemula->CA_POSCD : '';
            //         $replacementsPengadu[4] = $CA_DISTCD;
            //         $replacementsPengadu[5] = $CA_STATECD;
            //         $replacementsPengadu[6] = $mPenugasanSemula->CA_CASEID;
            //         $replacementsPengadu[7] = $ProfilPegawai->email;
            //         $replacementsPengadu[8] = $ProfilPegawai->name;
            //         $replacementsPengadu[9] = $ProfilPegawai->cawangan->BR_BRNNM.'<br />'.$ProfilPegawai->cawangan->BR_ADDR1.'<br />'.$ProfilPegawai->cawangan->BR_ADDR2;
                    
            //         $tarikhPenerimaan = date('d/m/Y', strtotime($mPenugasanSemula->CA_RCVDT));
            //         $kodHariPenerimaan = date('N', strtotime($mPenugasanSemula->CA_RCVDT));
            //         $namaHariPenerimaan = Ref::GetDescr('156', $kodHariPenerimaan, 'ms');
            //         $patternsPegawai[1] = "#NEGERIPEGAWAI#";
            //         $patternsPegawai[2] = "#CAWANGANPEGAWAI#";
            //         $patternsPegawai[3] = "#TARIKHPENUGASAN#";
            //         $patternsPegawai[4] = "#MASAPENUGASAN#";
            //         $patternsPegawai[5] = "#NAMAPEGAWAIPENUGASAN#";
            //         $patternsPegawai[6] = "#NOADUAN#";
            //         $replacementsPegawai[1] = $ProfilPegawai->Negeri->descr;
            //         $replacementsPegawai[2] = $ProfilPegawai->cawangan->BR_BRNNM;
            //         $replacementsPegawai[3] = $tarikhPenerimaan.' ('.$namaHariPenerimaan.')';
            //         $replacementsPegawai[4] = date('h:i A', strtotime($mPenugasanSemula->CA_RCVDT));
            //         $replacementsPegawai[5] = $ProfilPegawai->name;
            //         $replacementsPegawai[6] = $mPenugasanSemula->CA_CASEID;
                    
            // } else if ($mPenugasanSemula->CA_INVSTS == '7') //  MAKLUMAT TIDAK LENGKAP
            //     {  
            // } else if($mPenugasanSemula->CA_INVSTS == '8') //  LUAR BIDANG KUASA
            //     {   
            // } else if($mPenugasanSemula->CA_INVSTS == '6') //  PERTANYAAN
            //     {   
            // }
            
            // $date = date('Ymdhis');
            // $userid = Auth::user()->id;
            
            // if(!empty($SuratPengadu)) {
            //     $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
            //     $arr_repPengadu = array("#", "#");
            //     $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
            //     $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

            //     $filename = $userid . '_' . $mPenugasanSemula->CA_CASEID . '_' . $date . '_1.pdf';
            //     Storage::disk('letter')->put($filename, $SuratPengaduHtml->output()); // Store PDF to storage

            //     $AttachSuratPengadu = new Attachment();
            //     $AttachSuratPengadu->doc_title = $SuratPengadu->title;
            //     $AttachSuratPengadu->file_name = $SuratPengadu->title;
            //     $AttachSuratPengadu->file_name_sys = $filename;
            //     if($AttachSuratPengadu->save()) {
            //         $SuratPengaduId = $AttachSuratPengadu->id;
            //     }
            // }else{
            //     $SuratPengaduId = NULL;
            // }
            
            // if(!empty($SuratPegawai)) {
            //     $SuratPegawaiReplace = preg_replace($patternsPegawai, $replacementsPegawai, urldecode($ContentSuratPegawai));
            //     $arr_repPegawai = array("#", "#");
            //     $SuratPegawaiFinal = str_replace($arr_repPegawai, "", $SuratPegawaiReplace); // SuratPegawai in HTML
            //     $SuratPegawaiHtml = PDF::loadHTML($SuratPegawaiFinal); // Generate PDF from HTML

            //     $filenameSuratPegawai = $userid . '_' . $mPenugasanSemula->CA_CASEID . '_' . $date . '_2.pdf';
            //     Storage::disk('letter')->put($filenameSuratPegawai, $SuratPegawaiHtml->output()); // Store PDF to storage

            //     $AttachSuratPegawai = new Attachment();
            //     $AttachSuratPegawai->doc_title = $SuratPegawai->title;
            //     $AttachSuratPegawai->file_name = $SuratPegawai->title;
            //     $AttachSuratPegawai->file_name_sys = $filenameSuratPegawai;
            //     if($AttachSuratPegawai->save()) {
            //         $SuratPegawaiId = $AttachSuratPegawai->id;
            //     }
            // }else{
            //     $SuratPegawaiId = NULL;
            // }
            
    //            if($mAttachment->save()) {
            // PenugasanSemulaDetail::where(['CD_CASEID' => $CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
            IntegritiAdminDetail::
                // where(['CD_CASEID' => $CASEID, 'CD_CURSTS' => '1'])
                where(function ($query) use ($id, $mPenugasanSemula) {
                    $query->where('ID_CASEID', $id)
                        ->orWhere('ID_CASEID', $mPenugasanSemula->IN_CASEID);
                })
                ->update(['ID_CURSTS' => '0']);
                $mPenugasanSemulaDetail = new IntegritiAdminDetail;
                $mPenugasanSemulaDetail->ID_CASEID = $mPenugasanSemula->IN_CASEID;
            //     if ($request->expectsJson()) {
            //         $mPenugasanSemulaDetail->CD_TYPE = 'S'; // EZSTAR
            //     } else {
            //         $mPenugasanSemulaDetail->CD_TYPE = 'D';
            //     }
                $mPenugasanSemulaDetail->ID_DESC = $request->input('ID_DESC');
            //     $mPenugasanSemulaDetail->CD_ACTTYPE = 'CLS';
                $mPenugasanSemulaDetail->ID_INVSTS = $mPenugasanSemula->IN_INVSTS;
                $mPenugasanSemulaDetail->ID_CASESTS = $mPenugasanSemula->IN_CASESTS;
                $mPenugasanSemulaDetail->ID_CURSTS = '1';
            //     $mPenugasanSemulaDetail->CD_ACTFROM = $mPenugasanSemula->CA_ASGBY;
                $mPenugasanSemulaDetail->ID_ACTFROM = Auth::User()->id;
            //     $mPenugasanSemulaDetail->CD_ACTTO = $mPenugasanSemula->CA_INVBY;
                $mPenugasanSemulaDetail->ID_ACTTO = $mPenugasanSemula->IN_INVBY;
            //     $mPenugasanSemulaDetail->CD_DOCATTCHID_PUBLIC = $SuratPengaduId;
            //     $mPenugasanSemulaDetail->CD_DOCATTCHID_ADMIN = $SuratPegawaiId;
                if($mPenugasanSemulaDetail->save()) {
                    if ($request->expectsJson()) {
                        return response()->json(['data' => 'Aduan telah berjaya diberi penugasan semula']);
                    }
                    $request->session()->flash('success', 'Aduan telah berjaya diberi penugasan semula');
    //                    return redirect()->back();
                    return redirect()->route('integrititugassemula.index');
                }
    //            }
        }
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
//        $this->middleware('locale');
        $this->middleware(['locale','auth']);
    }

    public function getdatatable(Request $request) {
        // $TempohPertama = \App\Ref::find(1244);
        // $TempohKedua = \App\Ref::find(1245);
        // $TempohKetiga = \App\Ref::find(1246);
        $mPenugasanSemula = IntegritiAdmin::where(
            [
                ['IN_INVSTS', '02'], 
                // ['IN_BRNCD', Auth::user()->brn_cd]
            ])
            ->orderBy('IN_RCVDT','DESC');
        
        if ($request->mobile) {
            return response()->json(['data' => $mPenugasanSemula->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mPenugasanSemula)
            ->addIndexColumn()
            ->editColumn('IN_SUMMARY', function(IntegritiAdmin $penugasanSemula) {
                if($penugasanSemula->IN_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $penugasanSemula->IN_SUMMARY), 0, 7)).' ...';
                else
                    return '';
//                    return '<div style="height:80px; overflow-x:hidden; overflow-y:scroll">'.$penugasanSemula->IN_SUMMARY.'</div>';
            })
            ->editColumn('IN_INVSTS', function(IntegritiAdmin $penugasanSemula) {
                // if($penugasanSemula->IN_INVSTS != '')
                if($penugasanSemula->invsts){
                    return $penugasanSemula->invsts->descr;
                }
                else {
                    return $penugasanSemula->IN_INVSTS;
                }
            })
            //   ->editColumn('IN_CASEID', function (PenugasanSemula $penugasan) {
            //         return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
            //     })
            ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                return view('integriti.base.summarylink', compact('integriti'))->render();
            })
            ->editColumn('IN_RCVDT', function(IntegritiAdmin $penugasanSemula) {
                return $penugasanSemula->IN_RCVDT ? with(new Carbon($penugasanSemula->IN_RCVDT))->format('d-m-Y h:i A') : '';
            })
            // ->editColumn('IN_INVBY', function (PenugasanSemula $penugasanSemula) {
            //     if($penugasanSemula->IN_INVBY != '') {
            //         $Carian = $penugasanSemula;
            //         return view('aduan.carian.show_invby_link', compact('Carian'))->render();
            //     } else {
            //         return '';
            //     }
            // })
            ->editColumn('IN_INVBY', function (IntegritiAdmin $penugasanSemula) {
                // if($penugasanSemula->IN_INVBY != '') {
                if($penugasanSemula->invby) {
                    // $Carian = $penugasanSemula;
                    // return view('aduan.carian.show_invby_link', compact('Carian'))->render();
                    return $penugasanSemula->invby->name;
                } else {
                    return $penugasanSemula->IN_INVBY;
                }
            })
            // ->addColumn('tempoh', function(PenugasanSemula $penugasanSemula) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
            //     $totalDuration = $penugasanSemula->getduration($penugasanSemula->IN_RCVDT, $penugasanSemula->IN_CASEID);
            //     if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
            //                 return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //             else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
            //                 return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //             else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
            //                 return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //             else if ($totalDuration > $TempohKetiga->code)
            //                 return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            // })
            // ->addColumn('action', '
            //     <a href="{{ url("tugas-semula/{$IN_CASEID}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
            //     <i class="fa fa-pencil"></i></a>
            // ')
            ->addColumn('action', '
                <a href="{{ route("integrititugassemula.edit", $id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini"><i class="fa fa-pencil"></i></a>
            ')
            // ->rawColumns(['IN_INVBY','IN_SUMMARY','IN_CASEID', 'tempoh', 'action'])
            ->rawColumns(['IN_CASEID','action'])
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
            })
        ;

        return $datatables->make(true);
    }

    public function getdatatableuser(Request $request) {
        $mUser = User::with('role')
            ->select(
                'id',
                'username',
                'name', 
                // 'state_cd','brn_cd',
                DB::raw(
                    '(select count(IN_CASEID) from integriti_case_info where IN_INVBY = sys_users.id AND IN_INVSTS = "02") as count_case'
                )
            )
            ->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')
            ->where([
                'user_cat' => '1', 
                'status' => '1', 
                // 'brn_cd' => Auth::user()->brn_cd
            ])
            ->whereIn('sys_user_access.role_code',[
                '800',
                // '410','440','450'
                '191','192','193'
            ])
            ;
        
        if ($request->mobile) {
            return response()->json(['data' => $mUser->get()->toArray()]);
        }
        $datatables = Datatables::of($mUser)
            ->addIndexColumn()
            // ->editColumn('state_cd', function(User $user) {
            //     if($user->state_cd != '')
            //     return $user->Negeri->descr;
            //     else
            //     return '';
            // })
            // ->editColumn('brn_cd', function(User $user) {
            //     if($user->brn_cd != '')
            //     return $user->Cawangan->BR_BRNNM;
            //     else
            //     return '';
            // })
//                ->editColumn('tugas', function(User $user) {
//                    return Penugasan::GetCountTugas($user->id);
//                })
            ->editColumn('role.role_code', function (User $user) {
                // if($user->role->role_code != '')
                if(count($user->role) == 1)
                    return User::ShowRoleName($user->role->role_code);
                else
                    return '';
            })
            // ->addColumn('action', 
            //     '<a class="btn btn-xs btn-primary" onclick="myFunction({{ $id }})"><i class="fa fa-arrow-down"></i></a>'
            // )
            ->addColumn('action', 
                '<a class="btn btn-xs btn-primary" onclick="carianpenyiasat({{ $id }})"><i class="fa fa-arrow-down"></i></a>'
            )
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
}
