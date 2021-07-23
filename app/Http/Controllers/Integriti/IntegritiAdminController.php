<?php

namespace App\Http\Controllers\Integriti;

use Illuminate\Http\Request;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiAdmin;
use App\Integriti\IntegritiAdminDetail;
use App\Integriti\IntegritiAdminDoc;
use App\Letter;
use App\Repositories\RunnerRepository;
use App\User;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class IntegritiAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->user_cat == "1") {
            return view('integriti.admin.index');
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->user_cat == "1") {
            return view('integriti.admin.create');
        }
        else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'IN_AGAINSTNM' => 'required|max:250',
            'IN_RCVTYP' => 'required',
            'IN_CHANNEL' => 'required',
            'IN_SECTOR' => 'required',
            'IN_DOCNO' => 'required',
            'IN_EMAIL' => 'required_without_all:IN_MOBILENO,IN_TELNO',
            'IN_MOBILENO' => 'required_without_all:IN_TELNO,IN_EMAIL',
            'IN_TELNO' => 'required_without_all:IN_MOBILENO,IN_EMAIL',
            'IN_NAME' => 'required',
            'IN_NATCD' => 'required',
            'IN_STATECD' => 'required',
            'IN_DISTCD' => 'required',
            'IN_COUNTRYCD' => 'required_if:IN_NATCD,0',
            'IN_BGK_CASEID' => 'required_if:IN_REFTYPE,BGK',
            'IN_TTPMNO' => 'required_if:IN_REFTYPE,TTPM|max:30',
            'IN_TTPMFORM' => 'required_if:IN_REFTYPE,TTPM',
            'IN_REFOTHER' => 'max:30',
            'IN_AGAINSTLOCATION' => 'required',
            'IN_AGAINST_BRSTATECD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_BRNCD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_AGENCYCD' => 'required_if:IN_AGAINSTLOCATION,AGN',
            'IN_SUMMARY' => 'required',
            'IN_SUMMARY_TITLE' => 'required|max:200',
        ],
        [
            'IN_AGAINSTNM.required' => 'Ruangan Nama Pegawai Yang Diadu (PYDA) diperlukan.',
            'IN_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
            'IN_CHANNEL.required' => 'Ruangan Saluran diperlukan.',
            'IN_SECTOR.required' => 'Ruangan Sektor diperlukan.',
            'IN_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
            'IN_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'IN_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'IN_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'IN_NAME.required' => 'Ruangan Nama diperlukan.',
            'IN_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
            'IN_SUMMARY_TITLE.required' => 'Ruangan Tajuk Aduan diperlukan.',
            'IN_SUMMARY_TITLE.max' => 'Ruangan Tajuk Aduan mesti tidak melebihi :max aksara.',
            'IN_STATECD.required' => 'Ruangan Negeri diperlukan.',
            'IN_DISTCD.required' => 'Ruangan Daerah diperlukan.',
            'IN_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
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
        ]);
        $model = new IntegritiAdmin;
        $model->fill($request->all());
        $model->IN_INVSTS = '010'; // DERAF
        $model->IN_DEPTCD = 'I'; // Bahagian : Integriti
        $model->IN_CASESTS = 1;
        $model->IN_RCVDT = Carbon::now();
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
        if ($model->save()) {
            $mCaseDetail = new IntegritiAdminDetail;
            $mCaseDetail->ID_CASEID = $model->id;
            $mCaseDetail->ID_INVSTS = $model->IN_INVSTS;
            $mCaseDetail->ID_CASESTS = $model->IN_CASESTS;
            $mCaseDetail->ID_BRNCD = $model->IN_BRNCD;
            $mCaseDetail->ID_CURSTS = '1';
            if($mCaseDetail->save()) {
                // dd();
                return redirect()->route('integritiadmin.attachment', $model->id);
            }
        }
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
            // $count = IntegritiAdminDoc::
            // DB::table('integriti_case_doc')
                // ->where('CC_CASEID', $id)
                // ->
                // where(function ($query) use ($id, $model) {
                    // $query->where('IC_CASEID', '=', $id)
                        // ->orWhere('IC_CASEID', '=', $model->IN_CASEID);
                // })
                // ->where(function ($query){
                    // $query->whereNull('IC_DOCCAT')
                        // ->orWhere('IC_DOCCAT', '=', '1');
                // })
                // ->count('IC_CASEID');
            // $mAdminCaseDoc = AdminCaseDoc::where(['CC_CASEID' => $id])->first();
            // $mUser = User::find($model->CA_RCVBY);
            // if ($mUser) {
            //     $RcvBy = $mUser->name;
            // } else {
            //     $RcvBy = '';
            // }
    //        return view('aduan.admin-case.edit',compact('mAdminCase', 'mAdminCaseDoc', 'count', 'RcvBy'));
            return view('integriti.admin.edit', compact('model'));
        }
        else{
            return redirect()->route('integritiadmin.index');
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
            'IN_AGAINSTNM' => 'required|max:250',
            'IN_RCVTYP' => 'required',
            'IN_CHANNEL' => 'required',
            'IN_SECTOR' => 'required',
            'IN_DOCNO' => 'required',
            'IN_EMAIL' => 'required_without_all:IN_MOBILENO,IN_TELNO',
            'IN_MOBILENO' => 'required_without_all:IN_TELNO,IN_EMAIL',
            'IN_TELNO' => 'required_without_all:IN_MOBILENO,IN_EMAIL',
            'IN_NAME' => 'required',
            'IN_NATCD' => 'required',
            'IN_STATECD' => 'required',
            'IN_DISTCD' => 'required',
            'IN_COUNTRYCD' => 'required_if:IN_NATCD,0',
            'IN_BGK_CASEID' => 'required_if:IN_REFTYPE,BGK',
            'IN_TTPMNO' => 'required_if:IN_REFTYPE,TTPM|max:30',
            'IN_TTPMFORM' => 'required_if:IN_REFTYPE,TTPM',
            'IN_REFOTHER' => 'max:30',
            'IN_AGAINSTLOCATION' => 'required',
            'IN_AGAINST_BRSTATECD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_BRNCD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_AGENCYCD' => 'required_if:IN_AGAINSTLOCATION,AGN',
            'IN_SUMMARY' => 'required',
            'IN_SUMMARY_TITLE' => 'required|max:200',
        ],
        [
            'IN_AGAINSTNM.required' => 'Ruangan Nama Pegawai Yang Diadu (PYDA) diperlukan.',
            'IN_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
            'IN_CHANNEL.required' => 'Ruangan Saluran diperlukan.',
            'IN_SECTOR.required' => 'Ruangan Sektor diperlukan.',
            'IN_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
            'IN_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'IN_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'IN_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'IN_NAME.required' => 'Ruangan Nama diperlukan.',
            'IN_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
            'IN_SUMMARY_TITLE.required' => 'Ruangan Tajuk Aduan diperlukan.',
            'IN_SUMMARY_TITLE.max' => 'Ruangan Tajuk Aduan mesti tidak melebihi :max aksara.',
            'IN_STATECD.required' => 'Ruangan Negeri diperlukan.',
            'IN_DISTCD.required' => 'Ruangan Daerah diperlukan.',
            'IN_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
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
        ]);
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
        // dd($model);
        if ($model->save()) {
            return redirect()->route('integritiadmin.attachment',['id' => $model->id]);
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

    public function getdatatablecase(Request $request)
    {
        $datatables = DataTables::of([]);
        if ($datatables->request->get('SEARCH') == 1) {

            $TempohPertama = \App\Ref::find(1244);
            $TempohKedua = \App\Ref::find(1245);
            $TempohKetiga = \App\Ref::find(1246);
            $model = IntegritiAdmin::select(DB::raw(
                'integriti_case_info.id, IN_CASEID, IN_SUMMARY, IN_NAME, IN_INVSTS, IN_RCVTYP, IN_RCVDT'
                ))
                ->where(function ($query) {
                    $query->where('IN_CREATED_BY', '=', Auth::user()->id)
                        ->orWhere('IN_INVBY', '=', Auth::user()->id);
                })
                ->orderBy('IN_RCVDT', 'desc');

            $datatables = DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('IN_CASEID', function (IntegritiAdmin $integriti) {
                    return view('integriti.base.summarylink', compact('integriti'))->render();
                })
                ->editColumn('IN_SUMMARY', function (IntegritiAdmin $integritiAdmin) {
                    if ($integritiAdmin->IN_SUMMARY != '')
//                        return implode(' ', array_slice(explode(' ', $adminCase->IN_SUMMARY), 0, 7)).' ...';
                        return substr($integritiAdmin->IN_SUMMARY, 0, 20) . '...';
                    else
                        return '';
                })
                ->editColumn('IN_INVSTS', function (IntegritiAdmin $integritiAdmin) {
                    if($integritiAdmin->invsts){
                        return $integritiAdmin->invsts->descr;
                    } else {
                        return $integritiAdmin->IN_INVSTS;
                    }
                })
//                 ->editColumn('IN_CASESTS', function (AdminCase $adminCase) {
//                     if ($adminCase->IN_CASESTS != '')
//                         return $adminCase->statusPerkembangan->descr;
//                     else
//                         return '';
//                 })
                ->editColumn('IN_RCVDT', function (IntegritiAdmin $integritiAdmin) {
                    return $integritiAdmin->IN_RCVDT ? with(new Carbon($integritiAdmin->IN_RCVDT))->format('d-m-Y h:i A') : '';
                })
//                 ->addColumn('tempoh', function (AdminCase $adminCase) use ($TempohPertama, $TempohKedua, $TempohKetiga) {
//                     if ($adminCase->IN_RCVDT && $adminCase->IN_INVSTS != '10') {
//                         $totalDuration = $adminCase->getduration($adminCase->IN_RCVDT, $adminCase->IN_CASEID);
//                         if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
//                             return '<div style="background-color:#3F6; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
//                         else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
//                             return '<div style="background-color:#FF3;" align="center"><strong>' . $totalDuration . '</strong></div>';
//                         else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
//                             return '<div style="background-color:#F0F; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
//                         else if ($totalDuration > $TempohKetiga->code)
//                             return '<div style="background-color:#F00; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
//                     } else {
//                         return '';
//                     }
//                 })
                // ->addColumn('tempoh', function () {
                //     return '';
                // })
                ->addColumn('action', function (IntegritiAdmin $adminCase) {
                    return view('integriti.admin.actionbutton', compact('adminCase'))->render();
                })
//                 ->rawColumns(['IN_CASEID', 'IN_SUMMARY', 'tempoh', 'action'])
                ->rawColumns(['IN_CASEID', 'tempoh', 'action'])
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
//                     if ($request->has('IN_INVSTS')) {
//                         $query->where('IN_INVSTS', $request->get('IN_INVSTS'));
//                     }
//                     if ($request->has('sa')) {
//                         if ($request->get('sa') == '0') {
//                             $query->where(DB::raw("substr(IN_CASEID, 1, 1)"), '=', '0')
//                                 ->where(DB::raw("substr(IN_CASEID, 2, 1)"), '<>', '0');
//                         } elseif ($request->get('sa') == '00') {
//                             $query->where(DB::raw("substr(IN_CASEID, 1, 2)"), '=', '00')
//                                 ->where(DB::raw("substr(IN_CASEID, 3, 1)"), '<>', '0');
//                         } elseif ($request->get('sa') == '000') {
//                             $query->where(DB::raw("substr(IN_CASEID, 1, 3)"), '=', '000');
//                         } elseif ($request->get('sa') == 'SAS') {
//                             $query->where(DB::raw("substr(IN_CASEID, 1, 3)"), '=', 'SAS');
//                         }
//                     }
                })
                ;
        }
        return $datatables->make(true);
    }

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
        return view('integriti.admin.attachment', compact('model', 'countDoc'));
    }

    public function preview($id)
    {
        $model = IntegritiAdmin::find($id);
        $mAdminDoc = IntegritiAdminDoc::
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
        return view('integriti.admin.preview', compact('model', 'mAdminDoc'));
    }

    public function submit(Request $request, $id)
    {
        $model = IntegritiAdmin::find($id);
        if (in_array($model->IN_INVSTS, ['010'])) {
            $model->IN_CASEID = RunnerRepository::generateAppNumber('INT', date('m'), date('Y'));
            $model->IN_FILEREF = $model->IN_CASEID;
            $model->IN_INVSTS = '01';
            $model->IN_RCVDT = Carbon::now();
            if ($model->save()) {
                // IntegritiAdminDoc::where('ID_CASEID', $id)
                //     ->update(['ID_CASEID' => $model->IN_CASEID]);
                IntegritiAdminDetail::where(['ID_CASEID' => $id, 'ID_CURSTS' => '1'])
                    ->update(['ID_CURSTS' => '0']);
                // IntegritiAdminDetail::where('ID_CASEID', $id)
                //     ->update(['ID_CASEID' => $model->IN_CASEID]);
                $mLetterTemplate = Letter::
                    where(['letter_type' => '01','letter_code' => $model->IN_INVSTS])
                    ->first();
                $date = date('YmdHis');
                $userid = Auth::user()->id;
                if($mLetterTemplate){
                    $contentLetterTemplate = $mLetterTemplate->header . $mLetterTemplate->body . $mLetterTemplate->footer;
                } else {
                    $contentLetterTemplate = '';
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
                $patterns[1] = "#NOADUAN#";
                $patterns[2] = "#NAMAPENGADU#";
                $patterns[3] = "#ALAMATPENGADU#";
                $patterns[4] = "#DAERAHPENGADU#";
                $patterns[5] = "#NEGERIPENGADU#";
                $replacements[1] = $model->IN_CASEID;
                $replacements[2] = $model->IN_NAME;
                $replacements[3] = nl2br(htmlspecialchars($model->IN_ADDR));
                $replacements[4] = $IN_DISTCD;
                $replacements[5] = $IN_STATECD;

                $ContentReplace = preg_replace($patterns, $replacements, urldecode($contentLetterTemplate));
                $arr_rep = array("#", "#");
                $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
                $pdf = PDF::loadHTML($ContentFinal); // Generate PDF from HTML
                $filename = $userid . '_' . $model->id . '_' . $date . '.pdf';
                Storage::disk('letter')->put($filename, $pdf->output()); // Store PDF to storage

                $mAttachment = new Attachment();
                $mAttachment->doc_title = $mLetterTemplate->title;
                $mAttachment->file_name = $mLetterTemplate->title;
                $mAttachment->file_name_sys = $filename;
                if($mAttachment->save()){
                    $mIntegritiDetail = new IntegritiAdminDetail();
                    $mIntegritiDetail->ID_CASEID = $model->id;
                    $mIntegritiDetail->ID_INVSTS = $model->IN_INVSTS;
                    $mIntegritiDetail->ID_CURSTS = '1';
                    $mIntegritiDetail->ID_DOCATACHID_PUBLIC = $mAttachment->id;
                    if ($mIntegritiDetail->save()) {
                        $request->session()->flash(
                            'success', 'Aduan Integriti anda telah diterima. No. Aduan: ' . $model->IN_CASEID
                        );
                    }
                }
            }
        } else {
            $request->session()->flash(
                'warning', 'Harap maaf, Aduan Integriti anda telah <b>diterima</b>. <br />No. Aduan: ' 
                    . $model->IN_CASEID
            );
        }
        return redirect()->route('integritiadmin.index');
    }

    public function GetBrnList($state_cd) {
        $mBrnList = DB::table('sys_brn')
                ->where(['BR_STATECD' => $state_cd, 'BR_STATUS' => 1])
                ->pluck('BR_BRNNM','BR_BRNCD');
        $mBrnList->prepend('-- SILA PILIH --', '');
        return json_encode($mBrnList);
    }

    public function getpublicusercomplaintlist($docno) {
        if($docno){
            $model = 
                DB::table('case_info')
                ->join('sys_users', 'case_info.CA_INVBY', '=', 'sys_users.id')
                ->select(
                    DB::raw(
                        'case_info.CA_CASEID, 
                        CONCAT(
                            "No. Aduan : ", case_info.CA_CASEID, " , 
                            Pihak Diadu : ", case_info.CA_AGAINSTNM, " , 
                            Penyiasat : ", sys_users.name
                        ) as textname'
                    )
                )
                // ->where(function ($query) use ($docno){
                    // $query
                    // ->where('CA_CREBY', Auth::user()->id)
                        // ->orWhere('CA_EMAIL', Auth::user()->email)
                        // ->orWhere('CA_DOCNO', Auth::user()->icnew);
                    // })
                ->where('CA_DOCNO', $docno)
                ->whereNotIn('CA_INVSTS', ['10'])
                ->orderBy('CA_CREDT', 'DESC')
                ->pluck('textname', 'CA_CASEID')
                ;
        } else {
            $model = '';
        }
        $model->prepend('-- SILA PILIH --', '');
        // return $model;
        return json_encode($model);
    }
}
