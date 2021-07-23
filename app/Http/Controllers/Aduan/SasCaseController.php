<?php

namespace App\Http\Controllers\Aduan;

//use App\Attachment;
use App\Aduan\PenyiasatanKes;
use App\Http\Controllers\Controller;
use App\Mail\AduanTutupSas;
use App\Repositories\ConsumerComplaint\CaseInfoRepository;
use App\Repositories\RunnerRepository;
use App\SasCase;
use App\SasCaseDetail;
use App\SasCaseDoc;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Image;
use PDF;
use SoapClient;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class SasCaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $mSasCase = SasCase::get();
        return view('sas-case.index');
    }
    
    public function GetDataJpn($DOCNO)
    {
        $AgencyCode = "110012";
        $BranchCode = "eAduan";
        $TransactionCode = "T2"; // T2 - Admin Page, T7 - Public Page
        $RequestDateTime = date('Y-m-d H:i:s'); //date("Y-m-d") . "T" . date("H:i:s");
        $RequestIndicator = "A";
        $UserId = $DOCNO;
        $ICNumber = $DOCNO;
        
        try {
            $client = new SoapClient("http://10.23.150.194/tojpn/tomyidentiti/crswsdev.wsdl");
        
            $response = $client->retrieveCitizensData(array(
                "AgencyCode" => $AgencyCode,
                "BranchCode" => $BranchCode,
                "UserId" => $UserId,
                "TransactionCode" => $TransactionCode,
                "RequestDateTime" => $RequestDateTime,
                "ICNumber" => $ICNumber,
                "RequestIndicator" => $RequestIndicator,
            ));
//            return json_encode($response);
            if($response->ReplyIndicator == '1' || $response->ReplyIndicator == '2')
            {
                $arrData = $response;
                // Umur
                $Age = Carbon::createFromDate(date('Y', strtotime($response->DateOfBirth)), 5, 21)->age;
                $arrData->Age = $Age;
                switch ($Age) {
                    case ($Age <= 18):
                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'100'])->first();
                        $arrData->Age = '100';
                break;
                    case ($Age >= 19 && $Age <= 25):
                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'101'])->first();
                        $arrData->Age = '101';
                break;
                    case ($Age >= 26 && $Age <= 40):
                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'102'])->first();
                        $arrData->Age = '102';
                break;
                    case ($Age >= 41 && $Age <= 55):
                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'103'])->first();
                        $arrData->Age = '103';
                break;
                    case ($Age >= 56):
                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'104'])->first();
                        $arrData->Age = '104';
                break;
                    default:
                        $arrData->Age = '0';
                }
                $arrData->Age = $Age;
