<?php

namespace App\Http\Controllers\Integriti;

use App\Http\Controllers\Controller;
use App\Aduan\Kemaskini;
use App\Aduan\KemaskiniDoc;
use App\Aduan\KemaskiniDetail;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
use App\Integriti\IntegritiAdminDoc;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class IntegritiKemaskiniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('integriti.kemaskini.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
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
            return view('integriti.kemaskini.edit', compact('model'));
        } else {
            return redirect()->route('integritikemaskini.index');
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
        $this->validate($request, [
            // 'IN_RCVTYP' => 'required',
            // 'IN_DOCNO' => 'required',
            // 'IN_EMAIL' => 'required_without_all:IN_MOBILENO,IN_TELNO',
            // 'IN_MOBILENO' => 'required_without_all:IN_TELNO,IN_EMAIL',
            // 'IN_TELNO' => 'required_without_all:IN_MOBILENO,IN_EMAIL',
            // 'IN_NAME' => 'required',
            // 'IN_NATCD' => 'required',
            // 'IN_STATECD' => 'required',
            // 'IN_DISTCD' => 'required',
            // 'IN_COUNTRYCD' => 'required_if:IN_NATCD,0',
            'IN_AGAINSTNM' => 'required|max:250',
            'IN_BGK_CASEID' => 'required_if:IN_REFTYPE,BGK',
            'IN_TTPMNO' => 'required_if:IN_REFTYPE,TTPM|max:30',
            'IN_TTPMFORM' => 'required_if:IN_REFTYPE,TTPM',
            'IN_REFOTHER' => 'max:30',
            'IN_AGAINSTLOCATION' => 'required',
            'IN_AGAINST_BRSTATECD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_BRNCD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_AGENCYCD' => 'required_if:IN_AGAINSTLOCATION,AGN',
            'IN_SUMMARY_TITLE' => 'required|max:200',
            'IN_SUMMARY' => 'required',
        ],
        [
            // 'IN_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
            // 'IN_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
            // 'IN_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            // 'IN_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            // 'IN_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            // 'IN_NAME.required' => 'Ruangan Nama diperlukan.',
            // 'IN_STATECD.required' => 'Ruangan Negeri diperlukan.',
            // 'IN_DISTCD.required' => 'Ruangan Daerah diperlukan.',
            // 'IN_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
            'IN_AGAINSTNM.required' => 'Ruangan Nama Pegawai Yang Diadu (PYDA) diperlukan.',
            'IN_AGAINSTNM.max' => 'Ruangan Nama Pegawai Yang Diadu (PYDA) mesti tidak melebihi :max aksara.',
            'IN_BGK_CASEID.required_if' => 'Ruangan Aduan Kepenggunaan diperlukan.',
            'IN_TTPMNO.required_if' => 'Ruangan No. TTPM diperlukan.',
            'IN_TTPMNO.max' => 'Ruangan No. TTPM  mesti tidak melebihi :max aksara.',
            'IN_TTPMFORM.required_if' => 'Ruangan Jenis Borang TTPM diperlukan.',
            'IN_REFOTHER.max' => 'Ruangan Lain-lain mesti tidak melebihi :max aksara.',
            'IN_AGAINSTLOCATION.required' => 'Ruangan Lokasi PYDA diperlukan.',
            'IN_AGAINST_BRSTATECD.required_if' => 'Ruangan Negeri diperlukan.',
            'IN_BRNCD.required_if' => 'Ruangan Bahagian / Cawangan diperlukan.',
            'IN_AGENCYCD.required_if' => 'Ruangan Agensi KPDNHEP diperlukan.',
            'IN_SUMMARY_TITLE.required' => 'Ruangan Tajuk Aduan diperlukan.',
            'IN_SUMMARY_TITLE.max' => 'Ruangan Tajuk Aduan mesti tidak melebihi :max aksara.',
            'IN_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
        ]);
        // dd('test kemaskini update akmal');
        
//        $model = Kemaskini::find($id);
        // $model = Kemaskini::where(['CA_CASEID' => $id])->first();
        // $model->fill($request->all());
        // $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        // $model->CA_DEPTCD = $DeptCd;
        // if($request->CA_ONLINECMPL_AMOUNT == NULL){
            // $model->CA_ONLINECMPL_AMOUNT = 0.00;
        // } else {
            // $model->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);
        // }
        // if ($model->CA_NATCD == '1') {
            // $model->CA_COUNTRYCD = NULL;
        // }
        // if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            // $model->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
            // $model->CA_ONLINECMPL_IND = NULL;
            // $model->CA_ONLINECMPL_CASENO = NULL;
            // $model->CA_ONLINECMPL_URL = NULL;
        // }else{
            // $model->CA_CMPLKEYWORD = NULL;
        // }
        // if($request->CA_CMPLCAT == 'BPGK 19') {
            // if($model->CA_ONLINECMPL_IND) {
                // $model->CA_ONLINECMPL_IND = '1';
                // $model->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            // }else{
                // $model->CA_ONLINECMPL_IND = '0';
                // $model->CA_ONLINECMPL_CASENO = NULL;
            // }
            // if($request->CA_ONLINEADD_IND) {
                // $model->CA_ONLINEADD_IND = '1';
            // }else{
                // $model->CA_ONLINEADD_IND = '0';
                // $model->CA_AGAINSTADD = NULL;
                // $model->CA_AGAINST_STATECD = NULL;
                // $model->CA_AGAINST_DISTCD = NULL;
                // $model->CA_AGAINST_POSTCD = NULL;
            // }
            // $model->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            // $model->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
            // $model->CA_AGAINST_PREMISE = NULL;
        // }else{
            // $model->CA_ONLINECMPL_URL = NULL;
        // }
        // if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19'){
            // $StateCd = $request->CA_AGAINST_STATECD;
            // $DistCd = $request->CA_AGAINST_DISTCD;
        // }else{
            // $StateCd = $model->CA_STATECD;
            // $DistCd = $model->CA_DISTCD;
            // $model->CA_AGAINSTADD = NULL;
            // $model->CA_AGAINST_POSTCD = NULL;
            // $model->CA_AGAINST_STATECD = NULL;
            // $model->CA_AGAINST_DISTCD = NULL;
        // }
//        if($request->CA_ROUTETOHQIND && $request->CA_ROUTETOHQIND == 'on') {
//            $model->CA_ROUTETOHQIND = '1';
//            $model->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, true);
//            $model->CA_BRNCD = 'WHQR5';
//        }else{
//            $model->CA_ROUTETOHQIND = '0';
//            $model->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, false);
//        }
//        if($model->CA_INVBY == '' || $model->CA_INVBY == NULL) {
//            $model->CA_INVSTS = '1'; // status aduan: diterima
//            $model->CA_CASESTS = '1'; // status perkembangan: belum diberi penugasan
//        }
//        else if($model->CA_INVBY != '' || $model->CA_INVBY != NULL) {
//            $model->CA_INVSTS = '2'; // status aduan: dalam siasatan
//            $model->CA_CASESTS = '2'; // status perkembangan: telah diberi penugasan
//        }
        $model = IntegritiAdmin::find($id);
        $model->fill($request->all());
        if($request->IN_REFTYPE == 'BGK'){
            $model->IN_BGK_CASEID = $request->IN_BGK_CASEID;
        }
        else if($request->IN_REFTYPE == 'TTPM'){
            $model->IN_TTPMNO = $request->IN_TTPMNO;
            $model->IN_TTPMFORM = $request->IN_TTPMFORM;
        }
        else if($request->IN_REFTYPE == 'OTHER'){
            $model->IN_REFOTHER = $request->IN_REFOTHER;
        }
        
        if($request->IN_AGAINSTLOCATION == 'BRN'){
            $model->IN_AGAINST_BRSTATECD = $request->IN_AGAINST_BRSTATECD;
            $model->IN_BRNCD = $request->IN_BRNCD;
        }
        else if($request->IN_AGAINSTLOCATION == 'AGN'){
            $model->IN_AGENCYCD = $request->IN_AGENCYCD;
        }
        // dd($request, $model, $id);
        if ($model->save()) {
//            $request->session()->flash('success', 'Aduan telah berjaya dikemaskini');
//            return redirect()->back();
//            return redirect()->route('kemaskini.index');
            // dd($request, $model, $id);
            // return redirect()->route('kemaskini.docbuktiaduan', $model->CA_CASEID);
            return redirect()->route('integritikemaskini.attachment',['id' => $model->id]);
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
        abort(404);
    }
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getdatatable(Request $request) {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mKemaskini = IntegritiAdmin::where('IN_INVSTS', '07')
            ->where(function ($query) {
                $query->where('IN_CREATED_BY', '=', Auth::user()->id)
                    ->orWhere('IN_INVBY', '=', Auth::user()->id);
            })
            ->orderBy('IN_RCVDT', 'DESC');
        $datatables = DataTables::of($mKemaskini)
            ->addIndexColumn()
            ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                return view('integriti.base.summarylink', compact('integriti'))->render();
            })
            ->editColumn('IN_SUMMARY', function(IntegritiAdmin $integriti) {
                if($integriti->IN_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $integriti->IN_SUMMARY), 0, 7)).' ...';
                else
                    return '';
                    // return '<div style="max-height:80px; overflow:auto">'.$kemaskini->CA_SUMMARY.'</div>';
            })
            ->editColumn('IN_RCVDT', function(IntegritiAdmin $integriti) {
                return $integriti->IN_RCVDT ? with(new Carbon($integriti->IN_RCVDT))->format('d-m-Y h:i A') : '';
            })
            // ->addColumn('tempoh', function(Kemaskini $kemaskini) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
            //     if($kemaskini->CA_RCVDT){
            //         $totalDuration = $kemaskini->getduration($kemaskini->CA_RCVDT, $kemaskini->CA_CASEID);
            //         if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
            //             return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //         else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
            //             return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //         else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
            //             return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //         else if ($totalDuration > $TempohKetiga->code)
            //             return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
            //     } else {
            //         return 0;
            //     }
            // })
            ->addColumn('action', function (IntegritiAdmin $kemaskini) {
                return view('integriti.kemaskini.actionbutton', compact('kemaskini'))->render();
            })
            // ->rawColumns(['CA_CASEID', 'CA_SUMMARY', 'tempoh', 'action'])
            ->rawColumns(['IN_CASEID', 'action'])
            ->filter(function ($query) use ($request) {
                if ($request->has('IN_CASEID')) {
                    $query->where('IN_CASEID', 'like', "%{$request->get('IN_CASEID')}%");
                }
                if ($request->has('IN_SUMMARY')) {
                    $query->where('IN_SUMMARY', 'like', "%{$request->get('IN_SUMMARY')}%");
                }
                if ($request->has('IN_NAME')) {
                    $query->where('IN_NAME', 'like', "%{$request->get('IN_NAME')}%");
                }
                if ($request->has('IN_RCVDT_FROM')) {
                    $query->whereDate('IN_RCVDT', '>=', date('Y-m-d 00:00:00', strtotime($request->get('IN_RCVDT_FROM'))));
                }
                if ($request->has('IN_RCVDT_TO')) {
                    $query->whereDate('IN_RCVDT', '<=', date('Y-m-d 23:59:59', strtotime($request->get('IN_RCVDT_TO'))));
                }
            })
        ;
        return $datatables->make(true);
    }
    
    public function AduanRoute($StateCd, $DistCd, $DeptCd, $RouteToHQ = false) {
        if ($DeptCd == 'BPGK') {
            if ($StateCd == '16') {
                $FindBrn = DB::table('sys_brn')
                        ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                        ->where('BR_STATECD', $StateCd)
                        ->where(DB::raw("LOCATE(CONCAT(',', '$DistCd' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                        ->where('BR_DEPTCD', 'BGK')
                        ->where('BR_STATUS', 1)
                        ->first();
            } else {
                $FindBrn = DB::table('sys_brn')
                        ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                        ->where('BR_STATECD', $StateCd)
                        ->where(DB::raw("LOCATE(CONCAT(',', '$DistCd' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                        ->where('BR_DEPTCD', $DeptCd)
                        ->where('BR_STATUS', 1)
                        ->first();
            }
            if($RouteToHQ) {
                return 'WHQR5';
            }else{
                return $FindBrn->BR_BRNCD;
            }
        } else {
            $FindBrn = DB::table('sys_brn')
                    ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                    ->where('BR_STATECD', 16)
                    ->where(DB::raw("LOCATE(CONCAT(',', '1601' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                    ->where('BR_DEPTCD', $DeptCd)
                    ->where('BR_STATUS', 1)
                    ->first();
            return $FindBrn->BR_BRNCD;
        }
    }
    
    // public function docbuktiaduan($id) {
    //     $model = Kemaskini::where(['CA_CASEID' => $id])->first();
    //     $countDoc = DB::table('case_doc')
    //         ->where('CC_CASEID', $id)
    //         ->where('CC_IMG_CAT', '1')
    //         ->count('CC_CASEID');
    //     return view('aduan.kemaskini.docbuktiaduan', compact('model','countDoc'));
    // }

    public function attachment($id)
    {
        $model = IntegritiAdmin::find($id);
        $countDoc = 
            // DB::table('integriti_case_doc')
            IntegritiAdminDoc::
            // ->where('ID_CASEID', $id)
            where(function ($query) use ($id, $model) {
                $query->where('IC_CASEID', $id)
                    ->orWhere('IC_CASEID', $model->IN_CASEID);
            })
            ->where(function ($query){
                $query->whereNull('IC_DOCCAT')
                    ->orWhere('IC_DOCCAT', '1');
            })
            ->count('IC_CASEID');
        return view('integriti.kemaskini.attachment', compact('model', 'countDoc'));
    }
    
    // public function preview($id) {
    //     $model = Kemaskini::where(['CA_CASEID' => $id])->first();
    //     $modelDoc = KemaskiniDoc::where(['CC_CASEID' => $id])->get();
    //     $mUser = User::find($model->CA_RCVBY);
    //     if($mUser) {
    //         $RcvBy = $mUser->name;
    //     } else {
    //         $RcvBy = '';
    //     }
    //     return view('aduan.kemaskini.preview', compact('model', 'modelDoc', 'RcvBy'));
    // }

    public function preview($id)
    {
        $model = IntegritiAdmin::find($id);
        $modelDoc = IntegritiAdminDoc::
        // where(['CC_CASEID' => $id])
            where(function ($query) use ($id, $model) {
                $query->where('IC_CASEID', $id)
                    ->orWhere('IC_CASEID', $model->IN_CASEID);
            })
            ->where(function ($query){
                $query->whereNull('IC_DOCCAT')
                    ->orWhere('IC_DOCCAT', '1');
            })
            ->get();
        return view('integriti.kemaskini.preview', compact('model', 'modelDoc'));
    }
    
//     public function submit(Request $request, $id)
//     {
//         $model = Kemaskini::where(['CA_CASEID' => $id])->first();
//         $modelCaseDetail = KemaskiniDetail::where(['CD_CASEID' => $id, 'CD_INVSTS' => '2'])->first();
//         if(!$modelCaseDetail){
// //        if($model->CA_INVBY == '' || $model->CA_INVBY == NULL) {
//             $model->CA_INVSTS = '1'; // status aduan: diterima
//             $model->CA_CASESTS = '1'; // status perkembangan: belum diberi penugasan
//             if($model->save()) {
//                 KemaskiniDetail::where(['CD_CASEID' => $id, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
//                 KemaskiniDetail::where('CD_CASEID', $id)->update(['CD_CASEID' => $model->CA_CASEID]);
//                 $modelDetail = new KemaskiniDetail();
//                 $modelDetail->fill([
//                     'CD_CASEID' => $model->CA_CASEID,
//                     'CD_TYPE' => 'D',
//                     'CD_ACTTYPE' => 'NEW',
//                     'CD_INVSTS' => $model->CA_INVSTS,
//                     'CD_CASESTS' => $model->CA_CASESTS,
//                     'CD_CURSTS' => '1',
//                 ]);
//                 if($modelDetail->save()) {
//                     $request->session()->flash('success', 'Aduan telah menjadi status: ADUAN BARU.');
//                     return redirect()->route('kemaskini.index');
//                 }
//             }
// //        }else if($model->CA_INVBY != '' || $model->CA_INVBY != NULL) {
//         } else {
//             $model->CA_INVSTS = '2'; // status aduan: dalam siasatan
//             $model->CA_CASESTS = '2'; // status perkembangan: telah diberi penugasan
//             if($model->save()) {
//                 KemaskiniDetail::where(['CD_CASEID' => $id, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
//                 KemaskiniDetail::where('CD_CASEID', $id)->update(['CD_CASEID' => $model->CA_CASEID]);
//                 $modelDetail = new KemaskiniDetail();
//                 $modelDetail->fill([
//                     'CD_CASEID' => $model->CA_CASEID,
//                     'CD_INVSTS' => $model->CA_INVSTS,
//                     'CD_CASESTS' => $model->CA_CASESTS,
//                     'CD_CURSTS' => '1',
//                 ]);
//                 if($modelDetail->save()) {
//                     $request->session()->flash('success', 'Aduan telah menjadi status: DALAM SIASATAN.');
//                     return redirect()->route('kemaskini.index');
//                 }
//             }
//         }
//     }
    public function submit(Request $request, $id)
    {
        // dd('hantar aduan maklumat tidak lengkap');
        $model = IntegritiAdmin::find($id);
        if (in_array($model->IN_INVSTS, ['07'])) {
            // $model->IN_CASEID = RunnerRepository::generateAppNumber('INT', date('m'), date('Y'));
            $model->IN_INVSTS = '01';
            $model->IN_RCVDT = Carbon::now();
            if ($model->save()) {
                // IntegritiAdminDoc::where('ID_CASEID', $id)
                //     ->update(['ID_CASEID' => $model->IN_CASEID]);
                IntegritiAdminDetail::where(['ID_CASEID' => $id, 'ID_CURSTS' => '1'])
                    ->update(['ID_CURSTS' => '0']);
                // IntegritiAdminDetail::where('ID_CASEID', $id)
                //     ->update(['ID_CASEID' => $model->IN_CASEID]);
                $mIntegritiDetail = new IntegritiAdminDetail();
                $mIntegritiDetail->ID_CASEID = $model->id;
                $mIntegritiDetail->ID_INVSTS = $model->IN_INVSTS;
                $mIntegritiDetail->ID_CURSTS = '1';
                if ($mIntegritiDetail->save()) {
                    $request->session()->flash(
                        'success', 'Aduan telah menjadi status: <b>ADUAN BARU</b>. <br />No. Aduan: '
                        . $model->IN_CASEID
                    );
                }
            }
        } else {
            $request->session()->flash(
                'warning', 'Harap maaf, Aduan Integriti anda telah <b>diterima</b>. <br />No. Aduan: ' 
                    . $model->IN_CASEID
            );
        }
        return redirect()->route('integritikemaskini.index');
    }
}
