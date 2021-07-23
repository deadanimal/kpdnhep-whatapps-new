<?php

namespace App\Http\Controllers\Integriti;

use App\Attachment;
use App\Http\Controllers\Controller;
use App\Integriti\IntegritiPublic;
use App\Integriti\IntegritiPublicDetail;
use App\Integriti\IntegritiPublicDoc;
use App\Integriti\IntegritiPublicUser;
use App\Integriti\IntegritiPublicUserDetail;
use App\Integriti\IntegritiPublicUserDoc;
use App\Letter;
use App\Mail\IntegritiAduanTerima;
use App\Mail\IntegritiAduanTerimaPublicUser;
use App\Rating;
use App\Repositories\RunnerRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;

class IntegritiPublicUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('integriti.publicuser.create'
            // , compact('caseinfo', 'INVBY')
        );
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
            // 'IN_RCVTYP' => 'required',
            // 'IN_CHANNEL' => 'required',
            // 'IN_SECTOR' => 'required',
            'IN_DOCNO' => 'max:15',
            // 'IN_EMAIL' => 'required_without_all:IN_MOBILENO,IN_TELNO',
            // 'IN_MOBILENO' => 'required_without_all:IN_TELNO,IN_EMAIL',
            // 'IN_TELNO' => 'required_without_all:IN_MOBILENO,IN_EMAIL',
            // 'IN_NAME' => 'required',
            // 'IN_NATCD' => 'required',
            // 'IN_STATECD' => 'required',
            // 'IN_DISTCD' => 'required',
            // 'IN_COUNTRYCD' => 'required_if:IN_NATCD,0',
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
        ]
        // ,
        // [
        //     'IN_AGAINSTNM.required' => 'Ruangan Nama Pegawai Yang Diadu (PYDA) diperlukan.',
        //     'IN_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
        //     'IN_CHANNEL.required' => 'Ruangan Saluran diperlukan.',
        //     'IN_SECTOR.required' => 'Ruangan Sektor diperlukan.',
        //     'IN_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
        //     'IN_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
        //     'IN_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
        //     'IN_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
        //     'IN_NAME.required' => 'Ruangan Nama diperlukan.',
        //     'IN_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
        //     'IN_SUMMARY_TITLE.required' => 'Ruangan Tajuk Aduan diperlukan.',
        //     'IN_SUMMARY_TITLE.max' => 'Ruangan Tajuk Aduan mesti tidak melebihi :max aksara.',
        //     'IN_STATECD.required' => 'Ruangan Negeri diperlukan.',
        //     'IN_DISTCD.required' => 'Ruangan Daerah diperlukan.',
        //     'IN_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
        //     'IN_AGAINSTNM.max' => 'Ruangan Nama Pegawai Yang Diadu (PYDA) mesti tidak melebihi :max aksara.',
        //     'IN_BGK_CASEID.required_if' => 'Ruangan Aduan Kepenggunaan diperlukan.',
        //     'IN_TTPMNO.required_if' => 'Ruangan No. TTPM diperlukan.',
        //     'IN_TTPMNO.max' => 'Ruangan No. TTPM  mesti tidak melebihi :max aksara.',
        //     'IN_TTPMFORM.required_if' => 'Ruangan Jenis Borang TTPM diperlukan.',
        //     'IN_REFOTHER.max' => 'Ruangan Lain-lain mesti tidak melebihi :max aksara.',
        //     'IN_AGAINSTLOCATION.required' => 'Ruangan Lokasi PYDA diperlukan.',
        //     'IN_AGAINST_BRSTATECD.required_if' => 'Ruangan Negeri diperlukan.',
        //     'IN_BRNCD.required_if' => 'Ruangan Bahagian / Cawangan diperlukan.',
        //     'IN_AGENCYCD.required_if' => 'Ruangan Agensi KPDNHEP diperlukan.',
        // ]
        );
        $model = new IntegritiPublicUser;
        $model->fill($request->all());
        $model->IN_INVSTS = '010'; // Status Aduan : DERAF
        $model->IN_CASESTS = 1;
        $model->IN_DEPTCD = 'I'; // Bahagian : Integriti
        $model->IN_RCVTYP = '5'; // Cara Penerimaan / Sumber Aduan : Aduan Web
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
            $mCaseDetail = new IntegritiPublicUserDetail;
            $mCaseDetail->ID_CASEID = $model->id;
            $mCaseDetail->ID_INVSTS = $model->IN_INVSTS;
            $mCaseDetail->ID_CASESTS = $model->IN_CASESTS;
            $mCaseDetail->ID_BRNCD = $model->IN_BRNCD;
            $mCaseDetail->ID_CURSTS = '1';
            if($mCaseDetail->save()) {
                return redirect()->route('integritipublicuser.attachment', $model->id);
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
        $model = IntegritiPublicUser::find($id);
        if ($model) {
//                if ($model->IN_INVSTS == '00') {
            if (in_array($model->IN_INVSTS, ['010','07'])) {
                // if (Auth::user()->id == $model->IN_CREATED_BY) {
                    return view('integriti.publicuser.edit', compact('id','model'));
                // } 
                // else {
                //     return redirect()->route('dashboard');
                // }
            }
            else {
                return redirect()->route('dashboard');
            }
        }
        else {
            return redirect()->route('dashboard');
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
            // 'IN_RCVTYP' => 'required',
            // 'IN_CHANNEL' => 'required',
            // 'IN_SECTOR' => 'required',
            'IN_DOCNO' => 'max:15',
            // 'IN_EMAIL' => 'required_without_all:IN_MOBILENO,IN_TELNO',
            // 'IN_MOBILENO' => 'required_without_all:IN_TELNO,IN_EMAIL',
            // 'IN_TELNO' => 'required_without_all:IN_MOBILENO,IN_EMAIL',
            // 'IN_NAME' => 'required',
            // 'IN_NATCD' => 'required',
            // 'IN_STATECD' => 'required',
            // 'IN_DISTCD' => 'required',
            // 'IN_COUNTRYCD' => 'required_if:IN_NATCD,0',
            // 'IN_BGK_CASEID' => 'required_if:IN_REFTYPE,BGK',
            'IN_TTPMNO' => 'required_if:IN_REFTYPE,TTPM|max:30',
            'IN_TTPMFORM' => 'required_if:IN_REFTYPE,TTPM',
            'IN_REFOTHER' => 'max:30',
            'IN_AGAINSTLOCATION' => 'required',
            'IN_AGAINST_BRSTATECD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_BRNCD' => 'required_if:IN_AGAINSTLOCATION,BRN',
            'IN_AGENCYCD' => 'required_if:IN_AGAINSTLOCATION,AGN',
            'IN_SUMMARY' => 'required',
            'IN_SUMMARY_TITLE' => 'required|max:200',
        ]
        );
        $model = IntegritiPublicUser::find($id);
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
            return redirect()->route('integritipublicuser.attachment',['id' => $model->id]);
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

    public function attachment($id)
    {
        // if (Auth::user()->user_cat == "2") {
            $model = IntegritiPublicUser::findOrFail($id);
            if ($model) {
                $count = IntegritiPublicDoc::
                    where(function ($query) use ($id, $model) {
                        $query->where('IC_CASEID', '=', $id)
                            ->orWhere('IC_CASEID', '=', $model->IN_CASEID);
                    })
                    ->count()
                    ;
                if (in_array($model->IN_INVSTS, ['010','07'])) {
                    // if (Auth::user()->id == $model->IN_CREATED_BY) {
                        return view('integriti.publicuser.attachment', compact('id','model','count'));
                    // }
                    // else {
                        // return redirect()->route('dashboard');
                    // }
                }
                else {
                    return redirect()->route('dashboard');
                }
            }
            else {
                return redirect()->route('dashboard');
            }
        // }
        // else {
            // return redirect()->route('dashboard');
        // }
    }

    public function preview($id)
    {
        // if (Auth::user()->user_cat == "2") {
            $model = IntegritiPublicUser::findOrFail($id);
            if ($model) {
                if (in_array($model->IN_INVSTS, ['010','07'])) {
                    $mIntegritiPublicDoc = IntegritiPublicDoc::
                        where(function ($query) use ($id, $model) {
                            $query->where('IC_CASEID', '=', $id)
                                ->orWhere('IC_CASEID', '=', $model->IN_CASEID);
                        })
                        ->where(function ($query){
                            $query->whereNull('IC_DOCCAT')
                                ->orWhere('IC_DOCCAT', '=', '1');
                        })
                        ->get()
                        ;
                    $count = IntegritiPublicDoc::
                        where(function ($query) use ($id, $model) {
                            $query->where('IC_CASEID', '=', $id)
                                ->orWhere('IC_CASEID', '=', $model->IN_CASEID);
                        })
                        ->where(function ($query){
                            $query->whereNull('IC_DOCCAT')
                                ->orWhere('IC_DOCCAT', '=', '1');
                        })
                        ->count()
                        ;
                    // if (Auth::user()->id == $model->IN_CREATED_BY) {
                        return view('integriti.publicuser.preview', 
                            compact('model','mIntegritiPublicDoc','count')
                        );
                    // }
                    // else {
                    //     return redirect()->route('dashboard');
                    // }
                }
                else {
                    return redirect()->route('dashboard');
                }
            }
            else {
                return redirect()->route('dashboard');
            }
        // }
        // else {
        //     return redirect()->route('dashboard');
        // }
    }

    public function submit(Request $request, $id)
    {
        $model = IntegritiPublicUser::findOrFail($id);
//        if ($model->IN_INVSTS == '00') {
        if (in_array($model->IN_INVSTS, ['010'])) {
            $model->IN_CASEID = RunnerRepository::generateAppNumber('INT', date('m'), date('Y'));
            $model->IN_FILEREF = $model->IN_CASEID;
            $model->IN_INVSTS = '01'; // Aduan Diterima
            $model->IN_CASESTS = '1'; // Belum Diberi Penugasan
            $model->IN_RCVDT = Carbon::now();
            if ($model->save()) {
                // IntegritiPublicDoc::where('ID_CASEID', $id)
                //     ->update(['ID_CASEID' => $model->IN_CASEID]);
                IntegritiPublicUserDetail::where(['ID_CASEID' => $id, 'ID_CURSTS' => '1'])
                    ->update(['ID_CURSTS' => '0']);
                // IntegritiPublicDetail::where('ID_CASEID', $id)
                //     ->update(['ID_CASEID' => $model->IN_CASEID]);
                $mLetterTemplate = Letter::
                    where(['letter_type' => '01','letter_code' => $model->IN_INVSTS])
                    ->first();
                $date = date('YmdHis');
                // $userid = Auth::user()->id;
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
                $replacements[3] = nl2br(htmlspecialchars($model->IN_ADDR))
                    .'<br />'.$model->IN_POSTCD;
                $replacements[4] = $IN_DISTCD;
                $replacements[5] = $IN_STATECD;

                $ContentReplace = preg_replace($patterns, $replacements, urldecode($contentLetterTemplate));
                $arr_rep = array("#", "#");
                $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
                $pdf = PDF::loadHTML($ContentFinal); // Generate PDF from HTML
                $filename = 
                    // $userid . 
                    '_' . $model->id . '_' . $date . '.pdf';
                Storage::disk('letter')->put($filename, $pdf->output()); // Store PDF to storage
                
                $mAttachment = new Attachment();
                $mAttachment->doc_title = $mLetterTemplate->title;
                $mAttachment->file_name = $mLetterTemplate->title;
                $mAttachment->file_name_sys = $filename;
                if($mAttachment->save()){
                    $mIntegritiDetail = new IntegritiPublicUserDetail();
                    // $mIntegritiDetail->ID_CASEID = $model->IN_CASEID;
                    $mIntegritiDetail->ID_CASEID = $model->id;
                    $mIntegritiDetail->ID_INVSTS = $model->IN_INVSTS;
                    $mIntegritiDetail->ID_CURSTS = '1';
                    $mIntegritiDetail->ID_DOCATACHID_PUBLIC = $mAttachment->id;
                    if ($mIntegritiDetail->save()) {
                        $mRating = new Rating();
                        $mRating->intid = $model->IN_CASEID;
                        $mRating->rate = $request->rating;
                        if($mRating->save()) {
                            // if($request->user()->email)
                            if($model->IN_EMAIL)
                            {
                                try {
                                    // Mail::to($Request->user())->queue(new AduanTerimaPublic($mPubCase)); // Send pakai queue
                                    Mail::to($model->IN_EMAIL)
                                        // ->cc('integriti@kpdnhep.gov.my')
                                        ->send(new IntegritiAduanTerimaPublicUser($model));
                                        // Send biasa
                                } catch (Exception $e) {
                                    
                                }
                            }
                            return redirect()->route('integritipublicuser.success', $model->id);
                        }
                    }
                }
            }
        } else if (in_array($model->IN_INVSTS, ['07'])) {
            // $model->IN_CASEID = RunnerRepository::generateAppNumber('INT', date('m'), date('Y'));
            $model->IN_INVSTS = '01'; // Aduan Diterima
            $model->IN_CASESTS = '1'; // Belum Diberi Penugasan
            $model->IN_RCVDT = Carbon::now();
            if ($model->save()) {
                // IntegritiPublicDoc::where('ID_CASEID', $id)
                //     ->update(['ID_CASEID' => $model->IN_CASEID]);
                IntegritiPublicDetail::where(['ID_CASEID' => $id, 'ID_CURSTS' => '1'])
                    ->update(['ID_CURSTS' => '0']);
                // IntegritiPublicDetail::where('ID_CASEID', $id)
                //     ->update(['ID_CASEID' => $model->IN_CASEID]);
                $mIntegritiDetail = new IntegritiPublicDetail();
                // $mIntegritiDetail->ID_CASEID = $model->IN_CASEID;
                $mIntegritiDetail->ID_CASEID = $model->id;
                $mIntegritiDetail->ID_INVSTS = $model->IN_INVSTS;
                $mIntegritiDetail->ID_CURSTS = '1';
                if ($mIntegritiDetail->save()) {
                    // return redirect()->route('public-integriti.success', $model->id);
                    return redirect()
                        ->route('integritipublicuser.success', $model->id)
                        ->with('success', trans('public-case.confirmation.aftersubmit'));
                }
            }
        } else {
            return redirect()->route('integritipublicuser.success', $model->id);
        }
    }
    
    public function success(Request $request, $id)
    {
        // if (Auth::user()->user_cat == "2") {
            $model = IntegritiPublicUser::find($id);
            if ($model) {
                if (in_array($model->IN_INVSTS, ['01'])) {
                    // if (Auth::user()->id == $model->IN_CREATED_BY) {
                        return view('integriti.publicuser.success', compact('id','model'));
                    // }
                    // else {
                    //     return redirect()->route('dashboard');
                    // }
                }
                else {
                    return redirect()->route('dashboard');
                }
            }
            else {
                return redirect()->route('dashboard');
            }
        // }
        // else {
        //     return redirect()->route('dashboard');
        // }
    }
    
    public function printsuccess(Request $request, $id)
    {
        $model = IntegritiPublicUser::find($id);
        if ($model) {
            $pdf = PDF::loadView('integriti.publicuser.printsuccess', compact('model'), [], [
                'default_font_size' => 9
                ,'title' => 'eAduan 2.0'
            ]);
            return $pdf->stream('document.pdf');
        }
        else {
            return redirect()->route('dashboard');
        }
    }

    public function getdistlist($state_cd)
    {
        $mDistList = DB::table('sys_ref')
            ->where('cat', '18')
            ->where('code', 'like', "$state_cd%")
            ->orderBy('sort')
            ->pluck('code', 'descr');

        if (count($mDistList) > 1) {
            $mDistList->prepend('', '-- SILA PILIH --');
        }

        return json_encode($mDistList);
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

    /**
     * Get system brn data list
     * @param null $state_cd
     * @return false|string
     */
    public function getbrnlist($state_cd = null) {
        if($state_cd){
            if($state_cd == '16'){
                $mBrnList = DB::table('sys_brn')
                    // ->where(['BR_STATECD' => $state_cd, 'BR_STATUS' => 1])
                    ->where(['BR_STATECD' => $state_cd])
                    ->whereNotIn('BR_BRNCD', ['PKGK1', 'WHQ1'])
                    ->pluck('BR_BRNNM','BR_BRNCD');
                // $mBrnList->prepend('-- SILA PILIH --', '');
            } else {
                $mBrnList = DB::table('sys_brn')
                    ->where(['BR_STATECD' => $state_cd, 'BR_STATUS' => 1])
                    // ->where(['BR_STATECD' => $state_cd])
                    ->pluck('BR_BRNNM','BR_BRNCD');
                // $mBrnList->prepend('-- SILA PILIH --', '');
            }
        } else {
            $mBrnList = '';
        }
        return json_encode($mBrnList);
    }

    /**
     * Middleware
     */
    public function __construct()
    {
        $this->middleware(['guest', 'locale']);
    }
}