//                $MobileNo = 
                $arrData->Gdr = ($response->Gender == '0')? '0' : ($response->Gender == 'L'? 'M' : 'F'); // Jantina
                // Warganegara
                if ($response->ResidentialStatus=='B' ||
                    $response->ResidentialStatus=='C' ||
                    $response->ResidentialStatus=='M' ||
                    $response->ResidentialStatus=='P') {
                    $arrData->Warganegara = 'malaysia';
                } else {
                    $arrData->Warganegara = 'oth';
                }

                return json_encode($arrData);
            }
            else
            {
                $arrData = $response;
                $arrData->Name = '';
                $arrData->EmailAddress = '';
                $arrData->MobilePhoneNumber = '';
                $arrData->Warganegara = '';
                $arrData->CorrespondenceAddress1 = '';
                $arrData->CorrespondenceAddress2 = '';
                $arrData->CorrespondenceAddressPostcode = '';
                $arrData->CorrespondenceAddressStateCode = '';
                $arrData->CorrespondenceAddressCityCode = '';
                $arrData->Age = '';
                $arrData->Gdr = '';
                $arrData->Warganegara = '';
                
                $arrData->error = "No. Kp tidak Sah";
                return json_encode($arrData);
            }
        } catch (SoapFault $fault) {
                $arrData = $fault;
                $arrData->Name = '';
                $arrData->EmailAddress = '';
                $arrData->MobilePhoneNumber = '';
                $arrData->Warganegara = '';
                $arrData->CorrespondenceAddress1 = '';
                $arrData->CorrespondenceAddress2 = '';
                $arrData->CorrespondenceAddressPostcode = '';
                $arrData->CorrespondenceAddressStateCode = '';
                $arrData->CorrespondenceAddressCityCode = '';
                $arrData->Age = '';
                $arrData->Gdr = '';
                $arrData->Warganegara = '';
                
                $arrData->error = "Masalah teknikal";
                return json_encode($arrData);
        }
        
    }
    
    public function getdata(Request $request)
    {
        $mSasCase = SasCase::
        // where('CA_CASEID', 'LIKE', 'SAS%')
            where(function ($query) {
                $query->where('CA_CASEID', 'like', 'SAS%')
                    ->orWhere('CA_CASEID', 'like', 'AK%');
            })
            ->orderBy('CA_RCVDT', 'DESC')
            // ->orderBy('CA_CASEID', 'DESC')
        ;
        
        $datatables = DataTables::of($mSasCase)
                    ->addIndexColumn()
                    ->editColumn('CA_SUMMARY', function(SasCase $SasCase) {
                        if($SasCase->CA_SUMMARY != '')
                        return implode(' ', array_slice(explode(' ', $SasCase->CA_SUMMARY), 0, 7)).'...';
                        else
                        return '';
//                        return '<div style="max-height:80px; overflow:auto">'.$SasCase->CA_SUMMARY.'</div>';
                    })
                    ->editColumn('CA_RCVDT', function(SasCase $SasCase) {
                        if($SasCase->CA_RCVDT != '')
                        return Carbon::parse($SasCase->CA_RCVDT)->format('d-m-Y h:i A');
                        else
                        return '';
                    })
                    ->editColumn('CA_INVSTS', function(SasCase $SasCase) {
                        if($SasCase->CA_INVSTS != '')
                        return $SasCase->StatusAduan->descr;
                        else
                        return '';
                    })
                    ->editColumn('CA_CASESTS', function(SasCase $SasCase) {
                        if($SasCase->CA_CASESTS != '')
                        return $SasCase->StatusPerkembangan->descr;
                        else
                        return '';
                    })
                    ->editColumn('CA_RCVBY', function(SasCase $SasCase) {
                        if($SasCase->CA_RCVBY != '')
                            return $SasCase->RcvBy->name;
                        else
                            return '';
                    })
                    ->editColumn('CA_INVBY', function(SasCase $SasCase) {
                        if($SasCase->CA_INVBY != '') {
                            $Carian = $SasCase;
                            return view('aduan.carian.show_invby_link', compact('Carian'))->render();
                        }
                        else {
                            return '';
                        }
                    })
                    ->editColumn('CA_AGAINST_STATECD', function(SasCase $SasCase) {
                        if($SasCase->CA_AGAINST_STATECD != '')
                            return $SasCase->againststate->descr;
                        else
                            return '';
                    })
                    ->editColumn('CA_CASEID', function (SasCase $penugasan) {
                       return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
                    })
                    ->addColumn('action', 
                        '<a href="{{ url(\'sas-case\edit\', $CA_CASEID) }}" class="btn btn-xs btn-success"><i class="fa fa-search" data-toggle="tooltip" data-placement="right" title="Kemaskini"></i></a>'
                    )
                    ->rawColumns(['CA_INVBY','CA_SUMMARY','CA_CASEID', 'action'])
                    ->filter(function ($query) use ($request) {
                        if ($request->has('caseid')) {
                            $query->where('CA_CASEID', $request->get('caseid'));
                        }
                        if ($request->has('summary')) {
                            $query->where('CA_SUMMARY', 'like', "%{$request->get('summary')}%");
                        }
                        if ($request->has('name')) {
                            $query->where('CA_NAME', 'like', "%{$request->get('name')}%");
                        }
//                        if ($request->has('rcvdate')) {
//                            $query->whereDate('CA_RCVDT', Carbon::parse($request->get('rcvdate'))->format('Y-m-d'));
//                        }
                        if ($request->has('CA_RCVDT_FROM')) {
                            $query->whereDate('CA_RCVDT', '>=', date('Y-m-d 00:00:01', strtotime($request->get('CA_RCVDT_FROM'))));
                        }
                        if ($request->has('CA_RCVDT_TO')) {
                            $query->whereDate('CA_RCVDT', '<=', date('Y-m-d 23:59:59', strtotime($request->get('CA_RCVDT_TO'))));
                        }
                    });
                    
//        if ($caseid = $datatables->request->get('caseid')) {
//            $datatables->where('CA_CASEID', $caseid);
//        }
//        if ($summary = $datatables->request->get('summary')) {
//            $datatables->where('CA_SUMMARY', 'LIKE', "%$summary%");
//        }
//        if ($name = $datatables->request->get('name')) {
//            $datatables->where('CA_NAME', 'LIKE', "%$name%");
//        }
//        if ($rcvdate = $datatables->request->get('rcvdate')) {
//            $datatables->whereDate('CA_RCVDT', Carbon::parse($rcvdate)->format('Y-m-d'));
//        }
        
        return $datatables->make(true);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mUser = User::find(Auth::User()->id);
        return view('sas-case.create', compact('mUser'));
    }

    public function store(Request $request)
    {
        $request->merge(['CA_ONLINECMPL_AMOUNT'=>str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT)]); 
        if($request->CA_CMPLCAT != 'BPGK 19') {
            $request->merge([
                'CA_ONLINECMPL_PROVIDER' => NULL,
                'CA_ONLINECMPL_URL' => NULL,
                'CA_ONLINECMPL_ACCNO' => NULL,
                'CA_ONLINECMPL_IND' => NULL,
                'CA_ONLINECMPL_CASENO' => NULL,
                'CA_ONLINEADD_IND' => NULL
            ]);
            $this->validate($request, [
                'CA_RCVTYP' => 'required',
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                'CA_AGAINST_PREMISE' => 'required',
                'CA_SUMMARY' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_AGAINST_STATECD' => 'required',
                'CA_AGAINST_DISTCD' => 'required',
//                'CA_AGAINST_POSTCD' => 'min:5|max:5',
                'CA_RESULT' => 'required',
                'CA_ANSWER' => 'required',
                'CA_RCVDT' => 'required',
//                'CA_COMPLETEDT' => 'required',
                'CA_INVBY' => 'required',
                'CA_BPANO' => 'required_if:CA_RCVTYP,S14',
                'CA_SERVICENO' => 'required_if:CA_RCVTYP,S33',
                'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
//                'CA_AGAINST_TELNO' => 'required_without_all:CA_AGAINST_MOBILENO,CA_AGAINST_EMAIL',
//                'CA_AGAINST_MOBILENO' => 'required_without_all:CA_AGAINST_TELNO,CA_AGAINST_EMAIL',
//                'CA_AGAINST_EMAIL' => 'required_without_all:CA_AGAINST_MOBILENO,CA_AGAINST_TELNO',
            ],
            [
                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan',
                'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan',
                'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis) diperlukan',
                'CA_AGAINSTADD.required_unless' => 'Ruangan Alamat diperlukan',
                'CA_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan',
                'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan',
                'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan',
                'CA_RCVDT.required' => 'Ruangan Tarikh Terima Aduan diperlukan',
//                'CA_COMPLETEDT.required' => 'Ruangan Tarikh Selesai Aduan diperlukan',
                'CA_INVBY.required' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan',
                'CA_BPANO.required_if' => 'Ruangan No. Aduan BPA diperlukan',
                'CA_SERVICENO.required_if' => 'Ruangan No. Tali Khidmat diperlukan',
                'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian diperlukan',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan',
                'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan',
            ]);
        }
