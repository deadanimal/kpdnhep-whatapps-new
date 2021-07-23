<?php

namespace App\Http\Controllers\Integriti;

use App\Http\Controllers\Controller;
use App\Attachment;
use App\Integriti\IntegritiPublic;
use App\Integriti\IntegritiPublicDetail;
use App\Integriti\IntegritiPublicDoc;
use App\Mail\IntegritiAduanTerima;
use App\Letter;
use App\Rating;
use App\Repositories\RunnerRepository;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class PublicIntegritiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->user_cat == "2") {
            return view('integriti.public-integriti.create');
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
            // 'IN_REFTYPE' => 'required',
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
        ]);
        $mUser = User::find(Auth::user()->id);
        $model = new IntegritiPublic;
        $model->fill($request->all());
        $model->IN_SEXCD = $mUser->gender;
        $model->IN_NAME = $mUser->name;
        $model->IN_DOCNO = $mUser->icnew;
        $model->IN_AGE = $mUser->age;
        $model->IN_ADDR = $mUser->address;
        $model->IN_DISTCD = $mUser->distrinct_cd;
        $model->IN_POSTCD = $mUser->postcode;
        $model->IN_STATECD = $mUser->state_cd;
        $model->IN_NATCD = $mUser->citizen;
        $model->IN_COUNTRYCD = $mUser->ctry_cd;
        $model->IN_TELNO = $mUser->office_no;
        $model->IN_EMAIL = $mUser->email;
        $model->IN_MOBILENO = $mUser->mobile_no;
        $model->IN_STATUSPENGADU = $mUser->status_pengadu;
        $model->IN_INVSTS = '010'; // Status Aduan : DERAF
        $model->IN_RCVTYP = '5'; // Cara Penerimaan / Sumber Aduan : Aduan Web
        $model->IN_DEPTCD = 'I'; // Bahagian : Integriti
        // indicator untuk checkbox IN_SECRETFLAG
        // if ($request->IN_SECRETFLAG && $request->IN_SECRETFLAG == 'on') {
        //     $model->IN_SECRETFLAG = '1';
        // } else {
        //     $model->IN_SECRETFLAG = NULL;
        // }

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
            $mCaseDetail = new IntegritiPublicDetail();
            $mCaseDetail->ID_CASEID = $model->id;
            $mCaseDetail->ID_INVSTS = $model->IN_INVSTS;
            $mCaseDetail->ID_CURSTS = '1';
            if ($mCaseDetail->save()) {
                return redirect()->route('public-integriti.attachment',['id'=>$model->id]);
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
        if (Auth::user()->user_cat == "2") {
            $model = IntegritiPublic::find($id);
            if ($model) {
//                if ($model->IN_INVSTS == '00') {
                if (in_array($model->IN_INVSTS, ['010','07'])) {
                    if (Auth::user()->id == $model->IN_CREATED_BY) {
                        return view('integriti.public-integriti.edit', compact('id','model'));
                    } 
                    else {
                        return redirect()->route('dashboard');
                    }
                }
                else {
                    return redirect()->route('dashboard');
                }
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
            // 'IN_REFTYPE' => 'required',
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
        ]);
        $model = IntegritiPublic::find($id);
        $model->fill($request->all());
        // if ($request->IN_SECRETFLAG && $request->IN_SECRETFLAG == 'on') {
        //     $model->IN_SECRETFLAG = '1';
        // } else {
        //     $model->IN_SECRETFLAG = NULL;
        // }
        
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
            return redirect()->route('public-integriti.attachment',['id' => $model->id]);
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
    
    /**
     * Middleware
     */
    public function __construct()
    {
        $this->middleware(['locale','auth']);
    }
    
    public function attachment($id)
    {
        if (Auth::user()->user_cat == "2") {
            $model = IntegritiPublic::find($id);
            if ($model) {
                $count = IntegritiPublicDoc::
                    where(function ($query) use ($id, $model) {
                        $query->where('IC_CASEID', '=', $id)
                            ->orWhere('IC_CASEID', '=', $model->IN_CASEID);
                    })
                    ->count()
                    ;
                if (in_array($model->IN_INVSTS, ['010','07'])) {
                    if (Auth::user()->id == $model->IN_CREATED_BY) {
                        return view('integriti.public-integriti.attachment', compact('id','model','count'));
                    }
                    else {
                        return redirect()->route('dashboard');
                    }
                }
                else {
                    return redirect()->route('dashboard');
                }
            }
            else {
                return redirect()->route('dashboard');
            }
        }
        else {
            return redirect()->route('dashboard');
        }
    }
    
    public function preview($id)
    {
        if (Auth::user()->user_cat == "2") {
            $model = IntegritiPublic::find($id);
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
                    if (Auth::user()->id == $model->IN_CREATED_BY) {
                        return view('integriti.public-integriti.preview', 
                            compact('model','mIntegritiPublicDoc','count')
                        );
                    }
                    else {
                        return redirect()->route('dashboard');
                    }
                }
                else {
                    return redirect()->route('dashboard');
                }
            }
            else {
                return redirect()->route('dashboard');
            }
        }
        else {
            return redirect()->route('dashboard');
        }
    }
    
    public function submit(Request $request, $id)
    {
        $model = IntegritiPublic::find($id);
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
                IntegritiPublicDetail::where(['ID_CASEID' => $id, 'ID_CURSTS' => '1'])
                    ->update(['ID_CURSTS' => '0']);
                // IntegritiPublicDetail::where('ID_CASEID', $id)
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
                if($model->daerahpengadu){
                    $IN_DISTCD = $model->daerahpengadu->descr;
                } else {
                    $IN_DISTCD = '';
                }
                if($model->negeripengadu){
                    $IN_STATECD = $model->negeripengadu->descr;
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
                    $mIntegritiDetail = new IntegritiPublicDetail();
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
                            if($request->user()->email)
                            {
                                try {
                                    // Mail::to($Request->user())->queue(new AduanTerimaPublic($mPubCase)); // Send pakai queue
                                    Mail::to($request->user()->email)
                                        // ->cc('integriti@kpdnhep.gov.my')
                                        ->send(new IntegritiAduanTerima($model));
                                        // Send biasa
                                } catch (Exception $e) {
                                    
                                }
                            }
                            return redirect()->route('public-integriti.success', $model->id);
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
                        ->route('dashboard', array('#integriti'))
                        ->with('success', trans('public-case.confirmation.aftersubmit'));
                }
            }
        } else {
            return redirect()->route('public-integriti.success', $model->id);
        }
    }
    
    public function success(Request $request, $id)
    {
        if (Auth::user()->user_cat == "2") {
            $model = IntegritiPublic::find($id);
            if ($model) {
                if (in_array($model->IN_INVSTS, ['01'])) {
                    if (Auth::user()->id == $model->IN_CREATED_BY) {
                        return view('integriti.public-integriti.success', compact('id','model'));
                    }
                    else {
                        return redirect()->route('dashboard');
                    }
                }
                else {
                    return redirect()->route('dashboard');
                }
            }
            else {
                return redirect()->route('dashboard');
            }
        }
        else {
            return redirect()->route('dashboard');
        }
    }
    
    public function printsuccess(Request $request, $id)
    {
        $model = IntegritiPublic::find($id);
        if ($model) {
            $pdf = PDF::loadView('integriti.public-integriti.printsuccess', compact('model'), [], [
                'default_font_size' => 9
                ,'title' => 'eAduan 2.0'
            ]);
            return $pdf->stream('document.pdf');
        }
        else {
            return redirect()->route('dashboard');
        }
    }

    public function getdatatable(DataTables $datatables, Request $request)
    {
        $model = IntegritiPublic::
            where(function ($query){
                $query->where('IN_CREATED_BY', Auth::user()->id)
                    ->orWhere('IN_EMAIL', Auth::user()->email)
                    ->orWhere('IN_DOCNO', Auth::user()->icnew);
            })
            ->orderBy('IN_CREATED_AT', 'DESC');
        
        // if ($request->mobile) {
        //     return response()->json(['data' => $mPublicCase->limit($request->count)->get()->toArray()]);
        // }
        
        $datatables = DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('IN_CASEID', function (IntegritiPublic $integriti) {
                return view('integriti.base.summarylink', compact('integriti'))->render();
            })
            ->editColumn('IN_SUMMARY', function(IntegritiPublic $integritiPublic) {
                if($integritiPublic->IN_SUMMARY != '')
                    return implode(' ', array_slice(explode(' ', ucfirst($integritiPublic->IN_SUMMARY)), 0, 7)) . '...';
                    // return substr($pcase->IN_SUMMARY, 0, 20);
                else
                    return '';
            })
            ->addColumn('IN_RCVDT',function(IntegritiPublic $integritiPublic) {
                if(!empty($integritiPublic->IN_RCVDT))
                    return Carbon::parse($integritiPublic->IN_RCVDT)->format('d-m-Y h:i A');
                else
                    return '';
            })
            ->addColumn('IN_BRNCD',function(IntegritiPublic $integritiPublic) {
                if(!empty($integritiPublic->IN_BRNCD)){
                    if($integritiPublic->BrnCd){
                        return $integritiPublic->BrnCd->BR_BRNNM;
                    }
                    else{
                        return $integritiPublic->IN_BRNCD;
                    }
                }
                else {
                    return '';
                }
            })
            ->editColumn('IN_INVSTS', function (IntegritiPublic $integritiPublic) {
                if($integritiPublic->StatusAduan){
                    return $integritiPublic->StatusAduan->descr;
                } else {
                    return $integritiPublic->IN_INVSTS;
                }
            })
//                ->addColumn('tempoh',function(PublicCase $pcase) {
//                    if($pcase->CA_RCVDT != '')
//                        return $pcase->GetDuration($pcase->CA_RCVDT, $pcase->CA_CASEID);
//                    else
//                        return '';
//                })
            // ->editColumn('CA_INVBY', function(PublicCase $pcase) {
            //     if(!empty($pcase->CA_INVBY)){
            //         $Carian = $pcase;
            //         if($Carian->InvBy){
            //             return view('aduan.carian.show_invby_link', compact('Carian'))->render();
            //         }
            //         else {
            //             return $pcase->CA_INVBY;
            //         }
            //     } else {
            //         return '';
            //     }
            // })
            ->addColumn('action', function (IntegritiPublic $integritiPublic) {
                return view('integriti.public-integriti.actionbutton', compact('integritiPublic'))->render();
            })
            // ->rawColumns(['CA_CASEID','CA_INVBY','action'])
            ->rawColumns(['IN_CASEID','action'])
            ->filter(function ($query) use ($request) {
                if ($request->has('IN_CASEID')) {
                    $query->where('IN_CASEID', 'LIKE', "%{$request->get('IN_CASEID')}%");
                }
                if ($request->has('IN_INVSTS')) {
                    $query->where('IN_INVSTS', $request->get('IN_INVSTS'));
                }
                if ($request->has('IN_SUMMARY')) {
                    $query->where('IN_SUMMARY', 'LIKE', "%{$request->get('IN_SUMMARY')}%");
                }
            })
            ;
        return $datatables->make(true);
    }
}
