<?php

namespace App\Http\Controllers\Aduan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use App\Aduan\Kemaskini;
use App\Aduan\KemaskiniDoc;
use App\Aduan\KemaskiniDetail;
use App\User;

class KemaskiniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('aduan.kemaskini.index');
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
        $model = Kemaskini::where(['CA_CASEID' => $id])->first();
        if (empty($model)) {
            return redirect()->route('kemaskini.index')->with('warning', 'Maklumat Aduan <b>TIDAK DIJUMPAI</b>.');
        }
        $mUser = User::find($model->CA_RCVBY);
        if($mUser) {
            $RcvBy = $mUser->name;
        } else {
            $RcvBy = '';
        }
        return view('aduan.kemaskini.edit',compact('model', 'RcvBy'));
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
        if($request->CA_CMPLCAT != 'BPGK 19'){
            $request->merge([
                'CA_ONLINECMPL_PROVIDER' => NULL,
                'CA_ONLINECMPL_URL' => NULL,
                'CA_ONLINECMPL_ACCNO' => NULL,
                'CA_ONLINECMPL_IND' => NULL,
                'CA_ONLINECMPL_CASENO' => NULL,
                'CA_ONLINEADD_IND' => NULL
            ]);
            $this->validate($request, [
//                'CA_RCVTYP' => 'required',
//                'CA_DOCNO' => 'required',
//                'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
//                'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
//                'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
//                'CA_NAME' => 'required',
//                'CA_STATECD' => 'required',
//                'CA_DISTCD' => 'required',
//                'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                'CA_AGAINST_PREMISE' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required',
                'CA_AGAINST_STATECD' => 'required',
                'CA_AGAINST_DISTCD' => 'required',
                'CA_SUMMARY' => 'required',
            ],
            [
//                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
//                'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
//                'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                'CA_NAME.required' => 'Ruangan Nama diperlukan.',
//                'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
//                'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
//                'CA_COUNTRYCD.required_if' => 'Ruangan Negara diperlukan.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
                'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis) diperlukan.',
                'CA_AGAINSTADD.required' => 'Ruangan Alamat diperlukan.',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
            ]);
        } else {
            $this->validate($request, [
                'CA_RCVTYP' => 'required',
                'CA_DOCNO' => 'required',
                'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
                'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
                'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
                'CA_NAME' => 'required',
                'CA_STATECD' => 'required',
                'CA_DISTCD' => 'required',
                'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_ONLINECMPL_AMOUNT' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
                'CA_ONLINECMPL_BANKCD' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_ACCNO' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on|required_if:CA_ONLINECMPL_IND,1',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_SUMMARY' => 'required',
            ],
            [
                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
                'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
                'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_NAME.required' => 'Ruangan Nama diperlukan.',
                'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_COUNTRYCD.required_if' => 'Ruangan Negara diperlukan.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan.',
                'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL diperlukan.',
                'CA_ONLINECMPL_BANKCD.required_if' => 'Ruangan Nama Bank diperlukan.',
                'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan.',
                'CA_ONLINECMPL_ACCNO.required_if' => 'Ruangan No. Akaun Bank diperlukan.',
                'CA_ONLINECMPL_CASENO.required_if' => 'Ruangan No. Aduan Rujukan diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat / Premis) diperlukan.',
                'CA_AGAINSTADD.required_if' => 'Ruangan Alamat diperlukan.',
                'CA_AGAINST_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required_if' => 'Ruangan Daerah diperlukan.',
                'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
            ]);
        }
        