//        else{
//            $this->validate($request, [
//                'CA_STATECD' => 'required_unless:CA_ONLINEADD_IND,on,CA_CMPLCAT,BPGK 19',
//                'CA_DISTCD' => 'required_unless:CA_ONLINEADD_IND,on,CA_CMPLCAT,BPGK 19',
//            ],
//            [
//                'CA_STATECD.required_unless' => 'Ruangan Negeri diperlukan',
//                'CA_DISTCD.required_unless' => 'Ruangan Daerah diperlukan',
//            ]);
//        }
        $this->validate($request, [
            'CA_RCVTYP' => 'required',
//            'CA_STATECD' => 'required_unless:CA_ONLINEADD_IND,on,CA_CMPLCAT,BPGK 19',
//            'CA_DISTCD' => 'required_unless:CA_ONLINEADD_IND,on,CA_CMPLCAT,BPGK 19',
            'CA_STATECD' => 'required_if:CA_ONLINEADD_IND,0,CA_CMPLCAT,BPGK 19',
            'CA_DISTCD' => 'required_if:CA_ONLINEADD_IND,0,CA_CMPLCAT,BPGK 19',
            'CA_CMPLCAT' => 'required',
            'CA_CMPLCD' => 'required',
            'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
            'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
            'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
            'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
//            'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on',
            'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
            'CA_SUMMARY' => 'required',
            'CA_AGAINSTNM' => 'required',
//            'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
//            'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
//            'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,1,CA_CMPLCAT,BPGK 19',
            'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,1,CA_CMPLCAT,BPGK 19',
            'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,1,CA_CMPLCAT,BPGK 19',
//            'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_RESULT' => 'required',
            'CA_ANSWER' => 'required',
            'CA_RCVDT' => 'required',
//            'CA_COMPLETEDT' => 'required',
            'CA_INVBY' => 'required',
            'CA_BPANO' => 'required_if:CA_RCVTYP,S14',
            'CA_SERVICENO' => 'required_if:CA_RCVTYP,S33',
            'CA_ONLINECMPL_AMOUNT' => 'required',
            'CA_ONLINEADD_IND' => 'required_if:CA_CMPLCAT,BPGK 19',
//            'CA_AGAINST_TELNO' => 'required_without_all:CA_AGAINST_MOBILENO,CA_AGAINST_EMAIL',
//            'CA_AGAINST_MOBILENO' => 'required_without_all:CA_AGAINST_TELNO,CA_AGAINST_EMAIL',
//            'CA_AGAINST_EMAIL' => 'required_without_all:CA_AGAINST_MOBILENO,CA_AGAINST_TELNO',
        ],
        [
            'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan',
            'CA_STATECD.required_if' => 'Ruangan Negeri diperlukan',
            'CA_DISTCD.required_if' => 'Ruangan Daerah diperlukan',
            'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan',
            'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan',
            'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL / ID diperlukan',
            'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan',
            'CA_ONLINECMPL_BANKCD.required_unless' => 'Ruangan Nama Bank diperlukan',
            'CA_ONLINECMPL_ACCNO.required_unless' => 'Ruangan No. Akaun Bank diperlukan',
            'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis/Penjual) diperlukan',
            'CA_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan',
            'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan',
            'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan',
            'CA_RCVDT.required' => 'Ruangan Tarikh Terima Aduan diperlukan',
//            'CA_COMPLETEDT.required' => 'Ruangan Tarikh Selesai Aduan diperlukan',
            'CA_INVBY.required' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan',
            'CA_BPANO.required_if' => 'Ruangan No. Aduan BPA diperlukan',
            'CA_SERVICENO.required_if' => 'Ruangan No. Tali Khidmat diperlukan',
            'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian diperlukan',
            'CA_ONLINECMPL_AMOUNT.required_if' => 'Ruangan Cara Pembayaran diperlukan',
            'CA_AGAINSTADD.required_if' => 'Ruangan Alamat diperlukan',
            'CA_AGAINST_STATECD.required_if' => 'Ruangan Negeri diperlukan',
            'CA_AGAINST_DISTCD.required_if' => 'Ruangan Daerah diperlukan',
            'CA_ONLINEADD_IND.required_if' => 'Ruangan pilihan alamat penjual / pihak yang diadu diperlukan',
        ]);
        
        $model = new SasCase();
        $model->fill($request->all());
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $model->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
        }else{
            $model->CA_CMPLKEYWORD = NULL;
        }
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($request->CA_ONLINECMPL_IND) {
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
            }
            $model->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
        }else{
            $model->CA_ONLINECMPL_URL = NULL;
        }
//        $deptcd = explode(' ', $request->CA_CMPLCAT);
//        $model->CA_DEPTCD = $deptcd[0];
        $DeptCd = explode(' ', $model->CA_CMPLCAT)[0];
        $model->CA_DEPTCD = $DeptCd;
//        $model->CA_CASEID = SasCase::getNoAduan();
        // $model->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), 'SAS0');
        $model->CA_CASEID = RunnerRepository::generateAppNumber('AK', date('y'), 'AK0');
        $model->CA_RCVDT = date('Y-m-d H:i:s', strtotime($request->CA_RCVDT));
        if($request->CA_COMPLETEDT){
            $model->CA_COMPLETEDT = date('Y-m-d H:i:s', strtotime($request->CA_COMPLETEDT));
        } 
        else
        {
            $model->CA_COMPLETEDT = NULL;
        }
        $model->CA_INVSTS = '9'; // Pengesahan Penutupan
        $model->CA_CASESTS = '2'; // Telah Diberi Penugasan
        $model->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);
