<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\PenugasanSemula;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Aduan\PenugasanSemulaDetail;
use App\Aduan\PenugasanSemulaDoc;
use App\User;
use DB;
use PDF;
use App\Letter;
use Illuminate\Support\Facades\Storage;
use App\Attachment;
use Illuminate\Support\Facades\Auth;
use App\Ref;

class TugasSemulaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('aduan.tugas-semula.index');
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
     * @param  \App\PenugasanSemula  $penugasanSemula
     * @return \Illuminate\Http\Response
     */
    public function show(PenugasanSemula $penugasanSemula)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenugasanSemula  $penugasanSemula
     * @return \Illuminate\Http\Response
     */
//    public function edit(PenugasanSemula $penugasanSemula)
    public function edit($CASEID)
    {
        $mPenugasanSemula = PenugasanSemula::where(['CA_CASEID' => $CASEID])->first();
        if ($mPenugasanSemula->CA_RCVBY == 'Sistem Online' || $mPenugasanSemula->CA_RCVBY == 'EzADU App') {
            $RcvBy = $mPenugasanSemula->CA_RCVBY;
        } else {
            $mUser = User::find($mPenugasanSemula->CA_RCVBY);
            if($mUser) {
                $RcvBy = $mUser->name;
            } else {
                $RcvBy = '';
            }
        }
        
        $mGabungOne = DB::table('case_rel')->where(['CR_CASEID' => $CASEID])->first();
        if ($mGabungOne) {
            $mGabungAll = DB::table('case_rel')->where(['CR_RELID' => $mGabungOne->CR_RELID])->get();
        } else {
            $mGabungAll = '';
        }
        return view('aduan.tugas-semula.edit', compact('mPenugasanSemula', 'RcvBy', 'mGabungAll'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenugasanSemula  $penugasanSemula
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, PenugasanSemula $penugasanSemula)
    public function update(Request $request, $CASEID)
    {
        $this->validate($request, [
            'CA_CMPLCAT' => 'required',
            'CA_CMPLCD' => 'required',
            'CD_DESC' => 'required',
            'CA_INVBY' => 'required',
        ],
        [
            'CA_CMPLCAT.required' => 'Ruangan Kategori Aduan diperlukan.',
            'CA_CMPLCD.required' => 'Ruangan Subkategori Aduan diperlukan.',
            'CD_DESC.required' => 'Ruangan Saranan diperlukan.',
            'CA_INVBY.required' => 'Ruangan Pegawai Penyiasat / Serbuan diperlukan.',
        ]);
        $CheckRelation = DB::table('case_rel')->where('CR_CASEID', $CASEID)->value('CR_RELID');
        if($CheckRelation) {
            $ArrCaseId = DB::table('case_rel')->where('CR_RELID',$CheckRelation)->pluck('CR_CASEID');
            foreach($ArrCaseId as $GetCaseId) {
                $Save = $this->Save($request, $GetCaseId);
            }
            if ($request->expectsJson()) {
                return response()->json(['data' => 'Aduan telah berjaya diberi penugasan semula']);
            }
            $request->session()->flash('success', 'Aduan telah berjaya diberi penugasan semula');
            return redirect()->route('tugas-semula.index');
        } else {
            $Save = $this->Save($request, $CASEID);
            if($Save) {
                if ($request->expectsJson()) {
                    return response()->json(['data' => 'Aduan telah berjaya diberi penugasan semula']);
                }
                $request->session()->flash('success', 'Aduan telah berjaya diberi penugasan semula');
//                    return redirect()->back();
                return redirect()->route('tugas-semula.index');
            }
        }
//        $mPenugasanSemula = PenugasanSemula::find($CASEID);
//        $mPenugasanSemula->CA_CMPLCAT = $request->input('CA_CMPLCAT');
//        $mPenugasanSemula->CA_CMPLCD = $request->input('CA_CMPLCD');
//        $mPenugasanSemula->CA_INVBY = $request->input('CA_INVBY');
//        if ($mPenugasanSemula->save()) {
//            
////            $mLetter = Letter::find('3'); // Contoh Surat Penugasan Semula
////            $data = ['mLetter' => $mLetter];
////            $pdf = PDF::loadView('ref.pdf', $data);
////            
////            $date = date('Ymdhis');
////            $userid = Auth::user()->id;
////            $filename = $CASEID.'_'.$userid.'_'.$date.'.pdf';
////            Storage::disk('letter')->put($filename, $pdf->output());
////            
////            $mAttachment = new Attachment();
////            $mAttachment->doc_title = 'Surat Penugasan Semula';
////            $mAttachment->file_name = $filename;
////            $mAttachment->file_name_sys = $filename;
//            
//            $SuratPengadu = Letter::where(['letter_code' => $mPenugasanSemula->CA_INVSTS, 'letter_type' => '01', 'status' => '1'])->first(); // Templete Surat Kepada Pengadu
//            $SuratPegawai = Letter::where(['letter_code' => $mPenugasanSemula->CA_INVSTS, 'letter_type' => '02', 'status' => '1'])->first(); // Templete Surat Kepada Pegawai
//            $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
//            $ContentSuratPegawai = $SuratPegawai->header . $SuratPegawai->body . $SuratPegawai->footer;
//            $ProfilPegawai = User::find($mPenugasanSemula->CA_INVBY);
//            
//            if($mPenugasanSemula->CA_STATECD != ''){
//                $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mPenugasanSemula->CA_STATECD])->first();
//                $CA_STATECD = $StateNm->descr;
//            } else {
//                $CA_STATECD = '';
//            }
//            if($mPenugasanSemula->CA_DISTCD != ''){
//                $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mPenugasanSemula->CA_DISTCD])->first();
//                $CA_DISTCD = $DestrictNm->descr;
//            } else {
//                $CA_DISTCD = '';
//            }
//            
//            if ($mPenugasanSemula->CA_INVSTS == '2') //  DALAM SIASATAN
//                {    
//                    $patternsPengadu[1] = "#NAMAPENGADU#";
//                    $patternsPengadu[2] = "#ALAMATPENGADU#";
//                    $patternsPengadu[3] = "#POSKODPENGADU#";
//                    $patternsPengadu[4] = "#DAERAHPENGADU#";
//                    $patternsPengadu[5] = "#NEGERIPENGADU#";
//                    $patternsPengadu[6] = "#NOADUAN#";
//                    $patternsPengadu[7] = "#EMAILPEGAWAIPENYIASAT#";
//                    $patternsPengadu[8] = "#NAMAPEGAWAIPENYIASAT#";
//                    $patternsPengadu[9] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
//                    $replacementsPengadu[1] = $mPenugasanSemula->CA_NAME;
//                    $replacementsPengadu[2] = $mPenugasanSemula->CA_ADDR != ''? $mPenugasanSemula->CA_ADDR : '';
//                    $replacementsPengadu[3] = $mPenugasanSemula->CA_POSCD != ''? $mPenugasanSemula->CA_POSCD : '';
//                    $replacementsPengadu[4] = $CA_DISTCD;
//                    $replacementsPengadu[5] = $CA_STATECD;
//                    $replacementsPengadu[6] = $mPenugasanSemula->CA_CASEID;
//                    $replacementsPengadu[7] = $ProfilPegawai->email;
//                    $replacementsPengadu[8] = $ProfilPegawai->name;
//                    $replacementsPengadu[9] = $ProfilPegawai->cawangan->BR_BRNNM.'<br />'.$ProfilPegawai->cawangan->BR_ADDR1.'<br />'.$ProfilPegawai->cawangan->BR_ADDR2;
//                    
//                    $tarikhPenerimaan = date('d/m/Y', strtotime($mPenugasanSemula->CA_RCVDT));
//                    $kodHariPenerimaan = date('N', strtotime($mPenugasanSemula->CA_RCVDT));
//                    $namaHariPenerimaan = Ref::GetDescr('156', $kodHariPenerimaan, 'ms');
//                    $patternsPegawai[1] = "#NEGERIPEGAWAI#";
//                    $patternsPegawai[2] = "#CAWANGANPEGAWAI#";
//                    $patternsPegawai[3] = "#TARIKHPENUGASAN#";
//                    $patternsPegawai[4] = "#MASAPENUGASAN#";
//                    $patternsPegawai[5] = "#NAMAPEGAWAIPENUGASAN#";
//                    $patternsPegawai[6] = "#NOADUAN#";
//                    $replacementsPegawai[1] = $ProfilPegawai->Negeri->descr;
//                    $replacementsPegawai[2] = $ProfilPegawai->cawangan->BR_BRNNM;
//                    $replacementsPegawai[3] = $tarikhPenerimaan.' ('.$namaHariPenerimaan.')';
//                    $replacementsPegawai[4] = date('h:i A', strtotime($mPenugasanSemula->CA_RCVDT));
//                    $replacementsPegawai[5] = $ProfilPegawai->name;
//                    $replacementsPegawai[6] = $mPenugasanSemula->CA_CASEID;
//                    
//            } else if ($mPenugasanSemula->CA_INVSTS == '7') //  MAKLUMAT TIDAK LENGKAP
//                {  
//            } else if($mPenugasanSemula->CA_INVSTS == '8') //  LUAR BIDANG KUASA
//                {   
//            } else if($mPenugasanSemula->CA_INVSTS == '6') //  PERTANYAAN
//                {   
//            }
//            
//            $date = date('Ymdhis');
//            $userid = Auth::user()->id;
//            
//            if(!empty($SuratPengadu)) {
//                $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
//                $arr_repPengadu = array("#", "#");
//                $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
//                $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML
//
//                $filename = $userid . '_' . $mPenugasanSemula->CA_CASEID . '_' . $date . '_1.pdf';
//                Storage::disk('letter')->put($filename, $SuratPengaduHtml->output()); // Store PDF to storage
//
//                $AttachSuratPengadu = new Attachment();
//                $AttachSuratPengadu->doc_title = $SuratPengadu->title;
//                $AttachSuratPengadu->file_name = $SuratPengadu->title;
//                $AttachSuratPengadu->file_name_sys = $filename;
//                if($AttachSuratPengadu->save()) {
//                    $SuratPengaduId = $AttachSuratPengadu->id;
//                }
//            }else{
//                $SuratPengaduId = NULL;
//            }
//            
//            if(!empty($SuratPegawai)) {
//                $SuratPegawaiReplace = preg_replace($patternsPegawai, $replacementsPegawai, urldecode($ContentSuratPegawai));
//                $arr_repPegawai = array("#", "#");
//                $SuratPegawaiFinal = str_replace($arr_repPegawai, "", $SuratPegawaiReplace); // SuratPegawai in HTML
//                $SuratPegawaiHtml = PDF::loadHTML($SuratPegawaiFinal); // Generate PDF from HTML
//
//                $filenameSuratPegawai = $userid . '_' . $mPenugasanSemula->CA_CASEID . '_' . $date . '_2.pdf';
//                Storage::disk('letter')->put($filenameSuratPegawai, $SuratPegawaiHtml->output()); // Store PDF to storage
//
//                $AttachSuratPegawai = new Attachment();
//                $AttachSuratPegawai->doc_title = $SuratPegawai->title;
//                $AttachSuratPegawai->file_name = $SuratPegawai->title;
//                $AttachSuratPegawai->file_name_sys = $filenameSuratPegawai;
//                if($AttachSuratPegawai->save()) {
//                    $SuratPegawaiId = $AttachSuratPegawai->id;
//                }
//            }else{
//                $SuratPegawaiId = NULL;
//            }
//            
////            if($mAttachment->save()) {
//            PenugasanSemulaDetail::where(['CD_CASEID' => $CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
//                $mPenugasanSemulaDetail = new PenugasanSemulaDetail;
//                $mPenugasanSemulaDetail->CD_CASEID = $mPenugasanSemula->CA_CASEID;
//                if ($request->expectsJson()) {
//                    $mPenugasanSemulaDetail->CD_TYPE = 'S'; // EZSTAR
//                } else {
//                    $mPenugasanSemulaDetail->CD_TYPE = 'D';
//                }
//                $mPenugasanSemulaDetail->CD_DESC = $request->input('CD_DESC');
//                $mPenugasanSemulaDetail->CD_ACTTYPE = 'CLS';
//                $mPenugasanSemulaDetail->CD_INVSTS = $mPenugasanSemula->CA_INVSTS;
//                $mPenugasanSemulaDetail->CD_CASESTS = $mPenugasanSemula->CA_CASESTS;
//                $mPenugasanSemulaDetail->CD_CURSTS = '1';
//                $mPenugasanSemulaDetail->CD_ACTFROM = $mPenugasanSemula->CA_ASGBY;
//                $mPenugasanSemulaDetail->CD_ACTTO = $mPenugasanSemula->CA_INVBY;
//                $mPenugasanSemulaDetail->CD_DOCATTCHID_PUBLIC = $SuratPengaduId;
//                $mPenugasanSemulaDetail->CD_DOCATTCHID_ADMIN = $SuratPegawaiId;
//                if($mPenugasanSemulaDetail->save()) {
//                    if ($request->expectsJson()) {
//                        return response()->json(['data' => 'Aduan telah berjaya diberi penugasan semula']);
//                    }
//                    $request->session()->flash('success', 'Aduan telah berjaya diberi penugasan semula');
////                    return redirect()->back();
//                    return redirect()->route('tugas-semula.index');
//                }
////            }
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PenugasanSemula  $penugasanSemula
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenugasanSemula $penugasanSemula)
    {
        //
    }
    
    public function getdatatablecase(Request $request) {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mPenugasanSemula = PenugasanSemula::where([['CA_INVSTS', 2], ['CA_BRNCD', Auth::user()->brn_cd]])
            ->orderBy('CA_RCVDT','DESC');
        
        if ($request->mobile) {
            return response()->json(['data' => $mPenugasanSemula->offset($request->offset)->limit($request->count)->get()->toArray()]);
        }
        $datatables = DataTables::of($mPenugasanSemula)
            ->addIndexColumn()
            ->editColumn('CA_SUMMARY', function(PenugasanSemula $penugasanSemula) {
                if($penugasanSemula->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $penugasanSemula->CA_SUMMARY), 0, 7)).' ...';
                else
                    return '';
//                    return '<div style="height:80px; overflow-x:hidden; overflow-y:scroll">'.$penugasanSemula->CA_SUMMARY.'</div>';
            })
            ->editColumn('CA_INVSTS', function(PenugasanSemula $penugasanSemula) {
                if($penugasanSemula->CA_INVSTS != '')
                    return $penugasanSemula->statusAduan->descr;
                else
                    return '';
            })
              ->editColumn('CA_CASEID', function (PenugasanSemula $penugasan) {
                    return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
                })
            ->editColumn('CA_RCVDT', function(PenugasanSemula $penugasanSemula) {
                return $penugasanSemula->CA_RCVDT ? with(new Carbon($penugasanSemula->CA_RCVDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CA_INVBY', function (PenugasanSemula $penugasanSemula) {
                if($penugasanSemula->CA_INVBY != '') {
                    $Carian = $penugasanSemula;
                    return view('aduan.carian.show_invby_link', compact('Carian'))->render();
                } else {
                    return '';
                }
            })
            ->addColumn('tempoh', function(PenugasanSemula $penugasanSemula) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
                $totalDuration = $penugasanSemula->getduration($penugasanSemula->CA_RCVDT, $penugasanSemula->CA_CASEID);
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
                <a href="{{ url("tugas-semula/{$CA_CASEID}/edit") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-pencil"></i></a>
            ')
            ->rawColumns(['CA_INVBY','CA_SUMMARY','CA_CASEID', 'tempoh', 'action'])
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
            })
        ;
        
//        if ($CA_CASEID = $datatables->request->get('CA_CASEID')) {
//            $datatables->where('CA_CASEID', 'LIKE', "%$CA_CASEID%");
//        }
//        if ($CA_SUMMARY = $datatables->request->get('CA_SUMMARY')) {
//            $datatables->where('CA_SUMMARY', 'LIKE', "%$CA_SUMMARY%");
//        }
//        if ($CA_AGAINSTNM = $datatables->request->get('CA_AGAINSTNM')) {
//            $datatables->where('CA_AGAINSTNM', 'LIKE', "%$CA_AGAINSTNM%");
//        }
//        if ($CA_RCVDT = $datatables->request->get('CA_RCVDT')) {
//            $datatables->whereDate('CA_RCVDT', Carbon::parse($CA_RCVDT)->format('Y-m-d'));
//        }
//        if ($CA_INVSTS = $datatables->request->get('CA_INVSTS')) {
//            $datatables->where('CA_INVSTS', '=', $CA_INVSTS);
//        }

        return $datatables->make(true);
    }
    
    public function getcmpllist($CMPLCAT) {
        $mCatList = DB::table('sys_ref')
            ->where('cat', '634')
            ->where('code', 'like', "$CMPLCAT%")
            ->orderBy('sort')
            ->pluck('code', 'descr')
            ->prepend('', '-- SILA PILIH --');
        return json_encode($mCatList);
    }
    
    public function getdatatableuser(Request $request) {
        $mUser = User::where('user_cat', '1')->where('status', '1');
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
            ->addColumn('action', '
                <a class="btn btn-xs btn-primary" onclick="carianpenyiasat({{ $id }})"><i class="fa fa-arrow-down"></i></a>
            ')
            ->filter(function ($query) use ($request) {
                if ($request->has('icnew')) {
                    $query->where('icnew', 'like', "%{$request->get('icnew')}%");
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
            })
        ;

//        if ($icnew = $datatables->request->get('icnew')) {
//            $datatables->where('icnew', 'LIKE', "%$icnew%");
//        }
//        if ($name = $datatables->request->get('name')) {
//            $datatables->where('name', 'LIKE', "%$name%");
//        }
//        if ($state_cd = $datatables->request->get('state_cd')) {
//            $datatables->where('state_cd', $state_cd);
//        }
//        if ($brn_cd = $datatables->request->get('brn_cd')) {
//            $datatables->where('brn_cd', $brn_cd);
//        }
        return $datatables->make(true);
    }
    
    public function getuserdetail($id)
    {
        $UserDetail = DB::table('sys_users')
            ->where('id', $id)
            ->pluck('id', 'name');
        return json_encode($UserDetail);
    }
    
    public function getdatatableattachment($CASEID)
    {
        $mPenugasanSemulaDoc = PenugasanSemulaDoc::where('CC_CASEID', $CASEID)->where('CC_IMG_CAT', '1');
        $datatables = Datatables::of($mPenugasanSemulaDoc)
            ->addIndexColumn()
//            ->editColumn('id', function(PenugasanSemulaDoc $penugasanSemulaDoc) {
//                if($penugasanSemulaDoc->CC_DOCATTCHID != '')
//                    return $penugasanSemulaDoc->attachment->id;
//                else
//                    return '';
//            })
//            ->editColumn('doc_title', function(PenugasanSemulaDoc $penugasanSemulaDoc) {
//                if($penugasanSemulaDoc->CC_DOCATTCHID != '')
//                    return $penugasanSemulaDoc->attachment->doc_title;
//                else
//                    return '';
//            })
//            ->editColumn('file_name_sys', function(PenugasanSemulaDoc $penugasanSemulaDoc) {
//                if($penugasanSemulaDoc->CC_DOCATTCHID != '')
//                    return '<a href='.Storage::disk('public')->url($penugasanSemulaDoc->attachment->file_name_sys).' target="_blank">'.$penugasanSemulaDoc->attachment->file_name.'</a>';
//                else
//                    return '';
//            })
            ->editColumn('CC_IMG_NAME', function(PenugasanSemulaDoc $penugasanSemulaDoc) {
                if($penugasanSemulaDoc->CC_IMG_NAME != '')
                    return '<a href='.Storage::disk('bahanpath')->url($penugasanSemulaDoc->CC_PATH.$penugasanSemulaDoc->CC_IMG).' target="_blank">'.$penugasanSemulaDoc->CC_IMG_NAME.'</a>';
                else
                    return '';
            })
//            ->editColumn('updated_at', function(PenugasanSemulaDoc $penugasanSemulaDoc) {
//                if($penugasanSemulaDoc->CC_DOCATTCHID != '')
//                    return $penugasanSemulaDoc->attachment->updated_at ? with(new Carbon($penugasanSemulaDoc->attachment->updated_at))->format('d-m-Y h:i A') : '';
//                else
//                    return '';
//            })
            ->editColumn('updated_at', function(PenugasanSemulaDoc $penugasanSemulaDoc) {
                if($penugasanSemulaDoc->updated_at != '')
                    return $penugasanSemulaDoc->updated_at ? with(new Carbon($penugasanSemulaDoc->updated_at))->format('d-m-Y h:i A') : '';
                else
                    return '';
            })
            ->rawColumns(['CC_IMG_NAME'])
        ;
        
        return $datatables->make(true);
    }
    
    public function getdatatablemergecase($CASEID) {
        $mPenugasanSemula = PenugasanSemula::where('CA_MERGE', $CASEID)->orderBy('CA_CREDT', 'asc');
        $datatables = Datatables::of($mPenugasanSemula)
            ->addIndexColumn()
            ->editColumn('CA_SUMMARY', function(PenugasanSemula $penugasanSemula) {
                if($penugasanSemula->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $penugasanSemula->CA_SUMMARY), 0, 7)).' ...';
                else
                    return '';
            })
            ->editColumn('CA_INVSTS', function(PenugasanSemula $penugasanSemula) {
                if($penugasanSemula->CA_INVSTS != '')
                    return $penugasanSemula->statusAduan->descr;
                else
                    return '';
            })
            ->editColumn('CA_RCVDT', function(PenugasanSemula $penugasanSemula) {
                return $penugasanSemula->CA_RCVDT ? with(new Carbon($penugasanSemula->CA_RCVDT))->format('d-m-Y h:i A') : '';
            })
        ;
        return $datatables->make(true);
    }
    
    public function getdatatabletransaction($CASEID)
    {
        $mPenugasanSemulaDetail = PenugasanSemulaDetail::where(['CD_CASEID'=>$CASEID])->orderBy('CD_CREDT', 'DESC');
        
        $datatables = Datatables::of($mPenugasanSemulaDetail)
            ->addIndexColumn()
            ->editColumn('CD_INVSTS', function(PenugasanSemulaDetail $penugasanSemulaDetail) {
                if($penugasanSemulaDetail->CD_INVSTS != '')
                    return $penugasanSemulaDetail->statusaduan->descr;
                else
                    return '';
            })
            ->editColumn('CD_ACTFROM', function(PenugasanSemulaDetail $penugasanSemulaDetail) {
                if($penugasanSemulaDetail->CD_ACTFROM != '')
                    if ($penugasanSemulaDetail->actfrom) {
                        return $penugasanSemulaDetail->actfrom->name;
                    } else {
                        return $penugasanSemulaDetail->CD_ACTFROM;
                    }
                else
                    return '';
            })
            ->editColumn('CD_ACTTO', function(PenugasanSemulaDetail $penugasanSemulaDetail) {
                $kepada = '';
                if($penugasanSemulaDetail->CD_ACTTO != '') {
                    if ($penugasanSemulaDetail->actto) {
                        $kepada = $penugasanSemulaDetail->actto->name;
                    } else {
                        $kepada = $penugasanSemulaDetail->CD_ACTTO;
                    }
                    if($penugasanSemulaDetail->CD_DOCATTCHID_ADMIN != '') {
                        $kepada .= '<br /><a href='.Storage::disk('letter')->url($penugasanSemulaDetail->suratadmin->file_name_sys).' target="_blank">'.$penugasanSemulaDetail->suratadmin->doc_title.'</a>';
                    }
                }
                return $kepada;
            })
            ->editColumn('CD_DOCATTCHID_PUBLIC', function(PenugasanSemulaDetail $penugasanSemulaDetail) {
                $suratkpdpengadu = '';
                if ($penugasanSemulaDetail->CD_DOCATTCHID_PUBLIC)
//                    return $penugasanSemulaDetail->CD_DOCATTCHID_PUBLIC;
                    $suratkpdpengadu .= '<a href='.Storage::disk('letter')->url($penugasanSemulaDetail->suratpublic->file_name_sys).' target="_blank">'.$penugasanSemulaDetail->suratpublic->doc_title.'</a>';
                else
                    $suratkpdpengadu = '';
                return $suratkpdpengadu;
            })
            ->editColumn('CD_CREDT', function(PenugasanSemulaDetail $penugasanSemulaDetail) {
                return $penugasanSemulaDetail->CD_CREDT ? with(new Carbon($penugasanSemulaDetail->CD_CREDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CD_DESC', function(PenugasanSemulaDetail $penugasanSemulaDetail) {
                if($penugasanSemulaDetail->CD_CASEID != '')
                    return $penugasanSemulaDetail->CD_DESC;
                else
                    return '';
            })
            ->rawColumns(['CD_ACTTO', 'CD_DOCATTCHID_PUBLIC']);
        return $datatables->make(true);
    }
     public function ShowSummary($CASEID)
    {
        $model = PenugasanSemula::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PenugasanSemulaDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenugasanSemulaDoc::where(['CC_CASEID' => $CASEID])->get();
        return view('aduan.tugas-semula.show_summary_modal', compact('model','trnsksi','img'));
        
//        $model = Penyiasatan::where(['CA_CASEID' => $CASEID])->first();
//        return view('aduan.siasat.show_summary_modal', compact('model'));
    }
    
   
         public function PrintSummary($CASEID)
    {
        $model = PenugasanSemula::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PenugasanSemulaDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = PenugasanSemulaDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('aduan.tugas-semula.show_summary_modal', compact('model','trnsksi','img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }
    
    public function Save($request, $CA_CASEID){
        $mPenugasanSemula = PenugasanSemula::find($CA_CASEID);
        $mPenugasanSemula->CA_CMPLCAT = $request->input('CA_CMPLCAT');
        $mPenugasanSemula->CA_CMPLCD = $request->input('CA_CMPLCD');
        $mPenugasanSemula->CA_INVBY = $request->input('CA_INVBY');
        $mPenugasanSemula->CA_FILEREF = $request->input('CA_FILEREF');
        if ($mPenugasanSemula->save()) {
            $SuratPengadu = Letter::where([
                'letter_code' => $mPenugasanSemula->CA_INVSTS, 
                'letter_type' => '01', 
                'status' => '1',
                'letter_cat' => '2'])->first(); // Templete Surat Kepada Pengadu
            // custom data: letter_cat = '2'
            $SuratPegawai = Letter::where(['letter_code' => $mPenugasanSemula->CA_INVSTS, 'letter_type' => '02', 'status' => '1'])->first(); // Templete Surat Kepada Pegawai
            if($SuratPengadu){
                $ContentSuratPengadu = $SuratPengadu->header . $SuratPengadu->body . $SuratPengadu->footer;
            } else {
                $ContentSuratPengadu = NULL;
            }
            if($SuratPegawai){
                $ContentSuratPegawai = $SuratPegawai->header . $SuratPegawai->body . $SuratPegawai->footer;
            } else {
                $ContentSuratPegawai = NULL;
            }
            $ProfilPegawai = User::find($mPenugasanSemula->CA_INVBY);
            
            if(!empty(trim($mPenugasanSemula->CA_STATECD))){
                $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mPenugasanSemula->CA_STATECD])->first();
                if (!$StateNm) {
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'334','code'=>$mPenugasanSemula->CA_STATECD])->first();
                }
                $CA_STATECD = $StateNm->descr;
            } else {
                $CA_STATECD = '';
            }
            if(!empty(trim($mPenugasanSemula->CA_DISTCD))){
                $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mPenugasanSemula->CA_DISTCD])->first();
                if (!$DestrictNm){
                    $CA_DISTCD = $mPenugasanSemula->CA_DISTCD;
                } else {
                    $CA_DISTCD = $DestrictNm->descr;
                }
            } else {
                $CA_DISTCD = '';
            }
            
            if ($mPenugasanSemula->CA_INVSTS == '2') { //  DALAM SIASATAN
                $patternsPengadu[1] = "#NAMAPENGADU#";
                $patternsPengadu[2] = "#ALAMATPENGADU#";
                $patternsPengadu[3] = "#POSKODPENGADU#";
                $patternsPengadu[4] = "#DAERAHPENGADU#";
                $patternsPengadu[5] = "#NEGERIPENGADU#";
                $patternsPengadu[6] = "#NOADUAN#";
//                $patternsPengadu[7] = "#NOTELPEJABATPEGAWAI#";
                $patternsPengadu[7] = "#NOTELPEJABATPEGAWAIPENYIASAT#";
                $patternsPengadu[8] = "#EMAILPEGAWAIPENYIASAT#";
                $patternsPengadu[9] = "#NAMAPEGAWAIPENYIASAT#";
                $patternsPengadu[10] = "#ALAMATCAWANGANPEGAWAIPENYIASAT#";
                $patternsPengadu[11] = "#TARIKHPENUGASANSEMULA#";
                $replacementsPengadu[1] = $mPenugasanSemula->CA_NAME;
                $replacementsPengadu[2] = $mPenugasanSemula->CA_ADDR != ''? $mPenugasanSemula->CA_ADDR : '';
                $replacementsPengadu[3] = $mPenugasanSemula->CA_POSCD != ''? $mPenugasanSemula->CA_POSCD : '';
                $replacementsPengadu[4] = $CA_DISTCD;
                $replacementsPengadu[5] = $CA_STATECD;
                $replacementsPengadu[6] = $mPenugasanSemula->CA_CASEID;
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

//                $tarikhPenerimaan = date('d/m/Y', strtotime($mPenugasanSemula->CA_RCVDT));
//                $kodHariPenerimaan = date('N', strtotime($mPenugasanSemula->CA_RCVDT));
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
//                $replacementsPegawai[4] = date('h:i A', strtotime($mPenugasanSemula->CA_RCVDT));
                $replacementsPegawai[4] = '';
                $replacementsPegawai[5] = $ProfilPegawai->name;
                $replacementsPegawai[6] = $mPenugasanSemula->CA_CASEID;
                    
            } else if ($mPenugasanSemula->CA_INVSTS == '7') //  MAKLUMAT TIDAK LENGKAP
                {  
            } else if($mPenugasanSemula->CA_INVSTS == '8') //  LUAR BIDANG KUASA
                {   
            } else if($mPenugasanSemula->CA_INVSTS == '6') //  PERTANYAAN
                {   
            }
            
            $date = date('YmdHis');
            $userid = Auth::user()->id;
            
            if(!empty($SuratPengadu)) {
                $SuratPengaduReplace = preg_replace($patternsPengadu, $replacementsPengadu, urldecode($ContentSuratPengadu));
                $arr_repPengadu = array("#", "#");
                $SuratPengaduFinal = str_replace($arr_repPengadu, "", $SuratPengaduReplace); // SuratPengadu in HTML
                $SuratPengaduHtml = PDF::loadHTML($SuratPengaduFinal); // Generate PDF from HTML

                $filename = $userid . '_' . $mPenugasanSemula->CA_CASEID . '_' . $date . '_1.pdf';
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

                $filenameSuratPegawai = $userid . '_' . $mPenugasanSemula->CA_CASEID . '_' . $date . '_2.pdf';
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
            
            PenugasanSemulaDetail::where(['CD_CASEID' => $CA_CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
            $mPenugasanSemulaDetail = new PenugasanSemulaDetail;
            $mPenugasanSemulaDetail->CD_CASEID = $mPenugasanSemula->CA_CASEID;
            if ($request->expectsJson()) {
                $mPenugasanSemulaDetail->CD_TYPE = 'S'; // EZSTAR
            } else {
                $mPenugasanSemulaDetail->CD_TYPE = 'D';
            }
            $mPenugasanSemulaDetail->CD_DESC = $request->input('CD_DESC');
            $mPenugasanSemulaDetail->CD_ACTTYPE = 'CLS';
            $mPenugasanSemulaDetail->CD_INVSTS = $mPenugasanSemula->CA_INVSTS;
            $mPenugasanSemulaDetail->CD_CASESTS = $mPenugasanSemula->CA_CASESTS;
            $mPenugasanSemulaDetail->CD_CURSTS = '1';
            $mPenugasanSemulaDetail->CD_ACTFROM = $mPenugasanSemula->CA_ASGBY;
            $mPenugasanSemulaDetail->CD_ACTTO = $mPenugasanSemula->CA_INVBY;
            $mPenugasanSemulaDetail->CD_DOCATTCHID_PUBLIC = $SuratPengaduId;
            $mPenugasanSemulaDetail->CD_DOCATTCHID_ADMIN = $SuratPegawaiId;
            if($mPenugasanSemulaDetail->save()) {
                return true;
            }return false;
        }return false;
    }
}