//        $model = Kemaskini::find($id);
        $model = Kemaskini::where(['CA_CASEID' => $id])->first();
        $model->fill($request->all());
        $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        $model->CA_DEPTCD = $DeptCd;
        if($request->CA_ONLINECMPL_AMOUNT == NULL){
            $model->CA_ONLINECMPL_AMOUNT = 0.00;
        } else {
            $model->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);
        }
        if ($model->CA_NATCD == '1') {
            $model->CA_COUNTRYCD = NULL;
        }
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $model->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
            $model->CA_ONLINECMPL_IND = NULL;
            $model->CA_ONLINECMPL_CASENO = NULL;
            $model->CA_ONLINECMPL_URL = NULL;
        }else{
            $model->CA_CMPLKEYWORD = NULL;
        }
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($model->CA_ONLINECMPL_IND) {
                $model->CA_ONLINECMPL_IND = '1';
                $model->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            }else{
                $model->CA_ONLINECMPL_IND = '0';
                $model->CA_ONLINECMPL_CASENO = NULL;
            }
            if($request->CA_ONLINEADD_IND) {
                $model->CA_ONLINEADD_IND = '1';
            }else{
                $model->CA_ONLINEADD_IND = '0';
                $model->CA_AGAINSTADD = NULL;
                $model->CA_AGAINST_STATECD = NULL;
                $model->CA_AGAINST_DISTCD = NULL;
                $model->CA_AGAINST_POSTCD = NULL;
            }
            $model->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            $model->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
            $model->CA_AGAINST_PREMISE = NULL;
        }else{
            $model->CA_ONLINECMPL_URL = NULL;
        }
        if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19'){
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        }else{
            $StateCd = $model->CA_STATECD;
            $DistCd = $model->CA_DISTCD;
            $model->CA_AGAINSTADD = NULL;
            $model->CA_AGAINST_POSTCD = NULL;
            $model->CA_AGAINST_STATECD = NULL;
            $model->CA_AGAINST_DISTCD = NULL;
        }
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
        if ($model->save()) {
//            $request->session()->flash('success', 'Aduan telah berjaya dikemaskini');
//            return redirect()->back();
//            return redirect()->route('kemaskini.index');
            return redirect()->route('kemaskini.docbuktiaduan', $model->CA_CASEID);
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
        $this->middleware('auth');
    }
    
    public function getdatatable(Request $request) {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
//        $mKemaskini = Kemaskini::where([
//                ['CA_CREBY', '=', Auth::user()->id],
//                ['CA_INVSTS', '=', '7']
//            ])
//            ->orWhere([
//                ['CA_INVBY', '=', Auth::user()->id],
//                ['CA_INVSTS', '=', '7']
//            ])
//            ->orderBy('CA_RCVDT', 'DESC');
        $mKemaskini = Kemaskini::where('CA_INVSTS', '7')
            ->where(function ($query) {
                $query->where('CA_CREBY', '=', Auth::user()->id)
                    ->orWhere('CA_INVBY', '=', Auth::user()->id);
            })
            ->orderBy('CA_RCVDT', 'DESC');
        $datatables = DataTables::of($mKemaskini)
            ->addIndexColumn()
            ->editColumn('CA_CASEID', function(Kemaskini $penugasan) {
                return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
            })
            ->editColumn('CA_SUMMARY', function(Kemaskini $kemaskini) {
                if($kemaskini->CA_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', $kemaskini->CA_SUMMARY), 0, 7)).' ...';
                else
                    return '';
//                    return '<div style="max-height:80px; overflow:auto">'.$kemaskini->CA_SUMMARY.'</div>';
            })
            ->editColumn('CA_RCVDT', function(Kemaskini $kemaskini) {
                return $kemaskini->CA_RCVDT ? with(new Carbon($kemaskini->CA_RCVDT))->format('d-m-Y h:i A') : '';
            })
            ->addColumn('tempoh', function(Kemaskini $kemaskini) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
                if($kemaskini->CA_RCVDT){
                    $totalDuration = $kemaskini->getduration($kemaskini->CA_RCVDT, $kemaskini->CA_CASEID);
                    if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                        return '<div style="background-color:#3F6; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                    else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                        return '<div style="background-color:#FF3;" align="center"><strong>'.$totalDuration.'</strong></div>';
                    else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                        return '<div style="background-color:#F0F; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                    else if ($totalDuration > $TempohKetiga->code)
                        return '<div style="background-color:#F00; color: white;" align="center"><strong>'.$totalDuration.'</strong></div>';
                } else {
                    return 0;
                }

            })
            ->addColumn('action', function (Kemaskini $kemaskini) {
                return view('aduan.kemaskini.actionbutton', compact('kemaskini'))->render();
            })
            ->rawColumns(['CA_CASEID', 'CA_SUMMARY', 'tempoh', 'action'])
            ->filter(function ($query) use ($request) {
                if ($request->has('CA_CASEID')) {
                    $query->where('CA_CASEID', 'like', "%{$request->get('CA_CASEID')}%");
                }
                if ($request->has('CA_SUMMARY')) {
                    $query->where('CA_SUMMARY', 'like', "%{$request->get('CA_SUMMARY')}%");
                }
                if ($request->has('CA_NAME')) {
                    $query->where('CA_NAME', 'like', "%{$request->get('CA_NAME')}%");
                }
                if ($request->has('CA_RCVDT_FROM')) {
                    $query->whereDate('CA_RCVDT', '>=', date('Y-m-d 00:00:00', strtotime($request->get('CA_RCVDT_FROM'))));
                }
                if ($request->has('CA_RCVDT_TO')) {
                    $query->whereDate('CA_RCVDT', '<=', date('Y-m-d 23:59:59', strtotime($request->get('CA_RCVDT_TO'))));
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
    
    public function docbuktiaduan($id) {
        $model = Kemaskini::where(['CA_CASEID' => $id])->first();
        $countDoc = DB::table('case_doc')
            ->where('CC_CASEID', $id)
            ->where('CC_IMG_CAT', '1')
            ->count('CC_CASEID');
        return view('aduan.kemaskini.docbuktiaduan', compact('model','countDoc'));
    }
    
    public function preview($id) {
        $model = Kemaskini::where(['CA_CASEID' => $id])->first();
        $modelDoc = KemaskiniDoc::where(['CC_CASEID' => $id])->get();
        $mUser = User::find($model->CA_RCVBY);
        if($mUser) {
            $RcvBy = $mUser->name;
        } else {
            $RcvBy = '';
        }
        return view('aduan.kemaskini.preview', compact('model', 'modelDoc', 'RcvBy'));
    }
    
    public function submit(Request $request, $id)
    {
        $model = Kemaskini::where(['CA_CASEID' => $id])->first();
        $modelCaseDetail = KemaskiniDetail::where(['CD_CASEID' => $id, 'CD_INVSTS' => '2'])->first();
        if(!$modelCaseDetail){
//        if($model->CA_INVBY == '' || $model->CA_INVBY == NULL) {
            $model->CA_INVSTS = '1'; // status aduan: diterima
            $model->CA_CASESTS = '1'; // status perkembangan: belum diberi penugasan
            if($model->save()) {
                KemaskiniDetail::where(['CD_CASEID' => $id, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                KemaskiniDetail::where('CD_CASEID', $id)->update(['CD_CASEID' => $model->CA_CASEID]);
                $modelDetail = new KemaskiniDetail();
                $modelDetail->fill([
                    'CD_CASEID' => $model->CA_CASEID,
                    'CD_TYPE' => 'D',
                    'CD_ACTTYPE' => 'NEW',
                    'CD_INVSTS' => $model->CA_INVSTS,
                    'CD_CASESTS' => $model->CA_CASESTS,
                    'CD_CURSTS' => '1',
                ]);
                if($modelDetail->save()) {
                    $request->session()->flash('success', 'Aduan telah menjadi status: ADUAN BARU.');
                    return redirect()->route('kemaskini.index');
                }
            }
//        }else if($model->CA_INVBY != '' || $model->CA_INVBY != NULL) {
        } else {
            $model->CA_INVSTS = '2'; // status aduan: dalam siasatan
            $model->CA_CASESTS = '2'; // status perkembangan: telah diberi penugasan
            if($model->save()) {
                KemaskiniDetail::where(['CD_CASEID' => $id, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                KemaskiniDetail::where('CD_CASEID', $id)->update(['CD_CASEID' => $model->CA_CASEID]);
                $modelDetail = new KemaskiniDetail();
                $modelDetail->fill([
                    'CD_CASEID' => $model->CA_CASEID,
                    'CD_INVSTS' => $model->CA_INVSTS,
                    'CD_CASESTS' => $model->CA_CASESTS,
                    'CD_CURSTS' => '1',
                ]);
                if($modelDetail->save()) {
                    $request->session()->flash('success', 'Aduan telah menjadi status: DALAM SIASATAN.');
                    return redirect()->route('kemaskini.index');
                }
            }
        }
    }
}
