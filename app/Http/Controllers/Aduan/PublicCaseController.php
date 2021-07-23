<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\PublicCase;
use App\Aduan\PublicCaseDetail;
use App\Aduan\PublicCaseDoc;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\Letter;
use App\Log;
use App\Mail\AduanTerimaPublic;
use App\Rating;
use App\Ref;
use App\Repositories\ConsumerComplaint\CaseInfoRepository;
use App\Repositories\Ref\RefRepository;
use App\Repositories\RunnerRepository;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use PDF;
use Validator;
use Yajra\DataTables\DataTables;

class PublicCaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['locale','auth']);
    }
    
    public function index()
    {
        // return view('public-case.index'); //
        abort(404);
    }
    
    public function PrintSuccess($CASEID)
    {
        $model = PublicCase::where(['CA_CASEID' => $CASEID])->first();
//        $mPublicCaseDoc = PublicCaseDoc::where(['CC_CASEID' => $CASEID])->get();
        $pdf = PDF::loadView('aduan.public-case.printsuccess', compact('model'), [], ['default_font_size' => 9,'title' => 'eAduan 2.0']);
        return $pdf->stream('document.pdf');
    }
    
    public function getdocdetail($id)
    {
        $docDetail = DB::table('doc_attach')
            ->where('id', $id)
            ->pluck('id', 'doc_title');
        return json_encode($docDetail);
    }
    
    public function checkcase($id)
    {
        $mUser = User::find(Auth::user()->id);
        $mPublicCase = PublicCase::where(['CA_DOCNO' => $mUser->username]);
//        dd($mPublicCase);
        return view('public-case.checkcase', compact('mPublicCase','mUser','id')); //
    }
    
    public function GetDatatable(DataTables $datatables, Request $request) 
    {
        $mPublicCase = PublicCase::
                where(function ($query){
                    $query->where('CA_CREBY', '=', Auth::user()->id)
                            ->orWhere('CA_EMAIL', Auth::user()->email)
                            ->orWhere('CA_DOCNO', Auth::user()->icnew);
                })
                ->orderBy('CA_CREDT', 'DESC');
                
        if ($request->mobile) {
            return response()->json(['data' => $mPublicCase->limit($request->count)->get()->toArray()]);
        }
                
        $datatables = DataTables::of($mPublicCase)
                ->addIndexColumn()
                ->editColumn('CA_CASEID', function (PublicCase $penugasan) {
                    return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
                })
                ->editColumn('CA_SUMMARY', function(PublicCase $pcase) {
                    if($pcase->CA_SUMMARY != '')
//                        return implode(' ', array_slice(explode(' ', ucfirst($pcase->CA_SUMMARY)), 0, 7)).'...';
                        return substr($pcase->CA_SUMMARY, 0, 20);
                    else
                        return '';
                })
                ->editColumn('CA_CASESTS', function(PublicCase $pcase) {
                    if(Auth::user()->lang == 'ms'){
                        return $pcase->CaseStatus->descr;
                    } else {
                        return $pcase->CaseStatus->descr_en;
                    }
                })
                ->addColumn('CA_RCVDT',function(PublicCase $pcase) {
                    if($pcase->CA_RCVDT != '')
                        return Carbon::parse($pcase->CA_RCVDT)->format('d-m-Y h:i A');
                    else
                        return '';
                })
                ->addColumn('CA_BRNCD',function(PublicCase $pcase) {
                    if($pcase->CA_BRNCD != '')
                        return $pcase->BrnCd->BR_BRNNM;
                    else
                        return '';
                })
//                ->addColumn('tempoh',function(PublicCase $pcase) {
//                    if($pcase->CA_RCVDT != '')
//                        return $pcase->GetDuration($pcase->CA_RCVDT, $pcase->CA_CASEID);
//                    else
//                        return '';
//                })
                ->editColumn('CA_INVBY', function(PublicCase $pcase) {
                    if(!empty($pcase->CA_INVBY)){
                        $Carian = $pcase;
                        if($Carian->InvBy){
                            return view('aduan.carian.show_invby_link', compact('Carian'))->render();
                        }
                        else {
                            return $pcase->CA_INVBY;
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', function (PublicCase $pcase) {
                    return view('aduan.public-case.action_btn', compact('pcase'))->render();
                })
                ->rawColumns(['CA_CASEID','CA_INVBY','action'])
                ->filter(function ($query) use ($request) {
                    if ($request->has('CA_CASEID')) {
                        $query->where('CA_CASEID', 'LIKE', "%{$request->get('CA_CASEID')}%");
                    }
                    if ($request->has('CA_CASESTS')) {
                        $query->where('CA_INVSTS', $request->get('CA_CASESTS'));
                    }
                    if ($request->has('CA_SUMMARY')) {
                        $query->where('CA_SUMMARY', 'LIKE', "%{$request->get('CA_SUMMARY')}%");
                    }
                });
                
        return $datatables->make(true);
    }
    
    public function GetDatatableTransaction($CASEID)
    {
        $mPublicCaseDetail = PublicCaseDetail::where('CD_CASEID', $CASEID)
                ->orderBy('CD_CREDT','ASC')
                ->get();
        
        $datatables = DataTables::of($mPublicCaseDetail)
                ->addIndexColumn()
                ->editColumn('CD_INVSTS', function(PublicCaseDetail $PublicCaseDetail) {
                    if($PublicCaseDetail->CD_INVSTS != ''){
                        if(Auth::user()->lang == 'ms'){
                            return $PublicCaseDetail->StatusAduan->descr;
                        } else {
                            return $PublicCaseDetail->StatusAduan->descr_en;
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('CD_ACTFROM', function(PublicCaseDetail $PublicCaseDetail) {
//                    if($PublicCaseDetail->CD_ACTFROM != '')
//                    return $PublicCaseDetail->UserDaripada->name;
//                    else
//                    return '';
                    if ($PublicCaseDetail->CD_ACTFROM != ''){
                        if ($PublicCaseDetail->UserDaripada){
                            return $PublicCaseDetail->UserDaripada->name;
                        } else {
                            return $PublicCaseDetail->CD_ACTFROM;
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('CD_ACTTO', function(PublicCaseDetail $PublicCaseDetail) {
//                    if($PublicCaseDetail->CD_ACTTO != '')
//                    return $PublicCaseDetail->UserKepada->name;
//                    else
//                    return '';
                    if ($PublicCaseDetail->CD_ACTTO != ''){
                        if($PublicCaseDetail->UserKepada){
                            return $PublicCaseDetail->UserKepada->name;
                        } else {
                            return $PublicCaseDetail->CD_ACTTO;
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('CD_CREDT', function(PublicCaseDetail $PublicCaseDetail) {
                    if ($PublicCaseDetail->CD_CREDT != '')
                        return Carbon::parse($PublicCaseDetail->CD_CREDT)->format('d-m-Y h:i A');
                    else
                        return '';
                })
                ->editColumn('CD_DOCATTCHID_PUBLIC', function(PublicCaseDetail $PublicCaseDetail) {
                    if($PublicCaseDetail->CD_DOCATTCHID_PUBLIC != '')
                        return '<a href='.Storage::disk('letter')->url($PublicCaseDetail->suratPublic->file_name_sys).' target="_blank">'.$PublicCaseDetail->suratPublic->file_name.'</a>';
                        else
                        return '';
                })
                ->rawColumns(['CD_DOCATTCHID_PUBLIC']);
        
        return $datatables->make(true);
    }
    
    public function create() 
    {
        return view('aduan.public-case.newcomplain');
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
//        $CA_ONLINECMPL_AMOUNT_old = $request->CA_ONLINECMPL_AMOUNT;
        if(Auth::user()->profile_ind == "1"){
            $request->merge(['CA_ONLINECMPL_AMOUNT'=>str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT)]);
            if($request->CA_CMPLCAT != 'BPGK 19') {
                $this->validate($request, [
                    'CA_CMPLCAT' => 'required',
                    'CA_CMPLCD' => 'required',
                    'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                    'CA_TTPMTYP' => 'required_if:CA_CMPLCAT,BPGK 08',
                    'CA_TTPMNO' => 'required_if:CA_CMPLCAT,BPGK 08',
                    'CA_AGAINST_PREMISE' => 'required',
                    'CA_SUMMARY' => 'required',
                    'CA_AGAINSTNM' => 'required',
                    'CA_AGAINSTADD' => 'required_unless:CA_CMPLCAT,BPGK 19',
                    'CA_AGAINST_STATECD' => 'required',
                    'CA_AGAINST_DISTCD' => 'required',
                    'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
//                    'CA_AGAINST_POSTCD' => 'min:5|max:5'
                ]);
            }

            $validator = Validator::make($request->all(), [
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999|max:255',
                'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
                'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19|max:80',
//                'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on',
                'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_SUMMARY' => 'required',
                'CA_AGAINSTNM' => 'required|max:255',
                'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_ONLINECMPL_CASENO' => 'max:80',
            ])->validate();

//            if($validator->fails() ){
//                $request->merge(['CA_ONLINECMPL_AMOUNT'=>$CA_ONLINECMPL_AMOUNT_old]);
//            }

            $mUser = User::find(Auth::user()->id);
            $mPubCase = new PublicCase;
            $mPubCase->fill($request->all());

            if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
                $mPubCase->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
            }else{
                $mPubCase->CA_CMPLKEYWORD = NULL;
            }

            if($request->CA_CMPLCAT == 'BPGK 19') {
                if($request->CA_ONLINECMPL_IND) {
                    $mPubCase->CA_ONLINECMPL_IND = '1';
                    $mPubCase->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
                }else{
                    $mPubCase->CA_ONLINECMPL_IND = '0';
                    $mPubCase->CA_ONLINECMPL_CASENO = NULL;
                }

                if($request->CA_ONLINEADD_IND) {
                    $mPubCase->CA_ONLINEADD_IND = '1';
                }else{
                    $mPubCase->CA_ONLINEADD_IND = '0';
                }

                $mPubCase->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
                $mPubCase->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
            }else{
                $mPubCase->CA_ONLINECMPL_URL = '';
            }

//            $mPubCase->CA_CASEID = PublicCase::getNoAduan();
            $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
            $mPubCase->CA_DEPTCD = $DeptCd;
            $mPubCase->CA_SEXCD = $mUser->gender; 
            $mPubCase->CA_NAME = $mUser->name; 
            $mPubCase->CA_DOCNO = $mUser->icnew; 
            $mPubCase->CA_AGE = $mUser->age; 
            $mPubCase->CA_ADDR = $mUser->address; 
            $mPubCase->CA_DISTCD = $mUser->distrinct_cd; 
            $mPubCase->CA_POSCD = $mUser->postcode; 
            $mPubCase->CA_STATECD = $mUser->state_cd; 
            $mPubCase->CA_NATCD = $mUser->citizen; 
            $mPubCase->CA_COUNTRYCD = $mUser->ctry_cd; 
            $mPubCase->CA_TELNO = $mUser->office_no; 
            $mPubCase->CA_EMAIL = $mUser->email; 
            $mPubCase->CA_MOBILENO = $mUser->mobile_no; 
            $mPubCase->CA_EMAIL = $mUser->email; 
            $mPubCase->CA_STATUSPENGADU = $mUser->status_pengadu; 
            $mPubCase->CA_CASESTS = '1'; // BELUM DIBERI PENUGASAN
            $mPubCase->CA_INVSTS = '10'; // DERAF
            if ($request->expectsJson()) {
                $mPubCase->CA_RCVTYP = 'S29'; //APLIKASI TELEFON PINTAR
            } else {
                $mPubCase->CA_RCVTYP = 'S23'; //ADUAN WEB
            }
            $mPubCase->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);

//            $mPubCase->CA_RCVDT = Carbon::now();

            if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19' || $request->CA_ONLINEADD_IND == true)
            {
                $StateCd = $request->CA_AGAINST_STATECD;
                $DistCd = $request->CA_AGAINST_DISTCD;
            }else{
                $StateCd = $mPubCase->CA_STATECD;
                $DistCd = $mPubCase->CA_DISTCD;
                $mPubCase->CA_AGAINSTADD = NULL;
                $mPubCase->CA_AGAINST_POSTCD = NULL;
                $mPubCase->CA_AGAINST_STATECD = NULL;
                $mPubCase->CA_AGAINST_DISTCD = NULL;
            }
            // $mPubCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd);
            $mPubCase->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
//            dd($mPubCase);

            $input['refCategory'] = '244';
            $input['refSubCategory'] = '634';
            $mPubCase->CA_CMPLCD = RefRepository::getSubCategoryCode($input);

            if($mPubCase->save()) {
                $input['caseInfoId'] = $mPubCase->id;
                $input['caseInfoUpdatedById'] = $mPubCase->CA_MODBY;
                $input['caseInfoInvestigationStatusCode'] = $mPubCase->CA_INVSTS;
                $input['caseInfoReceivedTypeCode'] = $mPubCase->CA_RCVTYP;
                $input['validateSubCategoryCode'] = $mPubCase->CA_CMPLCD;

                CaseInfoRepository::setSubCategoryLog($input);

                $mCaseDetail = new PublicCaseDetail();
                $mCaseDetail->CD_CASEID = $mPubCase->id;
                $mCaseDetail->CD_TYPE = 'D';
                $mCaseDetail->CD_ACTTYPE = 'NEW';
                $mCaseDetail->CD_CASESTS = $mPubCase->CA_CASESTS;
                $mCaseDetail->CD_INVSTS = $mPubCase->CA_INVSTS;
                $mCaseDetail->CD_CURSTS = '1';
                if($mCaseDetail->save()) {
                    if ($request->expectsJson()) {
                        return response()->json(['last_insert_id' => $mPubCase->id]);
                    }
                    return redirect()->route('public-case.attachment',['id'=>$mPubCase->id,'invsts'=>$mPubCase->CA_INVSTS]);
                }            
            }
        }
        else{
            $mUser = User::find(Auth::user()->id);
            if($mUser->lang == 'ms'){
                $message = 'Sila kemaskini profil anda sebelum meneruskan membuat aduan.';
            } else {
                $message = 'Please update your profile before continuing to submit a complaint.';
            }
            $request->session()->flash('alert', $message);
            return redirect()->back()->withInput();
        }
    }
    
    public function Attachment($id, $invsts)
    {//dd($id, $invsts);
        $model = PublicCase::find($id);
        if($invsts == '7') {
            $CountDoc = DB::table('case_doc')
                            ->where(['CC_CASEID' => $model->CA_CASEID, 'CC_IMG_CAT' => 1])
                            ->orWhere(['CC_CASEID' => $id])
                            ->count('CC_CASEID');
            $mPublicCaseDoc = PublicCaseDoc::where(['CC_CASEID' => $model->CA_CASEID, 'CC_IMG_CAT' => 1])->orWhere(['CC_CASEID' => $id])->first();
        }else{
            $CountDoc = DB::table('case_doc')
                            ->where(['CC_CASEID' => $id, 'CC_IMG_CAT' => 1])
                            ->orWhere(['CC_CASEID' => $model->CA_CASEID])
                            ->count('CC_CASEID');
            $mPublicCaseDoc = PublicCaseDoc::where(['CC_CASEID' => $id, 'CC_IMG_CAT' => 1])->orWhere(['CC_CASEID' => $model->CA_CASEID])->first();
        }
        
        return view('aduan.public-case.attachment', compact('model','CountDoc','mPublicCaseDoc'));
    }
    
    public function store1(Request $request)
    {
        if($request->CA_CMPLCAT != 'BPGK 19') {
            $this->validate($request, [
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                'CA_AGAINST_PREMISE' => 'required',
                'CA_SUMMARY' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_AGAINST_STATECD' => 'required',
                'CA_AGAINST_DISTCD' => 'required',
                'CA_AGAINST_POSTCD' => 'min:5|max:5'
            ]);
        }
        $this->validate($request, [
            'CA_CMPLCAT' => 'required',
            'CA_CMPLCD' => 'required',
            'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
            'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
            'CA_ONLINECMPL_AMOUNT' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_ACCNO' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on',
            'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
            'CA_SUMMARY' => 'required',
            'CA_AGAINSTNM' => 'required',
            'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on'
        ]);
        
//        dd($request);
        $mUser = User::find(Auth::user()->id);
        $mPubCase = new PublicCase;
        $mPubCase->fill($request->all());
        
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $mPubCase->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
        }else{
            $mPubCase->CA_CMPLKEYWORD = NULL;
        }
        
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($request->CA_ONLINECMPL_IND) {
                $mPubCase->CA_ONLINECMPL_IND = '1';
                $mPubCase->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            }else{
                $mPubCase->CA_ONLINECMPL_IND = '0';
                $mPubCase->CA_ONLINECMPL_CASENO = NULL;
            }
            
            if($request->CA_ONLINEADD_IND) {
                $mPubCase->CA_ONLINEADD_IND = '1';
            }else{
                $mPubCase->CA_ONLINEADD_IND = '0';
            }
            
            $mPubCase->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
        }else{
            $mPubCase->CA_ONLINECMPL_URL = '';
        }
        
//        $mPubCase->CA_CASEID = PublicCase::getNoAduan();
        $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        $mPubCase->CA_DEPTCD = $DeptCd;
        $mPubCase->CA_SEXCD = $mUser->gender; 
        $mPubCase->CA_NAME = $mUser->name; 
        $mPubCase->CA_DOCNO = $mUser->icnew; 
        $mPubCase->CA_AGE = $mUser->age; 
        $mPubCase->CA_ADDR = $mUser->address; 
        $mPubCase->CA_DISTCD = $mUser->distrinct_cd; 
        $mPubCase->CA_POSCD = $mUser->postcode; 
        $mPubCase->CA_STATECD = $mUser->state_cd; 
        $mPubCase->CA_NATCD = $mUser->citizen; 
        $mPubCase->CA_COUNTRYCD = $mUser->ctry_cd; 
        $mPubCase->CA_TELNO = $mUser->office_no; 
        $mPubCase->CA_EMAIL = $mUser->email; 
        $mPubCase->CA_MOBILENO = $mUser->mobile_no; 
        $mPubCase->CA_EMAIL = $mUser->email; 
        $mPubCase->CA_CASESTS = '1'; //BELUM DIBERI PENUGASAN
        $mPubCase->CA_INVSTS = '10'; //DERAF
        $mPubCase->CA_RCVTYP = 'S23'; //ADUAN WEB
        $mPubCase->CA_RCVDT = Carbon::now();
        
        if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19')
        {
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        }else{
            $StateCd = $mPubCase->CA_STATECD;
            $DistCd = $mPubCase->CA_DISTCD;
            $mPubCase->CA_AGAINSTADD = NULL;
            $mPubCase->CA_AGAINST_POSTCD = NULL;
            $mPubCase->CA_AGAINST_STATECD = NULL;
            $mPubCase->CA_AGAINST_DISTCD = NULL;
        }
        // $mPubCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd);
        $mPubCase->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
//        dd($mPubCase);
//        if($mPubCase->save()) {
//            $mCaseDetail = new PublicCaseDetail();
//            $mCaseDetail->CD_CASEID = $mPubCase->CA_CASEID;
//            $mCaseDetail->CD_TYPE = 'D';
//            $mCaseDetail->CD_ACTTYPE = 'NEW';
//            $mCaseDetail->CD_CASESTS = $mPubCase->CA_CASESTS;
//            $mCaseDetail->CD_INVSTS = $mPubCase->CA_INVSTS;
//            $mCaseDetail->CD_CURSTS = '1';
//            if($mCaseDetail->save()) {
//                $request->session()->flash('success', 'Aduan telah berjaya dihantar. Sila muatnaik bukti aduan.');
//                return redirect('/public-case/'.$mPubCase->CA_CASEID.'/edit#attachment');
//            }            
//        }
            if($mPubCase->save()) {
                $date = date('Ymdhis');
                $userid = Auth::user()->id;
                
                $mSuratPublic = Letter::where(['letter_type' => '01','letter_code' => $mPubCase->CA_INVSTS])->first();
//                dd($mSuratPublic);
                $ContentSuratPublic = $mSuratPublic->header . $mSuratPublic->body . $mSuratPublic->footer;
                
                if($mPubCase->CA_STATECD != ''){
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mPubCase->CA_STATECD])->first();
                    $CA_STATECD = $StateNm->descr;
                } else {
                    $CA_STATECD = '';
                }
                if($mPubCase->CA_DISTCD != ''){
                    $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mPubCase->CA_DISTCD])->first();
                    $CA_DISTCD = $DestrictNm->descr;
                } else {
                    $CA_DISTCD = '';
                }
                $patterns[1] = "#NAMAPENGADU#";
                $patterns[2] = "#ALAMATPENGADU#";
                $patterns[3] = "#POSKODPENGADU#";
                $patterns[4] = "#DAERAHPENGADU#";
                $patterns[5] = "#NEGERIPENGADU#";
                $patterns[6] = "#NOADUAN#";
                $replacements[1] = $mPubCase->CA_NAME;
                $replacements[2] = $mPubCase->CA_ADDR != ''? $mPubCase->CA_ADDR : '';
                $replacements[3] = $mPubCase->CA_POSCD != ''? $mPubCase->CA_POSCD : '';
                $replacements[4] = $CA_DISTCD;
                $replacements[5] = $CA_STATECD;
                $replacements[6] = $mPubCase->CA_CASEID;
                
                $ContentReplace = preg_replace($patterns, $replacements, urldecode($ContentSuratPublic));
                $arr_rep = array("#", "#");
                $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
                $pdf = PDF::loadHTML($ContentFinal); // Generate PDF from HTML
                
                $filename = $userid . '_' . $mPubCase->CA_CASEID . '_' . $date . '.pdf';
                Storage::disk('letter')->put($filename, $pdf->output()); // Store PDF to storage
                
                $mAttachment = new Attachment();
                $mAttachment->doc_title = $mSuratPublic->title;
                $mAttachment->file_name = $mSuratPublic->title;
                $mAttachment->file_name_sys = $filename;
                if($mAttachment->save()){
                    PublicCaseDetail::where(['CD_CASEID' => $mPubCase->CA_CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                    $mPublicCaseDetail = new PublicCaseDetail();
                    $mPublicCaseDetail->fill([
                        'CD_CASEID' => $mPubCase->CA_CASEID,
                        'CD_TYPE' => 'D',
                        'CD_ACTTYPE' => 'NEW',
                        'CD_INVSTS' => '1',
                        'CD_CASESTS' => '1',
                        'CD_CURSTS' => '1',
                        'CD_DOCATTCHID_PUBLIC' => $mAttachment->id,
                        ]);
                    if($mPublicCaseDetail->save()) {
                        $request->session()->flash('success', 'Aduan telah berjaya dihantar. Sila muatnaik bukti aduan.');
                        return redirect('/public-case/'.$mPubCase->CA_CASEID.'/edit#attachment');
                    }
                } 
            }
    }
    
    public function AduanRoute($StateCd,$DistCd,$DeptCd)
    {
        if($DeptCd == 'BPGK') {
            if($StateCd == '16') {
                $FindBrn = DB::table('sys_brn')
                            ->select('BR_BRNCD','BR_BRNNM','BR_OTHDIST')
                            ->where('BR_STATECD',$StateCd)
                            ->where(DB::raw("LOCATE(CONCAT(',', '$DistCd' ,','),CONCAT(',',BR_OTHDIST,','))"),">",0)
                            ->where('BR_DEPTCD','BGK')
                            ->where('BR_STATUS', 1)
                            ->first();
            }else{
                $FindBrn = DB::table('sys_brn')
                            ->select('BR_BRNCD','BR_BRNNM','BR_OTHDIST')
                            ->where('BR_STATECD',$StateCd)
                            ->where(DB::raw("LOCATE(CONCAT(',', '$DistCd' ,','),CONCAT(',',BR_OTHDIST,','))"),">",0)
                            ->where('BR_DEPTCD',$DeptCd)
                            ->where('BR_STATUS', 1)
                            ->first();
            }
//            dd($FindBrn);
            return $FindBrn->BR_BRNCD;
        }else{
            $FindBrn = DB::table('sys_brn')
                            ->select('BR_BRNCD','BR_BRNNM','BR_OTHDIST')
                            ->where('BR_STATECD', 16)
                            ->where(DB::raw("LOCATE(CONCAT(',', '1601' ,','),CONCAT(',',BR_OTHDIST,','))"),">",0)
                            ->where('BR_DEPTCD',$DeptCd)
                            ->where('BR_STATUS', 1)
                            ->first();
            return $FindBrn->BR_BRNCD;
        }
    }
    
    public function edit($id)
    {
        $model = PublicCase::where(['id' => $id])->first();
        $CountDoc = DB::table('case_doc')
                            ->where('CC_CASEID', $id)
                            ->count('CC_CASEID');
        $mPublicCaseDoc = PublicCaseDoc::where(['CC_CASEID' => $id])->first();
        return view('aduan.public-case.edit1', compact('model','CountDoc','mPublicCaseDoc'));
    }
    
    public function Update(Request $request, $id)
    {//dd($request);
        $input = $request->all();
        $request->merge(['CA_ONLINECMPL_AMOUNT'=>str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT)]); 
        if($request->CA_CMPLCAT != 'BPGK 19')
        {
            $request->merge([
                'CA_ONLINECMPL_PROVIDER' => NULL,
                'CA_ONLINECMPL_URL' => NULL,
//                'CA_ONLINECMPL_AMOUNT' => NULL,
                'CA_ONLINECMPL_ACCNO' => NULL,
                'CA_ONLINECMPL_IND' => NULL,
                'CA_ONLINECMPL_CASENO' => NULL,
                'CA_ONLINEADD_IND' => NULL,
                'CA_ONLINECMPL_BANKCD' => NULL,
            ]);
            
            if($request->CA_CMPLCAT != 'BPGK 08') {
                $request->merge([
                    'CA_TTPMTYP' => NULL,
                    'CA_TTPMNO' => NULL
                ]);
            }
            
            $this->validate($request, [
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                'CA_TTPMTYP' => 'required_if:CA_CMPLCAT,BPGK 08',
                'CA_TTPMNO' => 'required_if:CA_CMPLCAT,BPGK 08',
                'CA_AGAINST_PREMISE' => 'required',
                'CA_SUMMARY' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_AGAINST_STATECD' => 'required',
                'CA_AGAINST_DISTCD' => 'required',
//                'CA_AGAINST_POSTCD' => 'min:5|max:5'
                'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
            ]);
        }
        else
        {
            $request->merge([
                'CA_TTPMTYP' => NULL,
                'CA_TTPMNO' => NULL
            ]);
            
            $this->validate($request, [
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
//                'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on|required_if:CA_ONLINECMPL_IND,1',
                'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
                'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19|max:80',
                'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_SUMMARY' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
//                'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on'
                'CA_ONLINECMPL_CASENO' => 'max:80',
            ]);
        }
        
        $mPubCase = PublicCase::find($id);
        $mPubCase->fill($request->all());
        $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        $mPubCase->CA_DEPTCD = $DeptCd;
        $mPubCase->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);
        
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $mPubCase->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
            $mPubCase->CA_ONLINECMPL_IND = NULL;
            $mPubCase->CA_ONLINECMPL_CASENO = NULL;
            $mPubCase->CA_ONLINECMPL_URL = NULL;
        }else{
            $mPubCase->CA_CMPLKEYWORD = NULL;
        }
        
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($request->CA_ONLINECMPL_IND) {
                $mPubCase->CA_ONLINECMPL_IND = '1';
                $mPubCase->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            }else{
                $mPubCase->CA_ONLINECMPL_IND = '0';
//                $mPubCase->CA_ONLINECMPL_CASENO = NULL;
            }
            
            if($request->CA_ONLINEADD_IND) {
                $mPubCase->CA_ONLINEADD_IND = '1';
            }else{
                $mPubCase->CA_ONLINEADD_IND = '0';
                $mPubCase->CA_AGAINSTADD = NULL;
                $mPubCase->CA_AGAINST_STATECD = NULL;
                $mPubCase->CA_AGAINST_DISTCD = NULL;
                $mPubCase->CA_AGAINST_POSTCD = NULL;
            }
            
            $mPubCase->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            $mPubCase->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
            $mPubCase->CA_AGAINST_PREMISE = NULL;
        }else{
            $mPubCase->CA_ONLINECMPL_URL = '';
        }
        
        if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19')
        {
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        }else{
            $StateCd = $mPubCase->CA_STATECD;
            $DistCd = $mPubCase->CA_DISTCD;
            $mPubCase->CA_AGAINSTADD = NULL;
            $mPubCase->CA_AGAINST_POSTCD = NULL;
            $mPubCase->CA_AGAINST_STATECD = NULL;
            $mPubCase->CA_AGAINST_DISTCD = NULL;
        }
        if($mPubCase->CA_INVSTS != '7') {
            // $mPubCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd);
            $mPubCase->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
        }
        
        if($request->updatemaklumatxlengkap == '1') {
            $mPubCase->CA_INVSTS = '7'; // Maklumat Tidak Lengkap
        }else{
            $mPubCase->CA_INVSTS = '10'; // Deraf
        }
        
//            dd($mPubCase);
        $input['refCategory'] = '244';
        $input['refSubCategory'] = '634';
        $mPubCase->CA_CMPLCD = RefRepository::getSubCategoryCode($input);

        if($mPubCase->save()) {
            $input['caseInfoId'] = $mPubCase->id;
            $input['caseInfoUpdatedById'] = $mPubCase->CA_MODBY;
            $input['caseInfoInvestigationStatusCode'] = $mPubCase->CA_INVSTS;
            $input['caseInfoReceivedTypeCode'] = $mPubCase->CA_RCVTYP;
            $input['validateSubCategoryCode'] = $mPubCase->CA_CMPLCD;

            CaseInfoRepository::setSubCategoryLog($input);

            if ($request->expectsJson()) {
                return response()->json(['last_insert_id' => $mPubCase->id]);
            }
            return redirect()->route('public-case.attachment',['id' => $mPubCase->id, 'invsts' => $mPubCase->CA_INVSTS]);
        }
    }
    
    public function Preview($id)
    {
        $model = PublicCase::find($id);
        $mPublicCaseDoc = PublicCaseDoc::where(['CC_CASEID' => $id, 'CC_IMG_CAT' => 1])->orWhere(['CC_CASEID' => $model->CA_CASEID])->get();
        return view('aduan.public-case.preview', compact('model','mPublicCaseDoc'));
    }
    
    public function Submit(Request $Request, $id)
    {//dd($Request,$id);
        $mPubCase = PublicCase::find($id);
        if($Request->submitmaklumatxlengkap == '1') {
            if($mPubCase->CA_INVBY == '') {
                $mPubCase->CA_CASESTS = '1'; //Aduan Diterima
                $mPubCase->CA_INVSTS = '1'; //Aduan Diterima
                if($mPubCase->save()) {
                    PublicCaseDoc::where('CC_CASEID', $id)->update(['CC_CASEID' => $mPubCase->CA_CASEID]);
                    PublicCaseDetail::where(['CD_CASEID' => $id, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                    PublicCaseDetail::where('CD_CASEID', $id)->update(['CD_CASEID' => $mPubCase->CA_CASEID]);
                    $mPublicCaseDetail = new PublicCaseDetail();
                    $mPublicCaseDetail->fill([
                            'CD_CASEID' => $mPubCase->CA_CASEID,
                            'CD_TYPE' => 'D',
                            'CD_ACTTYPE' => 'NEW',
                            'CD_INVSTS' => '1',
                            'CD_CASESTS' => '1',
                            'CD_CURSTS' => '1',
                            ]);
                    if($mPublicCaseDetail->save()) {
                        if ($Request->expectsJson()) {
                            return response()->json(['data' => 'ok']);
                        }
                        return redirect()->route('dashboard', array('#complain'))->with('success', trans('public-case.confirmation.aftersubmit'));
                    }
                }
            }else{
                $mPubCase->CA_INVSTS = '2'; //Dalam Siasatan
                if($mPubCase->save()) {
                    PublicCaseDoc::where('CC_CASEID', $id)->update(['CC_CASEID' => $mPubCase->CA_CASEID]);
                    PublicCaseDetail::where(['CD_CASEID' => $id, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                    PublicCaseDetail::where('CD_CASEID', $id)->update(['CD_CASEID' => $mPubCase->CA_CASEID]);
                    $mPublicCaseDetail = new PublicCaseDetail();
                    $mPublicCaseDetail->fill([
                            'CD_CASEID' => $mPubCase->CA_CASEID,
                            'CD_INVSTS' => '2',
                            'CD_CASESTS' => '2',
                            'CD_CURSTS' => '1',
                            ]);
                    if($mPublicCaseDetail->save()) {
                        if ($Request->expectsJson()) {
                            return response()->json(['data' => 'ok']);
                        }
                        return redirect()->route('dashboard', array('#complain'))->with('success', trans('public-case.confirmation.aftersubmit'));
                    }
                }
            }
        } else if ($mPubCase->CA_INVSTS == '10') {
            if ($Request->expectsJson()) {
                $mPubCase->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), '000');
            } else {
                $mPubCase->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), '0');
            }
            $mPubCase->CA_INVSTS = '1'; //Aduan Diterima
            $mPubCase->CA_RCVDT = Carbon::now();
            if($mPubCase->save()) {
                PublicCaseDoc::where('CC_CASEID', $id)->update(['CC_CASEID' => $mPubCase->CA_CASEID]);
                PublicCaseDetail::where(['CD_CASEID' => $id, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                PublicCaseDetail::where('CD_CASEID', $id)->update(['CD_CASEID' => $mPubCase->CA_CASEID]);
                $date = date('Ymdhis');
                $userid = Auth::user()->id;
                $mSuratPublic = Letter::where(['letter_type' => '01','letter_code' => $mPubCase->CA_INVSTS])->first();
                $ContentSuratPublic = $mSuratPublic->header . $mSuratPublic->body . $mSuratPublic->footer;

                if($mPubCase->CA_STATECD != ''){
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mPubCase->CA_STATECD])->first();
                    if (!$StateNm){
                        $CA_STATECD = $mPubCase->CA_STATECD;
                    } else {
                        $CA_STATECD = $StateNm->descr;
                    }
                } else {
                    $CA_STATECD = '';
                }
                if($mPubCase->CA_DISTCD != ''){
                    $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mPubCase->CA_DISTCD])->first();
                    if (!$DestrictNm){
                        $CA_DISTCD = $mPubCase->CA_DISTCD;
                    } else {
                        $CA_DISTCD = $DestrictNm->descr;
                    }
                } else {
                    $CA_DISTCD = '';
                }
                $patterns[1] = "#NAMAPENGADU#";
                $patterns[2] = "#ALAMATPENGADU#";
                $patterns[3] = "#POSKODPENGADU#";
                $patterns[4] = "#DAERAHPENGADU#";
                $patterns[5] = "#NEGERIPENGADU#";
                $patterns[6] = "#NOADUAN#";
                $patterns[7] = "#TARIKH#";
                $patterns[8] = "#MASA#";
                $replacements[1] = $mPubCase->CA_NAME;
                $replacements[2] = $mPubCase->CA_ADDR != ''? $mPubCase->CA_ADDR : '';
                $replacements[3] = $mPubCase->CA_POSCD != ''? $mPubCase->CA_POSCD : '';
                $replacements[4] = $CA_DISTCD;
                $replacements[5] = $CA_STATECD;
                $replacements[6] = $mPubCase->CA_CASEID;
                $replacements[7] = date('d/m/Y', strtotime($mPubCase->CA_RCVDT));
                $replacements[8] = date('h:i A', strtotime($mPubCase->CA_RCVDT));

                $ContentReplace = preg_replace($patterns, $replacements, urldecode($ContentSuratPublic));
                $arr_rep = array("#", "#");
                $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
                $pdf = PDF::loadHTML($ContentFinal); // Generate PDF from HTML

                $filename = $userid . '_' . $mPubCase->CA_CASEID . '_' . $date . '.pdf';
                Storage::disk('letter')->put($filename, $pdf->output()); // Store PDF to storage

                $mAttachment = new Attachment();
                $mAttachment->doc_title = $mSuratPublic->title;
                $mAttachment->file_name = $mSuratPublic->title;
                $mAttachment->file_name_sys = $filename;
                if($mAttachment->save()){
                    $mPublicCaseDetail = new PublicCaseDetail();
                    $mPublicCaseDetail->fill([
                        'CD_CASEID' => $mPubCase->CA_CASEID,
                        'CD_TYPE' => 'D',
                        'CD_ACTTYPE' => 'NEW',
                        'CD_INVSTS' => '1',
                        'CD_CASESTS' => '1',
                        'CD_CURSTS' => '1',
                        'CD_DOCATTCHID_PUBLIC' => $mAttachment->id,
                        ]);
                    if($mPublicCaseDetail->save()) {
                        $mRating = new Rating();
                        $mRating->caseid = $mPubCase->CA_CASEID;
                        $mRating->rate = $Request->rating;
                        if($mRating->save()) {
                            if($Request->user()->email != '')
                            {
    //                            Mail::to($Request->user())->queue(new AduanTerimaPublic($mPubCase)); // Send pakai queue
                                Mail::to($Request->user())->send(new AduanTerimaPublic($mPubCase)); // Send biasa
                            }
                            if ($Request->expectsJson()) {
                                return response()->json(['data' => $mPubCase->CA_CASEID]);
                            }
                            return redirect()->route('public-case.success', $mPubCase->CA_CASEID);
                        }
                    }
                } 
            }
        } else {
            return redirect()->route('public-case.success', $mPubCase->CA_CASEID);
        }
        
    }
    
    public function Success($CASEID)
    {
        return view('aduan.public-case.success', compact('CASEID'));
    }
    
    public function Check($CASEID)
    {
        $model = PublicCase::where(['CA_CASEID' => $CASEID])->first();
        $mPublicCaseDoc = PublicCaseDoc::where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 1])->get();
        return view('aduan.public-case.edit', compact('model','CountDoc','mPublicCaseDoc'));
    }
    
    public function Update1(Request $request, $CASEID)
    {
        if($request->CA_CMPLCAT != 'BPGK 19')
        {
            $request->merge([
                'CA_ONLINECMPL_PROVIDER' => NULL,
                'CA_ONLINECMPL_URL' => NULL,
                'CA_ONLINECMPL_AMOUNT' => NULL,
                'CA_ONLINECMPL_ACCNO' => NULL,
                'CA_ONLINECMPL_IND' => NULL,
                'CA_ONLINECMPL_CASENO' => NULL,
                'CA_ONLINEADD_IND' => NULL
            ]);
            
            $this->validate($request, [
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                'CA_AGAINST_PREMISE' => 'required',
                'CA_SUMMARY' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_AGAINST_STATECD' => 'required',
                'CA_AGAINST_DISTCD' => 'required',
                'CA_AGAINST_POSTCD' => 'min:5|max:5'
            ]);
        }
        else
        {
            $this->validate($request, [
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
                'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on|required_if:CA_ONLINECMPL_IND,1',
                'CA_ONLINECMPL_AMOUNT' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_ACCNO' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_SUMMARY' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on'
            ]);
        }
        
        $mPubCase = PublicCase::where(['CA_CASEID' => $CASEID])->first();
        $mPubCase->fill($request->all());
        $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        $mPubCase->CA_DEPTCD = $DeptCd;
        
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $mPubCase->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
            $mPubCase->CA_ONLINECMPL_IND = NULL;
            $mPubCase->CA_ONLINECMPL_CASENO = NULL;
            $mPubCase->CA_ONLINECMPL_URL = NULL;
        }else{
            $mPubCase->CA_CMPLKEYWORD = NULL;
        }
        
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($request->CA_ONLINECMPL_IND) {
                $mPubCase->CA_ONLINECMPL_IND = '1';
                $mPubCase->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            }else{
                $mPubCase->CA_ONLINECMPL_IND = '0';
                $mPubCase->CA_ONLINECMPL_CASENO = NULL;
            }
            
            if($request->CA_ONLINEADD_IND) {
                $mPubCase->CA_ONLINEADD_IND = '1';
            }else{
                $mPubCase->CA_ONLINEADD_IND = '0';
                $mPubCase->CA_AGAINSTADD = NULL;
                $mPubCase->CA_AGAINST_STATECD = NULL;
                $mPubCase->CA_AGAINST_DISTCD = NULL;
                $mPubCase->CA_AGAINST_POSTCD = NULL;
            }
            
            $mPubCase->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            $mPubCase->CA_AGAINST_PREMISE = NULL;
        }else{
            $mPubCase->CA_ONLINECMPL_URL = '';
        }
        
        if($request->btnHantar)
        {
            if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19')
            {
                $StateCd = $request->CA_AGAINST_STATECD;
                $DistCd = $request->CA_AGAINST_DISTCD;
            }else{
                $StateCd = $mPubCase->CA_STATECD;
                $DistCd = $mPubCase->CA_DISTCD;
                $mPubCase->CA_AGAINSTADD = NULL;
                $mPubCase->CA_AGAINST_POSTCD = NULL;
                $mPubCase->CA_AGAINST_STATECD = NULL;
                $mPubCase->CA_AGAINST_DISTCD = NULL;
            }
            // $mPubCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd);
            $mPubCase->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
            $mPubCase->CA_INVSTS = '1'; // Belum Mula
//            dd($mPubCase);
            if($mPubCase->save()) {
                $date = date('Ymdhis');
                $userid = Auth::user()->id;
                
                $mSuratPublic = Letter::where(['letter_type' => '01','letter_code' => $mPubCase->CA_INVSTS])->first();
//                dd($mSuratPublic);
                $ContentSuratPublic = $mSuratPublic->header . $mSuratPublic->body . $mSuratPublic->footer;
                
                if($mPubCase->CA_STATECD != ''){
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mPubCase->CA_STATECD])->first();
                    $CA_STATECD = $StateNm->descr;
                } else {
                    $CA_STATECD = '';
                }
                if($mPubCase->CA_DISTCD != ''){
                    $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mPubCase->CA_DISTCD])->first();
                    $CA_DISTCD = $DestrictNm->descr;
                } else {
                    $CA_DISTCD = '';
                }
                $patterns[1] = "#NAMAPENGADU#";
                $patterns[2] = "#ALAMATPENGADU#";
                $patterns[3] = "#POSKODPENGADU#";
                $patterns[4] = "#DAERAHPENGADU#";
                $patterns[5] = "#NEGERIPENGADU#";
                $patterns[6] = "#NOADUAN#";
                $replacements[1] = $mPubCase->CA_NAME;
                $replacements[2] = $mPubCase->CA_ADDR != ''? $mPubCase->CA_ADDR : '';
                $replacements[3] = $mPubCase->CA_POSCD != ''? $mPubCase->CA_POSCD : '';
                $replacements[4] = $CA_DISTCD;
                $replacements[5] = $CA_STATECD;
                $replacements[6] = $CASEID;
                
                $ContentReplace = preg_replace($patterns, $replacements, urldecode($ContentSuratPublic));
                $arr_rep = array("#", "#");
                $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
                $pdf = PDF::loadHTML($ContentFinal); // Generate PDF from HTML
                
                $filename = $userid . '_' . $CASEID . '_' . $date . '.pdf';
                Storage::disk('letter')->put($filename, $pdf->output()); // Store PDF to storage
                
                $mAttachment = new Attachment();
                $mAttachment->doc_title = $mSuratPublic->title;
                $mAttachment->file_name = $mSuratPublic->title;
                $mAttachment->file_name_sys = $filename;
                if($mAttachment->save()){
                    PublicCaseDetail::where(['CD_CASEID' => $CASEID, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                    $mPublicCaseDetail = new PublicCaseDetail();
                    $mPublicCaseDetail->fill([
                        'CD_CASEID' => $CASEID,
                        'CD_TYPE' => 'D',
                        'CD_ACTTYPE' => 'NEW',
                        'CD_INVSTS' => '1',
                        'CD_CASESTS' => '1',
                        'CD_CURSTS' => '1',
                        'CD_DOCATTCHID_PUBLIC' => $mAttachment->id,
                        ]);
                    if($mPublicCaseDetail->save()) {
                        $request->session()->flash('success', 'Aduan telah berjaya dihantar.');
                        return redirect()->route('dashboard');
                    }
                } 
            }
        }
        elseif ($request->btnSimpan)
        {
            if($mPubCase->save()) {
                $request->session()->flash('success', 'Aduan telah berjaya disimpan.');
                return redirect()->back();
            }
        }
    }

    public function getCmplCdList($CMPLCD)
    {
        if(Auth::user()->lang == 'ms') {
            $CmplCdList = DB::table('sys_ref')
                    ->where(['cat' => '634', 'status' => '1'])
                    ->where('code', 'LIKE', "$CMPLCD%")
//                    ->orderBy('sort', 'asc')
                    ->orderBy('descr', 'asc')
                    ->pluck('code', 'descr');
            if(count($CmplCdList) != 1){
                $CmplCdList->prepend('0', '-- SILA PILIH --');
            }
            return $CmplCdList;
        }else{
            $CmplCdList = DB::table('sys_ref')
                    ->where(['cat' => '634', 'status' => '1'])
                    ->where('code', 'LIKE', "$CMPLCD%")
                    ->orderBy('sort', 'asc')
                    ->pluck('code', 'descr_en');
            if(count($CmplCdList) != 1){
                $CmplCdList->prepend('0', '-- PLEASE SELECT --');
            }
            return $CmplCdList;
        }
        
    }
    
    public static function GetDstrtList($state_cd, $PlsSlct = true, $Lang = 'ms') 
    {
        if ($Lang == 'ms') {
            $mDstrtList = DB::table('sys_ref')
                    ->where('code', 'LIKE', $state_cd . '%')->where('code', '!=', $state_cd)->where('cat', '18')
                    ->pluck('descr', 'code');
            
            if($PlsSlct == true) {
                $mDstrtList->prepend('-- SILA PILIH --', '');
                return $mDstrtList;
            }else{
                return $mDstrtList;
            }
        } else {
            $mDstrtList = DB::table('sys_ref')
                    ->where('code', 'LIKE', $state_cd . '%')->where('code', '!=', $state_cd)->where('cat', '18')
                    ->orderBy('sort', 'asc')->pluck('descr_en', 'code');

            if($PlsSlct == true) {
                $mDstrtList->prepend('-- PLEASE SELECT --', '');
                return $mDstrtList;
            }else{
                return $mDstrtList;
            }
        }
    }
    
    public function GetDistList($state_cd) {
            if(Auth::user()->lang == 'ms'){
                $mDistList = DB::table('sys_ref')
                                ->where('cat', '18')
                                ->where('code', 'like', "$state_cd%")
                                ->orderBy('sort')
                                ->pluck('code', 'descr_en');
                if(count($mDistList) > 1) {
                                $mDistList->prepend('', '--  SILA PILIH --');
                }
            } else {
                $mDistList = DB::table('sys_ref')
                                ->where('cat', '18')
                                ->where('code', 'like', "$state_cd%")
                                ->orderBy('sort')
                                ->pluck('code', 'descr');
                if(count($mDistList) > 1) {
                                $mDistList->prepend('', '-- PLEASE SELECT --');
                }
            }
        
        return json_encode($mDistList);
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
    public function view($case_id)
    {
        $tab = 1;
        $mPcase = PublicCase::where(['CA_CASEID' => $case_id])->first();

        return view('public-case.view', compact('mPcase', 'tab')); 
    }
    
    public function ListAttachment($case_id)
    {
        $tab = 2;
        $mPcase = PublicCase::where(['CA_CASEID' => $case_id])->first();
        $mPcaseDoc = PublicCaseDoc::where(['CC_CASEID' => $case_id])->first();
        if(empty($mPcaseDoc)){
            $mPcaseDoc = new PublicCaseDoc;
        }
        return view('public-case.listattachment', compact('mPcase','mPcaseDoc', 'tab')); 
    }
    
    public function UpdateAttachment(Request $request, $case_id)
    {
        $mPcase = PublicCase::where(['CA_CASEID' => $case_id])->first();
        
        $file_name = $mPcase->CA_CASEID . '.' . 
        $request->file('file_doc')->getClientOriginalExtension();
        $extension = $request->file('file_doc')->getClientOriginalExtension();
//        Masukkan fail dalam folder uploads
        $file_name_sys = $request->file('file_doc')->move(base_path() . '/public/uploads/', $file_name);
        
        $mDocAtt = new Attachment;
        $mDocAtt->doc_title = $request->doc_title;
        $mDocAtt->file_name = $file_name;
        $mDocAtt->file_name_sys = $file_name_sys;
        $mDocAtt->doctype = $extension;
        $mDocAtt->remarks = $request->remarks;
        if($mDocAtt->save()){
            $mPcaseDoc = new PublicCaseDoc;
            $mPcaseDoc->CC_DOCATTCHID = $mDocAtt->id;
            $mPcaseDoc->CC_CASEID = $mPcase->CA_CASEID;
            $mPcaseDoc->DOC_TYPE = $extension;
            if($mPcaseDoc->save()){
                $mPcaseDtl = new PublicCaseDetail;
                $mPcaseDtl->CD_CASEID = $case_id;
                $mPcaseDtl->CA_INVSTS = 1;
                $mPcaseDtl->CA_CURSTS = 1;
                $mPcaseDtl->CA_CASESTS = $mPcase->CA_CASESTS;
                $mPcaseDtl->CD_DOCATTCHID = $mPcaseDoc->CC_DOCATTCHID;
                if($mPcaseDtl->save()){
                    $mLog = new Log;
                    $mLog->details = $request->path();
                    $mLog->parameter = $case_id;
                    $mLog->ip_address = $request->ip();
                    $mLog->user_agent = $request->header('User-Agent');
                    if($mLog->save()){
                        $request->session()->flash('success', 'Lampiran telah berjaya ditambah');
                        return redirect()->route('dashboard');
                    }
                }
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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
    
    public function delete($id) {
        $mPublicCase = PublicCase::where('id', $id)->whereNull('CA_CASEID')->first();
        $mPublicCaseDetail = PublicCaseDetail::where('CD_CASEID', $id)->first();
        $mPublicCaseDoc = PublicCaseDoc::where('CC_CASEID', $id)->get();

        if (!empty($mPublicCaseDoc)) {
            foreach ($mPublicCaseDoc as $doc) {
                Storage::delete('public/'.$doc->CC_PATH.$doc->CC_IMG);
                $doc->delete();
            }
        }

        if (!empty($mPublicCaseDetail)) {
            $mPublicCaseDetail->delete();
        }

        if (!empty($mPublicCase)) {
            $mPublicCase->delete();
        }

        session()->flash('success', 'Aduan berstatus deraf telah berjaya dihapus');
        return redirect()->back();
    }
}