//        if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19')
        if(($request->CA_ONLINEADD_IND == '1' && $request->CA_CMPLCAT == 'BPGK 19')|| $request->CA_CMPLCAT != 'BPGK 19')
        {
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
        if($request->CA_ROUTETOHQIND && $request->CA_ROUTETOHQIND == 'on') {
            $model->CA_ROUTETOHQIND = '1';
            $model->CA_BRNCD = 'WHQR5';
        }else{
            $model->CA_ROUTETOHQIND = '0';
            // $model->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, false);
            $model->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
        }
        if($model->save()) {
            $mCaseDtl = new SasCaseDetail();
            $mCaseDtl->CD_CASEID = $model->CA_CASEID;
            $mCaseDtl->CD_TYPE = 'D';
            $mCaseDtl->CD_ACTTYPE = 'CLS';
            $mCaseDtl->CD_INVSTS = $model->CA_INVSTS;
            $mCaseDtl->CD_CASESTS = $model->CA_CASESTS;
            $mCaseDtl->CD_CURSTS = '1';
            if($mCaseDtl->save()) {
//                return redirect('/sas-case/edit/'.$model->CA_CASEID)
                if($request->CA_EMAIL != '')
                {
//                    Mail::to($request->CA_EMAIL)->queue(new AduanTutupSas($model)); // Send pakai queue
                    Mail::to($request->CA_EMAIL)->send(new AduanTutupSas($model)); // Send biasa
                }
                return redirect('/sas-case/edit/'. $model->CA_CASEID. '#attachment')
                    // ->with('success', 'Aduan Profil Tinggi telah berjaya ditambah');
                    ->with('success', 'Aduan Khas telah berjaya ditambah');
            }
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($CASEID)
    {
        $mSasCase = SasCase::find($CASEID);
        $mUser = User::find(Auth::User()->id);
        if(!empty($mSasCase->CA_RCVBY)) {
            $mUserRcvBy = User::find($mSasCase->CA_RCVBY);
            $RcvBy = $mUserRcvBy->name;
        }else{
            $RcvBy = '';
        }
        if(!empty($mSasCase->CA_INVBY)) {
            $mUserInvBy = User::find($mSasCase->CA_INVBY);
            $InvBy = $mUserInvBy->name;
        }else{
            $InvBy = '';
        }
        $countSasCaseDoc = DB::table('case_doc')
//            ->where('CC_CASEID', $CASEID)
            ->where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 1])
            ->count('CC_CASEID')
        ;
        $countSasCaseDocSiasat = DB::table('case_doc')
            ->where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 2])
            ->count('CC_CASEID');
        $countAkta = DB::table('case_act')
                ->where(['CT_CASEID' => $CASEID])
                ->count('CT_CASEID');
        return view('sas-case.update', compact('mSasCase', 'mUser', 'RcvBy', 'InvBy', 'countSasCaseDoc', 'countSasCaseDocSiasat', 'countAkta'));
    }

    public function update(Request $request, $CASEID)
    {
        if($request->CA_CMPLCAT != 'BPGK 19') {
            $request->merge([
                'CA_ONLINECMPL_PROVIDER' => NULL,
                'CA_ONLINECMPL_URL' => NULL,
                'CA_ONLINECMPL_ACCNO' => NULL,
                'CA_ONLINECMPL_IND' => NULL,
                'CA_ONLINECMPL_CASENO' => NULL,
                'CA_ONLINEADD_IND' => NULL
            ]);
            $this->validate($request, [
                'CA_RCVTYP' => 'required',
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
                'CA_AGAINST_PREMISE' => 'required',
                'CA_SUMMARY' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_AGAINST_STATECD' => 'required',
                'CA_AGAINST_DISTCD' => 'required',
//                'CA_AGAINST_POSTCD' => 'min:5|max:5',
                'CA_RESULT' => 'required',
                'CA_ANSWER' => 'required',
                'CA_RCVDT' => 'required',
//                'CA_COMPLETEDT' => 'required',
                'CA_INVBY' => 'required',
                'CA_BPANO' => 'required_if:CA_RCVTYP,S14',
                'CA_SERVICENO' => 'required_if:CA_RCVTYP,S33',
                'CA_ONLINECMPL_AMOUNT' => 'required',
//                'CA_AGAINST_TELNO' => 'required_without_all:CA_AGAINST_MOBILENO,CA_AGAINST_EMAIL',
//                'CA_AGAINST_MOBILENO' => 'required_without_all:CA_AGAINST_TELNO,CA_AGAINST_EMAIL',
//                'CA_AGAINST_EMAIL' => 'required_without_all:CA_AGAINST_MOBILENO,CA_AGAINST_TELNO',
            ],
            [
                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan',
                'CA_CMPLCAT.required' => 'Ruangan Kategori Aduan diperlukan',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan',
                'CA_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan',
                'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan',
                'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan',
                'CA_RCVDT.required' => 'Ruangan Tarikh Terima Aduan diperlukan',
//                'CA_COMPLETEDT.required' => 'Ruangan Tarikh Selesai Aduan diperlukan',
                'CA_INVBY.required' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan',
                'CA_BPANO.required_if' => 'Ruangan No. Aduan BPA diperlukan',
                'CA_SERVICENO.required_if' => 'Ruangan No. Tali Khidmat diperlukan',
                'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian diperlukan',
                'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan',
                'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis) diperlukan',
                'CA_AGAINSTADD.required_unless' => 'Ruangan Alamat diperlukan',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan',
                'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan',
            ]);
        }
//        else{
//            $this->validate($request, [
//                'CA_STATECD' => 'required_unless:CA_ONLINEADD_IND,on,CA_CMPLCAT,BPGK 19',
//                'CA_DISTCD' => 'required_unless:CA_ONLINEADD_IND,on,CA_CMPLCAT,BPGK 19',
//            ],
//            [
//                'CA_STATECD.required_unless' => 'Ruangan Negeri diperlukan',
//                'CA_DISTCD.required_unless' => 'Ruangan Daerah diperlukan',
//            ]);
//        }
        $this->validate($request, [
            'CA_RCVTYP' => 'required',
//            'CA_STATECD' => 'required_unless:CA_ONLINEADD_IND,on,CA_CMPLCAT,BPGK 19',
//            'CA_DISTCD' => 'required_unless:CA_ONLINEADD_IND,on,CA_CMPLCAT,BPGK 19',
            'CA_STATECD' => 'required_if:CA_ONLINEADD_IND,0,CA_CMPLCAT,BPGK 19',
            'CA_DISTCD' => 'required_if:CA_ONLINEADD_IND,0,CA_CMPLCAT,BPGK 19',
            'CA_CMPLCAT' => 'required',
            'CA_CMPLCD' => 'required',
            'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
            'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
            'CA_ONLINECMPL_AMOUNT' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
//            'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on',
            'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
            'CA_SUMMARY' => 'required',
            'CA_AGAINSTNM' => 'required',
//            'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
//            'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
//            'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,1,CA_CMPLCAT,BPGK 19',
            'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,1,CA_CMPLCAT,BPGK 19',
            'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,1,CA_CMPLCAT,BPGK 19',
//            'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_RESULT' => 'required',
            'CA_ANSWER' => 'required',
            'CA_RCVDT' => 'required',
//            'CA_COMPLETEDT' => 'required',
            'CA_INVBY' => 'required',
            'CA_BPANO' => 'required_if:CA_RCVTYP,S14',
            'CA_SERVICENO' => 'required_if:CA_RCVTYP,S33',
            'CA_ONLINECMPL_AMOUNT' => 'required',
            'CA_SSP' => 'required',
            'CA_ONLINEADD_IND' => 'required_if:CA_CMPLCAT,BPGK 19',
//            'CA_AGAINST_TELNO' => 'required_without_all:CA_AGAINST_MOBILENO,CA_AGAINST_EMAIL',
//            'CA_AGAINST_MOBILENO' => 'required_without_all:CA_AGAINST_TELNO,CA_AGAINST_EMAIL',
//            'CA_AGAINST_EMAIL' => 'required_without_all:CA_AGAINST_MOBILENO,CA_AGAINST_TELNO',
        ],
        [
            'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan',
//            'CA_STATECD.required_unless' => 'Ruangan Negeri diperlukan',
//            'CA_DISTCD.required_unless' => 'Ruangan Daerah diperlukan',
            'CA_STATECD.required_if' => 'Ruangan Negeri diperlukan',
            'CA_DISTCD.required_if' => 'Ruangan Daerah diperlukan',
            'CA_CMPLCAT.required' => 'Ruangan Kategori Aduan diperlukan',
            'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan',
            'CA_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan',
            'CA_RESULT.required' => 'Ruangan Hasil Siasatan diperlukan',
            'CA_ANSWER.required' => 'Ruangan Jawapan Kepada Pengadu diperlukan',
            'CA_RCVDT.required' => 'Ruangan Tarikh Terima Aduan diperlukan',
//            'CA_COMPLETEDT.required' => 'Ruangan Tarikh Selesai Aduan diperlukan',
            'CA_INVBY.required' => 'Ruangan Pegawai Penyiasat/Serbuan diperlukan',
            'CA_BPANO.required_if' => 'Ruangan No. Aduan BPA diperlukan',
            'CA_SERVICENO.required_if' => 'Ruangan No. Tali Khidmat diperlukan',
            'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian diperlukan',
            'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan',
            'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan',
            'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL diperlukan',
            'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan',
            'CA_ONLINECMPL_BANKCD.required_unless' => 'Ruangan Nama Bank diperlukan',
            'CA_ONLINECMPL_ACCNO.required_unless' => 'Ruangan No. Akaun Bank diperlukan',
            'CA_ONLINECMPL_CASENO.required_if' => 'Ruangan No. Aduan Rujukan diperlukan',
            'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis) diperlukan',
            'CA_AGAINSTADD.required_if' => 'Ruangan Alamat diperlukan',
            'CA_AGAINST_STATECD.required_if' => 'Ruangan Negeri diperlukan',
            'CA_AGAINST_DISTCD.required_if' => 'Ruangan Daerah diperlukan',
            'CA_SSP.required' => 'Ruangan Wujud Kes diperlukan',
            'CA_ONLINEADD_IND.required_if' => 'Ruangan pilihan alamat penjual / pihak yang diadu diperlukan',
        ]);
        
        $mSasCase = SasCase::find($CASEID);
        $mSasCase->fill($request->all());
        $mSasCase->CA_RCVDT = date('Y-m-d H:i:s', strtotime($request->CA_RCVDT));
        $mSasCase->CA_COMPLETEDT = date('Y-m-d H:i:s', strtotime($request->CA_COMPLETEDT));
        $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        $mSasCase->CA_DEPTCD = $DeptCd;
        if($request->CA_ONLINECMPL_AMOUNT == NULL){
            $mSasCase->CA_ONLINECMPL_AMOUNT = 0.00;
        } else {
            $mSasCase->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);
        }
        if ($mSasCase->CA_NATCD == '1') {
            $mSasCase->CA_COUNTRYCD = NULL;
        }
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $mSasCase->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
        }else{
            $mSasCase->CA_CMPLKEYWORD = NULL;
        }
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($mSasCase->CA_ONLINECMPL_IND) {
                $mSasCase->CA_ONLINECMPL_IND = '1';
                $mSasCase->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            }else{
                $mSasCase->CA_ONLINECMPL_IND = '0';
                $mSasCase->CA_ONLINECMPL_CASENO = NULL;
            }
            if($request->CA_ONLINEADD_IND) {
                $mSasCase->CA_ONLINEADD_IND = '1';
            }else{
                $mSasCase->CA_ONLINEADD_IND = '0';
                $mSasCase->CA_AGAINSTADD = NULL;
                $mSasCase->CA_AGAINST_STATECD = NULL;
                $mSasCase->CA_AGAINST_DISTCD = NULL;
                $mSasCase->CA_AGAINST_POSTCD = NULL;
            }
            $mSasCase->CA_ONLINECMPL_BANKCD = $request->CA_ONLINECMPL_BANKCD;
            $mSasCase->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            $mSasCase->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
            $mSasCase->CA_AGAINST_PREMISE = NULL;
        }else{
            $mSasCase->CA_ONLINECMPL_URL = NULL;
        }
        if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19'){
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        }else{
            $StateCd = $request->CA_STATECD;
            $DistCd = $request->CA_DISTCD;
            $mSasCase->CA_AGAINSTADD = NULL;
            $mSasCase->CA_AGAINST_POSTCD = NULL;
            $mSasCase->CA_AGAINST_STATECD = NULL;
            $mSasCase->CA_AGAINST_DISTCD = NULL;
        }
        if($request->CA_ROUTETOHQIND && $request->CA_ROUTETOHQIND == 'on') {
            $mSasCase->CA_ROUTETOHQIND = '1';
            $mSasCase->CA_BRNCD = 'WHQR5';
        }else{
            $mSasCase->CA_ROUTETOHQIND = '0';
            // $mSasCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, false);
            $mSasCase->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
        }
        if($mSasCase->save()) {
            $mCaseDtl = new SasCaseDetail();
            $mCaseDtl->CD_CASEID = $mSasCase->CA_CASEID;
            $mCaseDtl->CD_INVSTS = $mSasCase->CA_INVSTS;
            $mCaseDtl->CD_CASESTS = $mSasCase->CA_CASESTS;
            $mCaseDtl->CD_DESC = 'Kemaskini Aduan';
            if($mCaseDtl->save()) {
                if($request->submit == 2){
                    if($mSasCase->CA_EMAIL != '')
                    {
//                        Mail::to($request->CA_EMAIL)->queue(new AduanTutupSas($model)); // Send pakai queue
                        Mail::to($mSasCase->CA_EMAIL)->send(new AduanTutupSas($mSasCase)); // Send biasa
                    }
                }
//                return redirect()->back()->with('success', 'Aduan Profil Tinggi telah berjaya dikemaskini');
                // return redirect('sas-case')->with('success', 'Aduan Profil Tinggi telah berjaya dikemaskini');
                return redirect('sas-case')->with('success', 'Aduan Khas telah berjaya dikemaskini');
            }
        }
    }
    
    public function createAttachment(Request $request)
    {
//        $file = $request->file('file');
//        $date = date('Ymdhis');
//        $userid = Auth::user()->id;
//        $filename = $userid.'_'.$date.'.'.$file->getClientOriginalExtension();
//        
//        //Move Uploaded File
//        $destinationPath = 'uploads';
//        $file->move($destinationPath,$filename);
//        
//        $mAttachment = new Attachment();
//        $mAttachment->doc_title = $request->doc_title;
//        $mAttachment->file_name = $file->getClientOriginalName();
//        $mAttachment->file_name_sys = $filename;
//        if($mAttachment->save()) {
//            $mSasCaseDoc = new \App\SasCaseDoc();
//            $mSasCaseDoc->CC_DOCATTCHID = $mAttachment->id;
//            $mSasCaseDoc->CC_CASEID = $request->CA_CASEID;
//            if($mSasCaseDoc->save()) {
//                $request->session()->flash('success', 'Aduan Profil Tinggi telah berjaya ditambah');
//                return redirect('/sas-case/update/'.$request->CA_CASEID.'#attachment');
//            }
//        }
        
//        $file = $request->file('file');
//   
//        //Display File Name
//        echo 'File Name: '.$file->getClientOriginalName();
//        echo '<br>';
//
//        //Display File Extension
//        echo 'File Extension: '.$file->getClientOriginalExtension();
//        echo '<br>';
//
//        //Display File Real Path
//        echo 'File Real Path: '.$file->getRealPath();
//        echo '<br>';
//
//        //Display File Size
//        echo 'File Size: '.$file->getSize();
//        echo '<br>';
//
//        //Display File Mime Type
//        echo 'File Mime Type: '.$file->getMimeType();
//        echo '<br>';
//        
//        echo $filename;
        $date = date('YmdHis');
        $userid = Auth::user()->id;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');
        if($file) {
            $filename = $userid.'_'.$request->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension();
            $directory = '/'.$Year.'/'.$Month.'/';
            Storage::disk('bahan')->makeDirectory($directory);
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
                $path = "images/{$hash}.jpg"; // use hash as a name
                $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
                Storage::disk('bahan')->put($directory.$filename, $resize->__toString());
                unlink($path);
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            $mSasCaseDoc = new SasCaseDoc();
            $mSasCaseDoc->CC_CASEID = $request->CC_CASEID;
            $mSasCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mSasCaseDoc->CC_IMG = $filename;
            $mSasCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mSasCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mSasCaseDoc->CC_IMG_CAT = 1;
            if($mSasCaseDoc->save()) {
//                $request->session()->flash('success', 'Bahan Bukti telah berjaya ditambah');
                return redirect('/sas-case/edit/'. $request->CC_CASEID. '#attachment');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function getDistrinctList($STATECD)
    {
        $mRef = DB::table('sys_ref')
            ->where('cat', "18")
            ->where('status', '1')
            ->where('code', 'LIKE',  "$STATECD%")
            ->orderBy('descr')
            ->pluck('code','descr');
        if(count($mRef) > 1) {
            $mRef->prepend('', '-- SILA PILIH --');
        }
        return json_encode($mRef);
    }
    
    public function getdataAttachment($CASEID)
    {
        $mSasCaseDoc = SasCaseDoc::
//                where('CC_CASEID',$CASEID)
                where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 1])
                ;
        
        $datatables = Datatables::of($mSasCaseDoc)
                ->addIndexColumn()
//                ->editColumn('doc_title', function(SasCaseDoc $SasCaseDoc) {
//                    if($SasCaseDoc->CC_DOCATTCHID != '')
//                    return $SasCaseDoc->attachment->doc_title;
//                    else
//                    return '';
//                })
//                ->editColumn('file_name_sys', function(SasCaseDoc $SasCaseDoc) {
//                    if($SasCaseDoc->CC_DOCATTCHID != '')
//                    return '<a href='.url("uploads/{$SasCaseDoc->attachment->file_name_sys}").' target="_blank">'.$SasCaseDoc->attachment->file_name.'</a>';
//                    else
//                    return '';
//                })
                ->editColumn('CC_IMG_NAME', function(SasCaseDoc $SasCaseDoc) {
                    if($SasCaseDoc->CC_IMG_NAME != '')
                        return '<a href='.Storage::disk('bahanpath')->url($SasCaseDoc->CC_PATH.$SasCaseDoc->CC_IMG).' target="_blank">'.$SasCaseDoc->CC_IMG_NAME.'</a>';
                    else
                        return '';
                })
                ->editColumn('updated_at', function(SasCaseDoc $SasCaseDoc) {
                    if($SasCaseDoc->updated_at != '')
                        return $SasCaseDoc->updated_at ? with(new Carbon($SasCaseDoc->updated_at))->format('d-m-Y h:i A') : '';
                    else
                        return '';
                })
                ->addColumn('action', function(SasCaseDoc $SasCaseDoc) {
                    return view('sas-case.attachmentactionbutton', compact('SasCaseDoc'))->render();
                })
                ->rawColumns(['CC_IMG_NAME', 'action'])
                ;
        
        return $datatables->make(true);
    }
    
    public function getdataTransaction($CASEID)
    {
        $mSasCaseDetail = SasCaseDetail::where(['CD_CASEID'=>$CASEID])->orderBy('CD_CREDT', 'ASC');
        
        $datatables = Datatables::of($mSasCaseDetail)
                ->addIndexColumn()
                ->editColumn('CD_INVSTS', function(SasCaseDetail $SasCaseDetail) {
                    if($SasCaseDetail->CD_INVSTS != '')
                        return $SasCaseDetail->statusaduan->descr;
                    else
                        return '';
                })
                ->editColumn('CD_DESC', function(SasCaseDetail $SasCaseDetail) {
                    if($SasCaseDetail->CD_CASEID != '')
                    return $SasCaseDetail->CD_DESC;
                    else
                    return '';
                })
                ->editColumn('CD_CREBY', function(SasCaseDetail $SasCaseDetail) {
                    if($SasCaseDetail->CD_CREBY != '')
                        return $SasCaseDetail->creby->name;
                    else
                        return '';
                })
                ->editColumn('CD_CREDT', function(SasCaseDetail $SasCaseDetail) {
                    return $SasCaseDetail->CD_CREDT ? with(new Carbon($SasCaseDetail->CD_CREDT))->format('d-m-Y h:i A') : '';
                })
                ;
        
        return $datatables->make(true);
    }
    
    public function destroy($id)
    {
        //
    }
    
    public function getCmplCdList($CMPLCAT)
    {
        $CmplCdList = DB::table('sys_ref')
            ->where(['cat' => '634', 'status' => '1'])
            ->where('code', 'LIKE', "$CMPLCAT%")
            ->orderBy('sort', 'asc')
//            ->orderBy('descr', 'asc')
            ->pluck('code', 'descr');
        if(count($CmplCdList) > 1) {
            $CmplCdList->prepend('', '-- SILA PILIH --');
        }
        return $CmplCdList;
    }
    
    public function getCmplkeywordList()
    {
        $CmplkeywordList = DB::table('sys_ref')
            ->where(['cat' => '1051', 'status' => '1'])
            ->orderBy('sort', 'asc')
            ->pluck('code', 'descr')
            ->prepend('', '-- SILA PILIH --');
        return $CmplkeywordList;
    }
    
    public function getdatatableuser(Request $request) {
        $mUser = User::with('role')
            ->select('id','username','name','state_cd','brn_cd')
            ->where(['user_cat' => '1', 'status' => '1']);
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
                if ($request->has('role_cd')) {
                    $query->join('sys_user_access', 'sys_users.id', '=', 'sys_user_access.user_id')->where('sys_user_access.role_code', $request->get('role_cd'));
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
    
       public function ShowSummary($CASEID)
    {
        
        
        $model = SasCase::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = SasCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = SasCaseDoc::where(['CC_CASEID' => $CASEID])->get();
        return view('sas-case.show_summary_modal', compact('model','trnsksi','img'));
    }
    
    public function PrintSummary($CASEID)
    {
        $model = SasCase::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = SasCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = SasCaseDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('sas-case.show_summary_modal', compact('model','trnsksi','img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }
    
    public function createdoc($id)
    {
        return view('sas-case.createdoc', compact('id'));
    }
    
    public function ajaxvalidatestoreattachment(Request $request)
    {
        $file = $request->file('file');
        if(empty($file)) {
            $validator = Validator::make($request->all(), 
                [
                    'file' => 'required'
                ],
                [
                    'file.required' => 'Ruangan Fail diperlukan.',
                ]
            );
        } else if($file->getClientOriginalExtension() == 'pdf' || $file->getClientOriginalExtension() == 'PDF') {
            $validator = Validator::make($request->all(), 
                [
                    'file' => 'required | max:2048'
                ],
                [
                    'file.required' => 'Ruangan Fail diperlukan.',
                    'file.max' => 'Fail format pdf mesti tidak melebihi 2Mb.',
                ]
            );
        } else {
            $validator = Validator::make($request->all(), 
                [
                    'file' => 'required | mimes:jpeg,jpg,png,gif,pdf'
                ], 
                [
                    'file.required' => 'Ruangan Fail diperlukan.',
                    'file.mimes' => 'Format fail mesti gif, jpeg, jpg, png, pdf.',
                ]
            );
        }
        
        if ($validator->fails()) {
            return response()->json(['fails' => $validator->getMessageBag()]);
        }else{
            return response()->json(['success']);
        }
    }
    
    public function editdoc($id)
    {
        $mSasCaseDoc = SasCaseDoc::find($id);
        return view('sas-case.editdoc', compact('mSasCaseDoc'));
    }
    
    public function updatedoc(Request $request, $id){
        $mSasCaseDoc = SasCaseDoc::find($id);
        $file = $request->file('file');
        $date = date('YmdHis');
        $userid = Auth()->user()->id;
        $Year = date('Y');
        $Month = date('m');

        if ($file) {
            Storage::delete($mSasCaseDoc->CC_PATH.$mSasCaseDoc->CC_IMG); // Delete old attachment
            $filename = $userid.'_'.$mSasCaseDoc->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension(); // Store new attachment
            $directory = '/'.$Year.'/'.$Month.'/';
            Storage::disk('bahan')->makeDirectory($directory);
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
                $path = "images/{$hash}.jpg"; // use hash as a name
                $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
                Storage::disk('bahan')->put($directory.$filename, $resize->__toString());
                unlink($path);
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            // Update record
            $mSasCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mSasCaseDoc->CC_IMG = $filename;
            $mSasCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mSasCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mSasCaseDoc->CC_IMG_CAT = 1;
            // Save record
            if ($mSasCaseDoc->save()) {
                return redirect()->route('sas-case.edit', ['id' => $mSasCaseDoc->CC_CASEID, '#attachment']);
            }
        } else {
            $mSasCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            if ($mSasCaseDoc->save()) {
                return redirect()->route('sas-case.edit', ['id' => $mSasCaseDoc->CC_CASEID, '#attachment']);
            }
        }
    }
    
    public function destroydoc($id){
        $model = SasCaseDoc::find($id);
        Storage::delete($model->CC_PATH.$model->CC_IMG);
        if($model->delete()){
            return redirect('/sas-case/edit/'.$model->CC_CASEID.'#attachment');
        }
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
    
    public function getdocsiasat($CASEID)
    {
        $mSasSiasatDoc = SasCaseDoc::where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 2])->get();
        $datatables = Datatables::of($mSasSiasatDoc)
            ->addIndexColumn()
            ->editColumn('CC_IMG_NAME', function(SasCaseDoc $sasSiasatDoc) {
                if($sasSiasatDoc->CC_IMG_NAME != '')
                    return '<a href='.Storage::disk('bahanpath')->url($sasSiasatDoc->CC_PATH.$sasSiasatDoc->CC_IMG).' target="_blank">'.$sasSiasatDoc->CC_IMG_NAME.'</a>';
                else
                    return '';
            })
            ->editColumn('updated_at', function(SasCaseDoc $sasSiasatDoc) {
                if($sasSiasatDoc->updated_at != '')
                    return $sasSiasatDoc->updated_at ? with(new Carbon($sasSiasatDoc->updated_at))->format('d-m-Y h:i A') : '';
                else
                    return '';
            })
            ->addColumn('action', function (SasCaseDoc $sasSiasatDoc) {
                return view('aduan.sas-case.actionbuttondocsiasat', compact('sasSiasatDoc'))->render();
            })
            ->rawColumns(['action','CC_IMG_NAME'])
        ;
        return $datatables->make(true);
    }
    
    public function createdocsiasat($CASEID)
    {
        return view('aduan.sas-case.createdocsiasat', compact('CASEID'));
    }
    
    public function storedocsiasat(Request $request)
    {
        $date = date('YmdHis');
        $userid = Auth::user()->id;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');
        if($file) {
            $filename = $userid.'_'.$request->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension();
            $directory = '/'.$Year.'/'.$Month.'/';
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $resize->stream();
                Storage::disk('bahan')->put($directory.$filename, $resize);
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            $mSasCaseDoc = new SasCaseDoc();
            $mSasCaseDoc->CC_CASEID = $request->CC_CASEID;
            $mSasCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mSasCaseDoc->CC_IMG = $filename;
            $mSasCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mSasCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mSasCaseDoc->CC_IMG_CAT = 2;
            if($mSasCaseDoc->save()) {
                return redirect()->route('sas-case.edit', ['id' => $mSasCaseDoc->CC_CASEID, '#attachment']);
            }
        }
    }
    
    public function editdocsiasat($id) {
        $mSasCaseDoc = SasCaseDoc::find($id);
        return view('aduan.sas-case.editdocsiasat', compact('mSasCaseDoc'));
    }
    
    public function updatedocsiasat(Request $request, $id)
    {
        $mSasCaseDoc = SasCaseDoc::find($id);
        $file = $request->file('file');
        $date = date('YmdHis');
        $userid = Auth()->user()->id;
        $Year = date('Y');
        $Month = date('m');
        if ($file) {
            Storage::delete($mSasCaseDoc->CC_PATH.$mSasCaseDoc->CC_IMG); // Delete old attachment
            $filename = $userid.'_'.$mSasCaseDoc->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension(); // Store new attachment
            $directory = '/'.$Year.'/'.$Month.'/';
            if($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $resize->stream();
                Storage::disk('bahan')->put($directory.$filename, $resize);
            } else {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            // Update record
            $mSasCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mSasCaseDoc->CC_IMG = $filename;
            $mSasCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mSasCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            // Save record
            if ($mSasCaseDoc->save()) {
                return redirect()->route('sas-case.edit', ['id' => $mSasCaseDoc->CC_CASEID, '#attachment']);
//                return redirect()->back();
            }
        } else {
            $mSasCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            if ($mSasCaseDoc->save()) {
                return redirect()->route('sas-case.edit', ['id' => $mSasCaseDoc->CC_CASEID, '#attachment']);
//                return redirect()->back();
            }
        }
    }
    
    public function getakta($CASEID)
    {
        $mSiasatKes = PenyiasatanKes::where(['CT_CASEID' => $CASEID])->get();
        $datatables = DataTables::of($mSiasatKes)
            ->addIndexColumn()
            ->editColumn('CT_AKTA', function(PenyiasatanKes $Siasatkes) {
                if($Siasatkes->CT_AKTA != '')
                    return $Siasatkes->Akta->descr;
                else
                    return '';
            })
            ->editColumn('CT_SUBAKTA', function(PenyiasatanKes $Siasatkes) {
                if($Siasatkes->CT_SUBAKTA != '')
                    return $Siasatkes->SubAkta->descr;
                else
                    return '';
            })
            ->addColumn('action', function (PenyiasatanKes $SiasatKes) {
                return view('aduan.sas-case.actionbuttonakta', compact('SiasatKes'))->render();
            })
        ;
        return $datatables->make(true);
    }
    
    public function createakta($CASEID)
    {
        return view('aduan.sas-case.createakta', compact('CASEID'));
    }
    
    public function storeakta(Request $request, $CASEID)
    {
        $mKes = new PenyiasatanKes;
        $mKes->fill($request->all());
        $mKes->CT_CASEID = $CASEID;
        if ($mKes->save()) {
            return response()->json(['success']);
//            return redirect()->route('sas-case.edit', ['id' => $mKes->CT_CASEID, '#case-info']);
        }
    }
    
    public function editakta($id) {
        $SiasatKes = PenyiasatanKes::find($id);
        return view('aduan.sas-case.editakta', compact('SiasatKes'));
    }

    public function updateakta(Request $request, $id) {
        $mKes = PenyiasatanKes::find($id);
        $mKes->fill($request->all());
        if($mKes->save()) {
            return response()->json(['success']);
//            return redirect()->route('sas-case.edit', ['id' => $mKes->CT_CASEID, '#case-info']);
        }
    }
    
    public function destroyakta($id)
    {
        $mKes = PenyiasatanKes::find($id);
        if($mKes->delete()){
            return response()->json(['success']);
//            return redirect()->route('sas-case.edit', ['id' => $mKes->CT_CASEID, '#case-info']);
        }
    }
}
