<?php

namespace App\Http\Controllers\Aduan;

use App\Aduan\CallCenterCase;
use App\Aduan\CallCenterCaseDetail;
use App\Aduan\CallCenterCaseDoc;
use App\Attachment;
use App\Events\CallCenterSubmit;
use App\Http\Controllers\Controller;
use App\KodMapping;
use App\Letter;
use App\Mail\AduanTerimaCallCenter;
use App\PenugasanCaseDetail;
use App\Repositories\ConsumerComplaint\CaseInfoRepository;
use App\Repositories\RunnerRepository;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Image;
use PDF;
use SoapClient;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class CallCenterCaseController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('aduan.call-center-case.index');
    }

    public function getdatatable(Request $request) {
//        $datatables = Datatables::of([]);
//        if($datatables->request->get('SEARCH') == 1) {
        $TempohPertama = \App\Ref::find(1244);
        $TempohKedua = \App\Ref::find(1245);
        $TempohKetiga = \App\Ref::find(1246);
        $mCallCenterCase = CallCenterCase::where([
//                    ['CA_CASEID','<>',null],
                    ['CA_RCVTYP','=','S28'],
                    ['CA_CREBY','=',Auth::user()->id],
                ])
//                ->whereIn('CA_INVSTS', ['10', '1'])
                ->orderBy('CA_RCVDT', 'DESC');
        
        $datatables = Datatables::of($mCallCenterCase)
                ->addIndexColumn()
                ->editColumn('CA_SUMMARY', function(CallCenterCase $callcase) {
//                    return '<div style="height:80px; overflow-x:hidden; overflow-y:scroll">' . $callcase->CA_SUMMARY . '</div>';
                    if($callcase->CA_SUMMARY != '')
                        return implode(' ', array_slice(explode(' ', $callcase->CA_SUMMARY), 0, 7)).' ...';
                    else
                        return '';
                })
                ->editColumn('CA_RCVDT', function (CallCenterCase $callcase) {
                    if ($callcase->CA_RCVDT != '')
                        return date('d-m-Y h:i A', strtotime($callcase->CA_RCVDT));
                    else
                        return '';
                })
                ->addColumn('tempoh', function(CallCenterCase $callcase) use ($TempohPertama,$TempohKedua,$TempohKetiga) {
                    if($callcase->CA_RCVDT){
                        $totalDuration = $callcase->getduration($callcase->CA_RCVDT, $callcase->CA_CASEID);
                        if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                            return '<div style="background-color:#3F6; color: white;" align="center"><strong>' . $totalDuration . '</strong></span>';
                        else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                            return '<div style="background-color:#FF3;" align="center"><strong>' . $totalDuration . '</strong></div>';
                        else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                            return '<div style="background-color:#F0F; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                        else if ($totalDuration > $TempohKetiga->code)
                            return '<div style="background-color:#F00; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                    } else {
                        return '0';
                    }
                    
                })
                ->editColumn('CA_INVSTS', function(CallCenterCase $callcase) {
                    if ($callcase->CA_INVSTS != '')
                        return $callcase->statusAduan->descr;
                    else
                        return '';
                })
                ->editColumn('CA_CASEID', function(CallCenterCase $penugasan) {
                    return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
                })
                ->addColumn('action', function (CallCenterCase $callcentercase) {
                    $date = date('Y-m-d', strtotime(Carbon::now()));
                    return view('aduan.call-center-case.indexactionbtn', compact('callcentercase','date'));
                })
                ->rawColumns(['tempoh', 'CA_CASEID','CA_SUMMARY', 'action'])
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
            if ($request->has('CA_RCVDT')) {
                $query->whereDate('CA_RCVDT', Carbon::parse($request->get('CA_RCVDT'))->format('Y-m-d'));
            }
//            if ($CA_CASEID = $datatables->request->get('CA_CASEID')) {
//                $datatables->where('CA_CASEID', 'LIKE', "%$CA_CASEID%");
//            }
//            if ($CA_SUMMARY = $datatables->request->get('CA_SUMMARY')) {
//                $datatables->where('CA_SUMMARY', 'LIKE', "%$CA_SUMMARY%");
//            }
//            if ($CA_NAME = $datatables->request->get('CA_NAME')) {
//                $datatables->where('CA_NAME', 'LIKE', "%$CA_NAME%");
//            }
//             if ($CA_RCVDT = $datatables->request->get('CA_RCVDT')) {
//                $datatables->whereDate('CA_RCVDT', Carbon::parse($CA_RCVDT)->format('Y-m-d'));
//            }
        });
        return $datatables->make(true);
    }
    
    public function create() {
        return view('aduan.call-center-case.create');
    }

    public function GenNoAduan() {
        $mCallCenterCase = new CallCenterCase();
//        $mCallCenterCase->CA_CASEID = CallCenterCase::getNoAduan();
        $mCallCenterCase->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), '00');
        $mCallCenterCase->CA_INVSTS = '10';
        $mCallCenterCase->CA_CASESTS = '1';
        $mCallCenterCase->CA_SUMMARY = 'Aduan';
        $mCallCenterCase->CA_RCVTYP = 'S28'; //Call Center
        $mCallCenterCase->CA_RCVBY = Auth::user()->id;
        $mCallCenterCase->CA_RCVDT = Carbon::now();
        $mCallCenterCase->CA_NATCD = '1'; //Warganegara
        $mUser = User::where(['id' => Auth::user()->id])->first();
            if($mUser->brn_cd != ''){
                $mCallCenterCase->CA_BRNCD = $mUser->brn_cd;
            } else {
                $mCallCenterCase->CA_BRNCD = 'WHQR';
            }
        if ($mCallCenterCase->save()) {
            $mCallCenterCaseDetail = new CallCenterCaseDetail();
            $mCallCenterCaseDetail->CD_CASEID = $mCallCenterCase->CA_CASEID;
            $mCallCenterCaseDetail->CD_TYPE = '';
            $mCallCenterCaseDetail->CD_DESC = '';
            $mCallCenterCaseDetail->CD_ACTTYPE = '';
            $mCallCenterCaseDetail->CD_INVSTS = $mCallCenterCase->CA_INVSTS;
            $mCallCenterCaseDetail->CD_CASESTS = $mCallCenterCase->CA_CASESTS;
            $mCallCenterCaseDetail->CD_CURSTS = 1;
            $mCallCenterCaseDetail->CD_ACTFROM = '';
            $mCallCenterCaseDetail->CD_ACTTO = '';
            if ($mCallCenterCaseDetail->save()) {
                $CA_CASEID = $mCallCenterCase->CA_CASEID;
                return redirect()->route('call-center-case.display-noaduan', compact('CA_CASEID'))->with('success', 'Nombor aduan telah <b>BERJAYA</b> dijana');
//                return redirect()->route('call-center-case.index')->with('success', 'Nombor aduan telah <b>BERJAYA</b> dijana');
//                return redirect()->route('call-center-case.edit', $mCallCenterCase->CA_CASEID)->with('success', 'Nombor aduan telah <b>BERJAYA</b> dijana');
            }
        }
    }
    public function DisplayNoaduan($CA_CASEID) {
        
        return view('aduan.call-center-case.display_noaduan', compact('CA_CASEID'));
    }

    public function store1(Request $request) {

        $error = '';
        $StatusPengadu = '';
        $UserAddress = '';
//        $UserAge = '';
        $UserDist = '';
        $UserPostcode = '';
        $UserState = '';
//        $mCallCenterCase = new CallCenterCase();
//        $mCallCenterCase->fill($request->all());

        if ($request->CA_CMPLCAT != 'BPGK 19') {
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
                    ], [
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan',
                'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama(Syarikat/Premis) diperlukan.',
                'CA_AGAINSTADD.required_unless' => 'Ruangan Alamat diperlukan.',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_POSTCD.min' => 'Poskod tidak sah.',
                'CA_AGAINST_POSTCD.max' => 'Poskod tidak sah.',
                'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
            ]);
        }

        $this->validate($request, [
            'CA_DOCNO' => 'required',
            'CA_NAME' => 'required',
            'CA_AGE' => 'required',
//                'CA_EMAIL' => 'required',
            'CA_ADDR' => 'required',
//                'CA_STATECD' => 'required',
//                'CA_DISTCD' => 'required',
            'CA_CMPLCAT' => 'required',
            'CA_CMPLCD' => 'required',
            'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
            'CA_AGAINSTNM' => 'required',
            'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_SUMMARY' => 'required',
            'CA_DOCNO' => 'required',
            'CA_NAME' => 'required',
            'CA_AGE' => 'required',
//                'CA_EMAIL' => 'required',
            'CA_ADDR' => 'required',
            'CA_NATCD' => 'required',
//                'CA_STATECD' => 'required_if:CA_NATCD,1',
//                'CA_DISTCD' => 'required_if:CA_NATCD,1',
//                'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
            'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
            'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
            'CA_ONLINECMPL_AMOUNT' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_ACCNO' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on',
                ], [
            'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
            'CA_NAME.required' => 'Ruangan Nama diperlukan.',
            'CA_AGE.required' => 'Ruangan Umur diperlukan.',
            'CA_EMAIL.required' => 'Ruangan Email diperlukan.',
            'CA_ADDR.required' => 'Ruangan Alamat diperlukan.',
            'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
            'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
            'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
            'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
            'CA_AGAINST_PREMISE.required_unless' => 'Ruangan Jenis Premis diperlukan.',
            'CA_AGAINSTNM.required' => 'Ruangan Nama(Syarikat/Premis) diperlukan.',
            'CA_AGAINSTADD.required_if' => 'Ruangan Alamat diperlukan.',
            'CA_AGAINST_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
            'CA_AGAINST_DISTCD.required_if' => 'Ruangan Negeri diperlukan.',
            'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
            'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan',
            'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan',
            'CA_ONLINECMPL_AMOUNT.required_if' => 'Ruangan Jumlah Kerugian diperlukan',
            'CA_ONLINECMPL_ACCNO.required_if' => 'Ruangan No. Akaun diperlukan',
            'CA_ONLINECMPL_CASENO.required_if' => 'Ruangan No. Aduan Rujukan diperlukan',
        ]);
//          ====== begin check myidentiy ==============
//        if ($request->CA_NATCD == 1) {
//            $AgencyCode = "110012";
//            $BranchCode = "eAduan";
//            $TransactionCode = "T2"; // T2 - Admin Page, T7 - Public Page
//            $RequestDateTime = date('Y-m-d H:i:s'); //date("Y-m-d") . "T" . date("H:i:s");
//            $RequestIndicator = "A";
//            $UserId = $request->CA_DOCNO;
//            $ICNumber = $request->CA_DOCNO;
//            $Nama_Pengadu = $request->CA_NAME;
//
//            try {
//                $client = new SoapClient("http://10.23.150.194/tojpn/tomyidentiti/crswsdev.wsdl");
//
//                $response = $client->retrieveCitizensData(array(
//                    "AgencyCode" => $AgencyCode,
//                    "BranchCode" => $BranchCode,
//                    "UserId" => $UserId,
//                    "TransactionCode" => $TransactionCode,
//                    "RequestDateTime" => $RequestDateTime,
//                    "ICNumber" => $ICNumber,
//                    "RequestIndicator" => $RequestIndicator,
//                ));
//                if ($response->ReplyIndicator != '') {
//                    if ($response->ReplyIndicator == '1' || $response->ReplyIndicator == '2') {
//                        $arrData = $response;
//                        $Name = $response->Name;
//                        $matchname = (strtoupper(implode(explode(' ', $Name))) == strtoupper(implode(explode(' ', $Nama_Pengadu)))) ? true : false;
//                        // Umur
//                        if ($matchname) {
////                            $UserAge = Carbon::createFromDate(date('Y', strtotime($response->DateOfBirth)), 5, 21)->age;
//                            $arrData->Gdr = ($response->Gender == '0') ? '0' : ($response->Gender == 'L' ? 'M' : 'F'); // Jantina
//                            // STATUS PENGADU
//                            if ($response->ResidentialStatus == 'B' ||
//                                    $response->ResidentialStatus == 'C' ||
//                                    $response->ResidentialStatus == 'M' ||
//                                    $response->ResidentialStatus == 'P' ||
//                                    $response->ResidentialStatus == '') {
//                                $arrData->Warganegara = 'malaysia';
//                                if ($response->RecordStatus == '2' ||
//                                        $response->RecordStatus == 'B' ||
//                                        $response->RecordStatus == 'H') { // Sudah Meninggal
//                                    $arrData->error = "Individu direkodkan telah meninggal dunia";
//                                    $StatusPengadu = '6'; // Individu direkodkan telah meninggal dunia
//                                } else {
//                                    // Dapatkan rekod jpn letak dlm array
//                                    $arrData->error = "";
//                                    if ($response->ResidentialStatus == 'B' ||
//                                            $response->ResidentialStatus == 'C') {
//                                        $StatusPengadu = '1';  // Warganegara
//                                    } else {
//                                        $StatusPengadu = '2'; // Pemastautin Tetap
//                                    }
//                                }
//                            }
//                            $UserAddress = "$response->CorrespondenceAddress1 $response->CorrespondenceAddress2";
//                            $UserDist = KodMapping::MapDistrict($response->CorrespondenceAddressCityCode);
//                            $UserPostcode = "$response->CorrespondenceAddressPostcode";
//                            $UserState = "$response->CorrespondenceAddressStateCode";
//                            $error = "";
//                        } else {
//                            $arrData = $response;
//                            $StatusPengadu = '4'; // Nama tidak sepadan dengan Kad Pengenalan
//                        }
//                    } else {
//                        $arrData = $response;
//                        $StatusPengadu = '5'; // No. Kad Pengenalan Tidak Sah
//                    }
//                } else {
//                    $error = "Masalah teknikal";
//                }
//            } catch (SoapFault $fault) {
//                $arrData = $fault;
//                $StatusPengadu = '';
//                $error = "Masalah teknikal";
//                return json_encode($arrData);
//            }
//        } else {
//            $StatusPengadu = '3';
//        }
        // ======= end checking myidentity ==============
        $mCallCenterCase = new CallCenterCase();
        $mCallCenterCase->fill($request->all());
        if ($StatusPengadu != "") {

            $mCallCenterCase->CA_MYIDENTITY_ADDR = $UserAddress;
            $mCallCenterCase->CA_MYIDENTITY_DISTCD = "$UserDist";
            $mCallCenterCase->CA_MYIDENTITY_POSCD = $UserPostcode;
            $mCallCenterCase->CA_MYIDENTITY_STATECD = $UserState;
            $mCallCenterCase->CA_STATUSPENGADU = $StatusPengadu;
//            $mCallCenterCase->CA_NATCD = $UserNATCD;
            $mCallCenterCase->CA_CASEID = CallCenterCase::getNoAduan();
            $mCallCenterCase->CA_INVSTS = '1';
            $mCallCenterCase->CA_CASESTS = '1';
            $mCallCenterCase->CA_RCVTYP = 'S28'; //Call Center
            $mCallCenterCase->CA_RCVBY = Auth::user()->id;
            $mCallCenterCase->CA_RCVDT = Carbon::now();
            if ($mCallCenterCase->save()) {
                $mCallCenterCaseDetail = new CallCenterCaseDetail();
                $mCallCenterCaseDetail->CD_CASEID = $mCallCenterCase->CA_CASEID;
                $mCallCenterCaseDetail->CD_TYPE = '';
                $mCallCenterCaseDetail->CD_DESC = '';
                $mCallCenterCaseDetail->CD_ACTTYPE = '';
                $mCallCenterCaseDetail->CD_INVSTS = $mCallCenterCase->CA_INVSTS;
                $mCallCenterCaseDetail->CD_CASESTS = $mCallCenterCase->CA_CASESTS;
                $mCallCenterCaseDetail->CD_CURSTS = 1;
                $mCallCenterCaseDetail->CD_ACTFROM = '';
                $mCallCenterCaseDetail->CD_ACTTO = '';
                if ($mCallCenterCaseDetail->save()) {
                    return redirect()->route('call-center-case.edit', $mCallCenterCase->CA_CASEID)->with('success', 'Aduan Call Center telah BERJAYA ditambah');
                }
            }
        } else {
            $request->session()->flash('warning', $error);
            return view('aduan.call-center-case.create');
        }
    }
    
    public function store(Request $request)
    {
        $StatusPengadu = '';
        $UserAddress = '';
        $UserDist = '';
        $UserPostcode = '';
        $UserState = '';
        $error = "";
        
        $this->validate($request, [
            'CA_DOCNO' => 'required',
            'CA_NAME' => 'required',
            'CA_CMPLCAT' => 'required',
            'CA_CMPLCD' => 'required',
            'CA_NATCD' => 'required',
            'CA_STATECD' => 'required_if:CA_NATCD,1',
            'CA_DISTCD' => 'required_if:CA_NATCD,1',
            'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
            'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
            'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
//            'CA_ONLINECMPL_AMOUNT' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
            'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on',
            'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
            'CA_SUMMARY' => 'required',
            'CA_AGAINSTNM' => 'required',
            'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on|required_unless:CA_CMPLCAT,BPGK 19',
            'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
//            'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on',
            'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
            'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
            'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
//            'CA_AGAINST_POSTCD' => 'required'
        ],
        [
            'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
            'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
            'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            'CA_NAME.required' => 'Ruangan Nama diperlukan.',
            'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
            'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
            'CA_AGAINST_PREMISE.required_unless' => 'Ruangan Jenis Premis diperlukan.',
            'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat / Premis) diperlukan.',
            'CA_AGAINST_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
            'CA_AGAINST_DISTCD.required_if' => 'Ruangan Daerah diperlukan.',
            'CA_AGAINSTADD.required_unless' => 'Ruangan Alamat diperlukan.',
            'CA_AGAINSTADD.required_if' => 'Ruangan Alamat diperlukan.',
//            'CA_AGAINST_POSTCD.required_if' => 'Ruangan Poskod diperlukan.',
//            'CA_AGAINST_POSTCD.required' => 'Ruangan Poskod diperlukan.',
            'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
            'CA_NATCD.required' => 'Ruangan Warganegara diperlukan.',
            'CA_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
            'CA_DISTCD.required_if' => 'Ruangan Daerah diperlukan.',
            'CA_COUNTRYCD.required_if' => 'Ruangan Negara diperlukan.',
            'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
            'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan.',
            'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL diperlukan.',
            'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian diperlukan.',
            'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan.',
            'CA_ONLINECMPL_BANKCD.required_unless' => 'Ruangan Nama Bank diperlukan.',
            'CA_ONLINECMPL_ACCNO.required_unless' => 'Ruangan No. Akaun diperlukan.',
            'CA_ONLINECMPL_CASENO.required_if' => 'Ruangan No. Aduan Rujukan diperlukan.',
        ]);
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
//                'CA_AGAINST_POSTCD' => 'min:5|max:5'
            ]);
        }
//        ====== begin check myidentiy ==============
//        if ($request->CA_NATCD == 1) {
//            $AgencyCode = "110012";
//            $BranchCode = "eAduan";
//            $TransactionCode = "T2"; // T2 - Admin Page, T7 - Public Page
//            $RequestDateTime = date('Y-m-d H:i:s'); //date("Y-m-d") . "T" . date("H:i:s");
//            $RequestIndicator = "A";
//            $UserId = $request->CA_DOCNO;
//            $ICNumber = $request->CA_DOCNO;
//            $Nama_Pengadu = $request->CA_NAME;
//
//            try {
//                $client = new SoapClient("http://10.23.150.194/tojpn/tomyidentiti/crswsdev.wsdl");
//
//                $response = $client->retrieveCitizensData(array(
//                    "AgencyCode" => $AgencyCode,
//                    "BranchCode" => $BranchCode,
//                    "UserId" => $UserId,
//                    "TransactionCode" => $TransactionCode,
//                    "RequestDateTime" => $RequestDateTime,
//                    "ICNumber" => $ICNumber,
//                    "RequestIndicator" => $RequestIndicator,
//                ));
//                if ($response->ReplyIndicator != '') {
//                    if ($response->ReplyIndicator == '1' || $response->ReplyIndicator == '2') {
//                        $arrData = $response;
//                        $Name = $response->Name;
//                        $matchname = (strtoupper(implode(explode(' ', $Name))) == strtoupper(implode(explode(' ', $Nama_Pengadu)))) ? true : false;
//                        // Umur
//                        if ($matchname) {
////                            $UserAge = Carbon::createFromDate(date('Y', strtotime($response->DateOfBirth)), 5, 21)->age;
//                            $arrData->Gdr = ($response->Gender == '0') ? '0' : ($response->Gender == 'L' ? 'M' : 'F'); // Jantina
//                            // STATUS PENGADU
//                            if ($response->ResidentialStatus == 'B' ||
//                                    $response->ResidentialStatus == 'C' ||
//                                    $response->ResidentialStatus == 'M' ||
//                                    $response->ResidentialStatus == 'P' ||
//                                    $response->ResidentialStatus == '') {
//                                $arrData->Warganegara = 'malaysia';
//                                if ($response->RecordStatus == '2' ||
//                                        $response->RecordStatus == 'B' ||
//                                        $response->RecordStatus == 'H') { // Sudah Meninggal
//                                    $arrData->error = "Individu direkodkan telah meninggal dunia";
//                                    $StatusPengadu = '6'; // Individu direkodkan telah meninggal dunia
//                                } else {
//                                    // Dapatkan rekod jpn letak dlm array
//                                    $arrData->error = "";
//                                    if ($response->ResidentialStatus == 'B' ||
//                                            $response->ResidentialStatus == 'C') {
//                                        $StatusPengadu = '1';  // Warganegara
//                                    } else {
//                                        $StatusPengadu = '2'; // Pemastautin Tetap
//                                    }
//                                }
//                            }
//                            $UserAddress = "$response->CorrespondenceAddress1 $response->CorrespondenceAddress2";
//                            $UserDist = KodMapping::MapDistrict($response->CorrespondenceAddressCityCode);
//                            $UserPostcode = "$response->CorrespondenceAddressPostcode";
//                            $UserState = "$response->CorrespondenceAddressStateCode";
//                            $error = "";
//                        } else {
//                            $arrData = $response;
//                            $StatusPengadu = '4'; // Nama tidak sepadan dengan Kad Pengenalan
//                        }
//                    } else {
//                        $arrData = $response;
//                        $StatusPengadu = '5'; // No. Kad Pengenalan Tidak Sah
//                    }
//                } else {
//                    $error = "Masalah teknikal";
//                }
//            } catch (SoapFault $fault) {
//                $arrData = $fault;
//                $StatusPengadu = '';
//                $error = "Masalah teknikal";
//                return json_encode($arrData);
//            }
//        } else {
//            $StatusPengadu = '3';
//        }
//        ====== end check myidentiy ==============
        $mCallCenterCase = new CallCenterCase;
        $mCallCenterCase->fill($request->all());
        
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $mCallCenterCase->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
        }else{
            $mCallCenterCase->CA_CMPLKEYWORD = NULL;
        }
        
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($request->CA_ONLINECMPL_IND) {
                $mCallCenterCase->CA_ONLINECMPL_IND = '1';
                $mCallCenterCase->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            }else{
                $mCallCenterCase->CA_ONLINECMPL_IND = '0';
                $mCallCenterCase->CA_ONLINECMPL_CASENO = NULL;
            }
            
            if($request->CA_ONLINEADD_IND) {
                $mCallCenterCase->CA_ONLINEADD_IND = '1';
            }else{
                $mCallCenterCase->CA_ONLINEADD_IND = '0';
            }
            
            $mCallCenterCase->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            $mCallCenterCase->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
            $mCallCenterCase->CA_ONLINECMPL_PROVIDER = $request->CA_ONLINECMPL_PROVIDER;
            $mCallCenterCase->CA_ONLINECMPL_ACCNO = $request->CA_ONLINECMPL_ACCNO;
            $mCallCenterCase->CA_ONLINECMPL_BANKCD = $request->CA_ONLINECMPL_BANKCD;
        }else{
            $mCallCenterCase->CA_ONLINECMPL_URL = '';
        }
//        $mCallCenterCase->CA_MYIDENTITY_ADDR = $UserAddress;
//        $mCallCenterCase->CA_MYIDENTITY_DISTCD = "$UserDist";
//        $mCallCenterCase->CA_MYIDENTITY_POSCD = $UserPostcode;
//        $mCallCenterCase->CA_MYIDENTITY_STATECD = $UserState;
        $mCallCenterCase->CA_INVSTS = '10'; // DERAF
        $mCallCenterCase->CA_CASESTS = 1;
        $mCallCenterCase->CA_RCVTYP = 'S28';
        $mCallCenterCase->CA_RCVBY = request('CA_RCVBY');
        $mCallCenterCase->CA_RCVDT = Carbon::now();
        $mCallCenterCase->CA_DOCNO = request('CA_DOCNO');
        $mCallCenterCase->CA_NAME = request('CA_NAME');
        $mCallCenterCase->CA_EMAIL = request('CA_EMAIL');
        $mCallCenterCase->CA_MOBILENO = request('CA_MOBILENO');
        $mCallCenterCase->CA_TELNO = request('CA_TELNO');
        $mCallCenterCase->CA_FAXNO = request('CA_FAXNO');
        $mCallCenterCase->CA_SEXCD = request('CA_SEXCD');
        $mCallCenterCase->CA_AGE = request('CA_AGE');
        $mCallCenterCase->CA_ADDR = request('CA_ADDR');
//        $mCallCenterCase->CA_RACECD = request('CA_RACECD');
        $mCallCenterCase->CA_STATUSPENGADU = $StatusPengadu;
        $mCallCenterCase->CA_POSCD = request('CA_POSCD');
        $mCallCenterCase->CA_STATECD = request('CA_STATECD');
        $mCallCenterCase->CA_DISTCD = request('CA_DISTCD');
        $mCallCenterCase->CA_NATCD = request('CA_NATCD');
        $mCallCenterCase->CA_COUNTRYCD = request('CA_COUNTRYCD');
        $mCallCenterCase->CA_CMPLCAT = request('CA_CMPLCAT');
        $mCallCenterCase->CA_CMPLCD = request('CA_CMPLCD');
        $mCallCenterCase->CA_AGAINST_PREMISE = request('CA_AGAINST_PREMISE');
        $mCallCenterCase->CA_AGAINSTNM = request('CA_AGAINSTNM');
        $mCallCenterCase->CA_AGAINST_TELNO = request('CA_AGAINST_TELNO');
        $mCallCenterCase->CA_AGAINST_MOBILENO = request('CA_AGAINST_MOBILENO');
        $mCallCenterCase->CA_AGAINST_EMAIL = request('CA_AGAINST_EMAIL');
        $mCallCenterCase->CA_AGAINST_FAXNO = request('CA_AGAINST_FAXNO');
        $mCallCenterCase->CA_AGAINSTADD = request('CA_AGAINSTADD');
        $mCallCenterCase->CA_AGAINST_POSTCD = request('CA_AGAINST_POSTCD');
        $mCallCenterCase->CA_AGAINST_STATECD = request('CA_AGAINST_STATECD');
        $mCallCenterCase->CA_AGAINST_DISTCD = request('CA_AGAINST_DISTCD');
        $mCallCenterCase->CA_SUMMARY = request('CA_SUMMARY');
        $DeptCd = explode(' ', $mCallCenterCase->CA_CMPLCAT)[0];
        $mCallCenterCase->CA_DEPTCD = $DeptCd;
        
        if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19')
        {
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        }else{
            $StateCd = $mCallCenterCase->CA_STATECD;
            $DistCd = $mCallCenterCase->CA_DISTCD;
            $mCallCenterCase->CA_AGAINSTADD = NULL;
            $mCallCenterCase->CA_AGAINST_POSTCD = NULL;
            $mCallCenterCase->CA_AGAINST_STATECD = NULL;
            $mCallCenterCase->CA_AGAINST_DISTCD = NULL;
        }
        if($request->CA_ROUTETOHQIND && $request->CA_ROUTETOHQIND == 'on') {
            $mCallCenterCase->CA_ROUTETOHQIND = '1';
//            $mCallCenterCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, true);
            $mCallCenterCase->CA_BRNCD = 'WHQR5';
        }else{
            $mCallCenterCase->CA_ROUTETOHQIND = '0';
            // $mCallCenterCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, false);
            $mCallCenterCase->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
        }
        
        if ($mCallCenterCase->save()) {
            $mCallCenterDetail = new CallCenterCaseDetail;
            $mCallCenterDetail->CD_CASEID = $mCallCenterCase->id;
            $mCallCenterDetail->CD_TYPE = 'D';
            $mCallCenterDetail->CD_ACTTYPE = 'CLS';
            $mCallCenterDetail->CD_INVSTS = $mCallCenterCase->CA_INVSTS;
            $mCallCenterDetail->CD_CASESTS = $mCallCenterCase->CA_CASESTS;
            $mCallCenterDetail->CD_CURSTS = '1';
//            dd($mCallCenterDetail);
            if($mCallCenterDetail->save()) {
                return redirect()->route('call-center-case.attachment', $mCallCenterCase->id);
            }
        }
    }
    
    public function AduanRoute($StateCd, $DistCd, $DeptCd, $RouteToHQ) {
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
            return $FindBrn->BR_BRNCD;
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
    
    public function update(Request $request, $caseid)
    {
        $StatusPengadu = '';
        $UserAddress = '';
        $UserDist = '';
        $UserPostcode = '';
        $UserState = '';
        $error = "";
        if($request->CA_CMPLCAT != 'BPGK 19'){
            $request->merge([
                'CA_ONLINECMPL_PROVIDER' => NULL,
                'CA_ONLINECMPL_URL' => NULL,
//                'CA_ONLINECMPL_AMOUNT' => NULL,
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
//                'CA_AGAINST_POSTCD' => 'min:5|max:5',
                'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
//                'CA_ONLINECMPL_BANKCD' => 'required',
                'CA_DOCNO' => 'required',
                'CA_NAME' => 'required',
                'CA_ADDR' => 'required',
//                'CA_STATECD' => 'required_if:CA_NATCD,1',
//                'CA_DISTCD' => 'required_if:CA_NATCD,1',
                'CA_STATECD' => 'required',
                'CA_DISTCD' => 'required',
                'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
//                'CA_POSCD' => 'required|min:5|max:5',
//                'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
                'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
                'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
            ],
            [
                'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                'CA_AGAINST_POSTCD.min' => 'Poskod Tidak Sah',
//                'CA_AGAINST_POSTCD.max' => 'Poskod Tidak Sah',
                'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan',
                'CA_NAME.required' => 'Ruangan Nama diperlukan',
                'CA_ADDR.required' => 'Ruangan Alamat diperlukan',
                'CA_STATECD.required' => 'Ruangan Negeri diperlukan',
                'CA_DISTCD.required' => 'Ruangan Daerah diperlukan',
                'CA_COUNTRYCD.required_if' => 'Ruangan Negeri diperlukan',
//                'CA_POSCD.required' => 'Ruangan Poskod diperlukan',
//                'CA_POSCD.min' => 'Jumlah Poskod mesti sekurang-kurangnya 5 aksara.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
                'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan.',
                'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat / Premis) diperlukan.',
                'CA_AGAINSTADD.required_unless' => 'Ruangan Alamat diperlukan.',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian diperlukan.',
//                'CA_ONLINECMPL_BANKCD.required' => 'Ruangan Nama Bank diperlukan.',
            ]);
        } else {
            $this->validate($request, [
//                'CA_RCVTYP' => 'required',
                'CA_DOCNO' => 'required',
//                'CA_EMAIL' => 'required|email',
//                'CA_MOBILENO' => 'required',
//                'CA_NAME' => 'required',
                'CA_STATECD' => 'required_if:CA_NATCD,1',
                'CA_DISTCD' => 'required_if:CA_NATCD,1',
                'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
                'CA_CMPLCAT' => 'required',
                'CA_CMPLCD' => 'required',
                'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
                'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on|required_if:CA_ONLINECMPL_IND,1',
                'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
                'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19',
                'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19',
                'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
                'CA_SUMMARY' => 'required',
                'CA_AGAINSTNM' => 'required',
                'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
//                'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on',
//                'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on',
                'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
                'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
                'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
            ],
            [
                'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
                'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
//                'CA_EMAIL.required' => 'Ruangan Emel diperlukan.',
                'CA_EMAIL.email' => 'Ruangan Emel tidak sah.',
//                'CA_MOBILENO.required' => 'Ruangan No. Telefon Bimbit diperlukan.',
                'CA_NAME.required' => 'Ruangan Nama diperlukan.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat / Premis) diperlukan.',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required_if' => 'Ruangan Daerah diperlukan.',
                'CA_AGAINSTADD.required_if' => 'Ruangan Alamat diperlukan.',
//                'CA_AGAINST_POSTCD.required_if' => 'Ruangan Poskod diperlukan.',
                'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
                'CA_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
                'CA_DISTCD.required_if' => 'Ruangan Daerah diperlukan.',
                'CA_COUNTRYCD.required_if' => 'Ruangan Negara diperlukan.',
                'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian diperlukan.',
                'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan.',
                'CA_ONLINECMPL_BANKCD.required_unless' => 'Ruangan Nama Bank diperlukan.',
                'CA_ONLINECMPL_ACCNO.required_unless' => 'Ruangan No. Akaun  diperlukan.',
                'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan.',
                'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL diperlukan.',
                'CA_ONLINECMPL_CASENO.required_if' => 'Ruangan No. Aduan Rujukan diperlukan.',
            ]);
        }
//        ====== begin check myidentiy ==============
//        if ($request->CA_NATCD == 1) {
//            $AgencyCode = "110012";
//            $BranchCode = "eAduan";
//            $TransactionCode = "T2"; // T2 - Admin Page, T7 - Public Page
//            $RequestDateTime = date('Y-m-d H:i:s'); //date("Y-m-d") . "T" . date("H:i:s");
//            $RequestIndicator = "A";
//            $UserId = $request->CA_DOCNO;
//            $ICNumber = $request->CA_DOCNO;
//            $Nama_Pengadu = $request->CA_NAME;
//
//            try {
//                $client = new SoapClient("http://10.23.150.194/tojpn/tomyidentiti/crswsdev.wsdl");
//
//                $response = $client->retrieveCitizensData(array(
//                    "AgencyCode" => $AgencyCode,
//                    "BranchCode" => $BranchCode,
//                    "UserId" => $UserId,
//                    "TransactionCode" => $TransactionCode,
//                    "RequestDateTime" => $RequestDateTime,
//                    "ICNumber" => $ICNumber,
//                    "RequestIndicator" => $RequestIndicator,
//                ));
//                if ($response->ReplyIndicator != '') {
//                    if ($response->ReplyIndicator == '1' || $response->ReplyIndicator == '2') {
//                        $arrData = $response;
//                        $Name = $response->Name;
//                        $matchname = (strtoupper(implode(explode(' ', $Name))) == strtoupper(implode(explode(' ', $Nama_Pengadu)))) ? true : false;
//                        // Umur
//                        if ($matchname) {
////                            $UserAge = Carbon::createFromDate(date('Y', strtotime($response->DateOfBirth)), 5, 21)->age;
//                            $arrData->Gdr = ($response->Gender == '0') ? '0' : ($response->Gender == 'L' ? 'M' : 'F'); // Jantina
//                            // STATUS PENGADU
//                            if ($response->ResidentialStatus == 'B' ||
//                                    $response->ResidentialStatus == 'C' ||
//                                    $response->ResidentialStatus == 'M' ||
//                                    $response->ResidentialStatus == 'P' ||
//                                    $response->ResidentialStatus == '') {
//                                $arrData->Warganegara = 'malaysia';
//                                if ($response->RecordStatus == '2' ||
//                                        $response->RecordStatus == 'B' ||
//                                        $response->RecordStatus == 'H') { // Sudah Meninggal
//                                    $arrData->error = "Individu direkodkan telah meninggal dunia";
//                                    $StatusPengadu = '6'; // Individu direkodkan telah meninggal dunia
//                                } else {
//                                    // Dapatkan rekod jpn letak dlm array
//                                    $arrData->error = "";
//                                    if ($response->ResidentialStatus == 'B' ||
//                                            $response->ResidentialStatus == 'C') {
//                                        $StatusPengadu = '1';  // Warganegara
//                                    } else {
//                                        $StatusPengadu = '2'; // Pemastautin Tetap
//                                    }
//                                }
//                            }
//                            $UserAddress = "$response->CorrespondenceAddress1 $response->CorrespondenceAddress2";
//                            $UserDist = KodMapping::MapDistrict($response->CorrespondenceAddressCityCode);
//                            $UserPostcode = "$response->CorrespondenceAddressPostcode";
//                            $UserState = "$response->CorrespondenceAddressStateCode";
//                            $error = "";
//                        } else {
//                            $arrData = $response;
//                            $StatusPengadu = '4'; // Nama tidak sepadan dengan Kad Pengenalan
//                        }
//                    } else {
//                        $arrData = $response;
//                        $StatusPengadu = '5'; // No. Kad Pengenalan Tidak Sah
//                    }
//                } else {
//                    $error = "Masalah teknikal";
//                }
//            } catch (SoapFault $fault) {
//                $arrData = $fault;
//                $StatusPengadu = '';
//                $error = "Masalah teknikal";
//                return json_encode($arrData);
//            }
//        } else {
//            $StatusPengadu = '3';
//        }
//        ====== end check myidentiy ==============
        
        $mCallCenterCase = CallCenterCase::find($caseid);
//        $mAdminCase = AdminCase::where(['CA_CASEID' => $caseid])->first();
        $mCallCenterCase->fill($request->all());
        $mCallCenterCase->CA_RCVTYP = request('CA_RCVTYP');
        $mCallCenterCase->CA_RCVTYP = 'S28';
        $mCallCenterCase->CA_RCVBY = request('CA_RCVBY');
        $mCallCenterCase->CA_DOCNO = request('CA_DOCNO');
        $mCallCenterCase->CA_NAME = request('CA_NAME');
        $mCallCenterCase->CA_EMAIL = request('CA_EMAIL');
        $mCallCenterCase->CA_MOBILENO = request('CA_MOBILENO');
        $mCallCenterCase->CA_TELNO = request('CA_TELNO');
        $mCallCenterCase->CA_FAXNO = request('CA_FAXNO');
        $mCallCenterCase->CA_SEXCD = request('CA_SEXCD');
        $mCallCenterCase->CA_AGE = request('CA_AGE');
        $mCallCenterCase->CA_ADDR = request('CA_ADDR');
        $mCallCenterCase->CA_RACECD = request('CA_RACECD');
        $mCallCenterCase->CA_POSCD = request('CA_POSCD');
        $mCallCenterCase->CA_STATECD = request('CA_STATECD');
        $mCallCenterCase->CA_DISTCD = request('CA_DISTCD');
        $mCallCenterCase->CA_NATCD = request('CA_NATCD');
        $mCallCenterCase->CA_COUNTRYCD = request('CA_COUNTRYCD');
        $mCallCenterCase->CA_CMPLCAT = request('CA_CMPLCAT');
        $mCallCenterCase->CA_CMPLCD = request('CA_CMPLCD');
        $mCallCenterCase->CA_AGAINST_PREMISE = request('CA_AGAINST_PREMISE');
        $mCallCenterCase->CA_AGAINSTNM = request('CA_AGAINSTNM');
        $mCallCenterCase->CA_AGAINST_TELNO = request('CA_AGAINST_TELNO');
        $mCallCenterCase->CA_AGAINST_MOBILENO = request('CA_AGAINST_MOBILENO');
        $mCallCenterCase->CA_AGAINST_EMAIL = request('CA_AGAINST_EMAIL');
        $mCallCenterCase->CA_AGAINST_FAXNO = request('CA_AGAINST_FAXNO');
        $mCallCenterCase->CA_AGAINSTADD = request('CA_AGAINSTADD');
        $mCallCenterCase->CA_AGAINST_POSTCD = request('CA_AGAINST_POSTCD');
        $mCallCenterCase->CA_AGAINST_STATECD = request('CA_AGAINST_STATECD');
        $mCallCenterCase->CA_AGAINST_DISTCD = request('CA_AGAINST_DISTCD');
        $mCallCenterCase->CA_SUMMARY = request('CA_SUMMARY');
        $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        $mCallCenterCase->CA_DEPTCD = $DeptCd;
        if ($mCallCenterCase->CA_NATCD == '1') {
            $mCallCenterCase->CA_COUNTRYCD = '';
        } 
//        else if ($mCallCenterCase->CA_NATCD == '0') {
//            $mCallCenterCase->CA_POSCD = '';
//            $mCallCenterCase->CA_STATECD = '';
//            $mCallCenterCase->CA_DISTCD = '';
//        }
        if(in_array($request->CA_CMPLCAT,['BPGK 01','BPGK 03'])) {
            $mCallCenterCase->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
            $mCallCenterCase->CA_ONLINECMPL_IND = NULL;
            $mCallCenterCase->CA_ONLINECMPL_CASENO = NULL;
            $mCallCenterCase->CA_ONLINECMPL_URL = NULL;
        }else{
            $mCallCenterCase->CA_CMPLKEYWORD = NULL;
        }
        if($request->CA_CMPLCAT == 'BPGK 19') {
            if($mCallCenterCase->CA_ONLINECMPL_IND) {
                $mCallCenterCase->CA_ONLINECMPL_IND = '1';
                $mCallCenterCase->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            }else{
                $mCallCenterCase->CA_ONLINECMPL_IND = '0';
                $mCallCenterCase->CA_ONLINECMPL_CASENO = NULL;
            }
            if($request->CA_ONLINEADD_IND) {
                $mCallCenterCase->CA_ONLINEADD_IND = '1';
            }else{
                $mCallCenterCase->CA_ONLINEADD_IND = '0';
                $mCallCenterCase->CA_AGAINSTADD = NULL;
                $mCallCenterCase->CA_AGAINST_STATECD = NULL;
                $mCallCenterCase->CA_AGAINST_DISTCD = NULL;
                $mCallCenterCase->CA_AGAINST_POSTCD = NULL;
            }
            $mCallCenterCase->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            $mCallCenterCase->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
            $mCallCenterCase->CA_ONLINECMPL_PROVIDER = $request->CA_ONLINECMPL_PROVIDER;
            $mCallCenterCase->CA_ONLINECMPL_ACCNO = $request->CA_ONLINECMPL_ACCNO;
            $mCallCenterCase->CA_ONLINECMPL_BANKCD = $request->CA_ONLINECMPL_BANKCD;
            $mCallCenterCase->CA_AGAINST_PREMISE = NULL;
        }else{
            $mCallCenterCase->CA_ONLINECMPL_URL = NULL;
        }
        if($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19'){
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        }else{
            $StateCd = $mCallCenterCase->CA_STATECD;
            $DistCd = $mCallCenterCase->CA_DISTCD;
            $mCallCenterCase->CA_AGAINSTADD = NULL;
            $mCallCenterCase->CA_AGAINST_POSTCD = NULL;
            $mCallCenterCase->CA_AGAINST_STATECD = NULL;
            $mCallCenterCase->CA_AGAINST_DISTCD = NULL;
        }
        // if($request->CA_ONLINEADD_IND != 'on'){
        //     $StateCd = $request->CA_STATECD;
        //     $DistCd = $request->CA_DISTCD;
        // }
        if($request->CA_ROUTETOHQIND && $request->CA_ROUTETOHQIND == 'on') {
            $mCallCenterCase->CA_ROUTETOHQIND = '1';
//            $mCallCenterCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, true);
            $mCallCenterCase->CA_BRNCD = 'WHQR5';
        }else{
            $mCallCenterCase->CA_ROUTETOHQIND = '0';
            // $mCallCenterCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, false);
            $mCallCenterCase->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
        }
        if ($mCallCenterCase->save()) {
            $request->session()->flash('success', 'Aduan telah berjaya dikemaskini');
//            return redirect()->back();
            return redirect()->route('call-center-case.attachment', $mCallCenterCase->id);
        }
    }
    
    public function attachment($id) {
        $model = CallCenterCase::find($id);
        $countDoc = DB::table('case_doc')
            ->where('CC_CASEID', $id)
            ->count('CC_CASEID');
        $mCallCenterCaseDoc = CallCenterCaseDoc::where(['CC_CASEID' => $id])->first();
        return view('aduan.call-center-case.attachment', compact('model','countDoc','mCallCenterCaseDoc'));
    }
    
    public function preview($id) {
        $model = CallCenterCase::find($id);
        $mCallCenterCase = CallCenterCaseDoc::where(['CC_CASEID' => $id])->get();
        $mUser = User::find($model->CA_RCVBY);
        if($mUser) {
            $RcvBy = $mUser->name;
        } else {
            $RcvBy = '';
        }
        return view('aduan.call-center-case.preview', compact('model','mCallCenterCase', 'RcvBy'));
    }
    
    public function submit(Request $Request, $id)
    {
        $mCallCenterCase = CallCenterCase::find($id);
        $mCallCenterCaseOldInvsts = $mCallCenterCase->CA_INVSTS;
        if($mCallCenterCase->CA_CASEID == NULL){
            $mCallCenterCase->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), '00');
//            $mCallCenterCase->CA_CASEID = CallCenterCase::getNoAduan();
        }
        if ($mCallCenterCaseOldInvsts == '10'){
            $mCallCenterCase->CA_RCVDT = Carbon::now();
        }
        $mCallCenterCase->CA_INVSTS = '1'; //Aduan Diterima
        if($mCallCenterCase->save()) {
            CallCenterCaseDoc::where('CC_CASEID', $id)->update(['CC_CASEID' => $mCallCenterCase->CA_CASEID]);
            CallCenterCaseDetail::where(['CD_CASEID' => $id, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
            CallCenterCaseDetail::where('CD_CASEID', $id)->update(['CD_CASEID' => $mCallCenterCase->CA_CASEID]);
            $date = date('YmdHis');
            $userid = Auth::user()->id;
            $mSuratPublic = Letter::where(['letter_type' => '01','letter_code' => $mCallCenterCase->CA_INVSTS])->first();
            $ContentSuratPublic = $mSuratPublic->header . $mSuratPublic->body . $mSuratPublic->footer;

            if($mCallCenterCase->CA_STATECD != ''){
                $StateNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'17','code'=>$mCallCenterCase->CA_STATECD])->first();
                $CA_STATECD = $StateNm->descr;
            } else {
                $CA_STATECD = '';
            }
            if($mCallCenterCase->CA_DISTCD != ''){
                $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat'=>'18','code'=>$mCallCenterCase->CA_DISTCD])->first();
                $CA_DISTCD = $DestrictNm->descr;
            } else {
                $CA_DISTCD = '';
            }
            if ($mCallCenterCaseOldInvsts == '10'){
                $patterns[1] = "#NAMAPENGADU#";
                $patterns[2] = "#ALAMATPENGADU#";
                $patterns[3] = "#POSKODPENGADU#";
                $patterns[4] = "#DAERAHPENGADU#";
                $patterns[5] = "#NEGERIPENGADU#";
                $patterns[6] = "#NOADUAN#";
                $patterns[7] = "#TARIKH#";
                $patterns[8] = "#MASA#";
                $replacements[1] = $mCallCenterCase->CA_NAME;
                $replacements[2] = $mCallCenterCase->CA_ADDR != ''? $mCallCenterCase->CA_ADDR : '';
                $replacements[3] = $mCallCenterCase->CA_POSCD != ''? $mCallCenterCase->CA_POSCD : '';
                $replacements[4] = $CA_DISTCD;
                $replacements[5] = $CA_STATECD;
                $replacements[6] = $mCallCenterCase->CA_CASEID;
                $replacements[7] = date('d/m/Y', strtotime($mCallCenterCase->CA_RCVDT));
                $replacements[8] = date('h:i A', strtotime($mCallCenterCase->CA_RCVDT));

                $ContentReplace = preg_replace($patterns, $replacements, urldecode($ContentSuratPublic));
                $arr_rep = array("#", "#");
                $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
                $pdf = PDF::loadHTML($ContentFinal); // Generate PDF from HTML

                $filename = $userid . '_' . $mCallCenterCase->CA_CASEID . '_' . $date . '.pdf';
                Storage::disk('letter')->put($filename, $pdf->output()); // Store PDF to storage

                $mAttachment = new Attachment();
                $mAttachment->doc_title = $mSuratPublic->title;
                $mAttachment->file_name = $mSuratPublic->title;
                $mAttachment->file_name_sys = $filename;
                if($mAttachment->save()){
                    $mCallCenterCaseDetail = new CallCenterCaseDetail();
                    $mCallCenterCaseDetail->fill([
                        'CD_CASEID' => $mCallCenterCase->CA_CASEID,
                        'CD_TYPE' => 'D',
                        'CD_ACTTYPE' => 'NEW',
                        'CD_INVSTS' => '1',
                        'CD_CASESTS' => '1',
                        'CD_CURSTS' => '1',
                        'CD_DOCATTCHID_PUBLIC' => $mAttachment->id,
                    ]);
                    if($mCallCenterCaseDetail->save()) {
//                        if($Request->user()->email != '')
                        if($mCallCenterCase->CA_EMAIL != '')
                        {
//                            Mail::to($Request->user())->queue(new AduanTerimaPublic($mAdminCase)); // Send pakai queue
                            Mail::to($mCallCenterCase->CA_EMAIL)->send(new AduanTerimaCallCenter($mCallCenterCase)); // Send biasa
                        }
                        $Request->session()->flash('success', 'Aduan anda telah diterima');
                        return redirect()->route('call-center-case.index');
                    }
                }
            }
            else{
                if($mCallCenterCase->save()) {
                    $Request->session()->flash('success', 'Aduan anda telah diterima');
                    return redirect()->route('call-center-case.index');
                }
            }
        }
    }
    
    public function check($CASEID)
    {
        $model = CallCenterCase::where(['CA_CASEID' => $CASEID])->first();
        $mCallCenterCaseDoc = CallCenterCaseDoc::where(['CC_CASEID' => $CASEID])->get();
        $mUser = User::find($model->CA_RCVBY);
        if($mUser) {
            $RcvBy = $mUser->name;
        } else {
            $RcvBy = '';
        }
        return view('aduan.call-center-case.check', compact('model','mCallCenterCaseDoc', 'RcvBy'));
    }

    public function doccreate($id)
    {
        return view('aduan.call-center-case.doccreate', compact('id'));
    }
    
    public function storeDoc(Request $request)
    {
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
//                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
//                            $constraint->aspectRatio();
//                            $constraint->upsize();
//                        });
//                $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
//                $path = "images/{$hash}.jpg"; // use hash as a name
//                $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
//                Storage::disk('bahan')->put($directory.$filename, $resize->__toString());
//                unlink($path);
                $resize = Image::make($file)->resize(null, 600, function ($constraint) { // returns Intervention\Image\Image
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
//                $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
//                $path = "images/{$hash}.jpg"; // use hash as a name
//                $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
                $resize->stream();
                Storage::disk('bahan')->put($directory.$filename, $resize);
            }
            else
            {
                Storage::disk('bahan')->putFileAs('/'.$Year.'/'.$Month.'/', $request->file('file'), $filename);
            }
            $mCallCenterCaseDoc = new CallCenterCaseDoc();
            $mCallCenterCaseDoc->CC_CASEID = $request->CC_CASEID;
            $mCallCenterCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mCallCenterCaseDoc->CC_IMG = $filename;
            $mCallCenterCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mCallCenterCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            $mCallCenterCaseDoc->CC_IMG_CAT = 1;
            if($mCallCenterCaseDoc->save()) {
                $request->session()->flash('success', 'Bahan Bukti telah berjaya ditambah');
                return redirect()->route('call-center-case.attachment',$request->CC_CASEID);
            }
        }
    }
    
    public function docedit($id)
    {
        $mCallCenterCaseDoc = CallCenterCaseDoc::find($id);
        return view('aduan.call-center-case.docedit', compact('mCallCenterCaseDoc'));
    }
    
    public function docupdate(Request $request, $id)
    {
        $mCallCenterCaseDoc = CallCenterCaseDoc::find($id);
        $file = $request->file('file');
        $date = date('YmdHis');
        $userid = Auth()->user()->id;
        $Year = date('Y');
        $Month = date('m');

        if ($file) {
            Storage::delete($mCallCenterCaseDoc->CC_PATH.$mCallCenterCaseDoc->CC_IMG); // Delete old attachment
            $filename = $userid.'_'.$mCallCenterCaseDoc->CC_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension(); // Store new attachment
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
            $mCallCenterCaseDoc->CC_PATH = Storage::disk('bahan')->url($directory);
            $mCallCenterCaseDoc->CC_IMG = $filename;
            $mCallCenterCaseDoc->CC_IMG_NAME = $file->getClientOriginalName();
            $mCallCenterCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            // Save record
            if ($mCallCenterCaseDoc->save()) {
                $request->session()->flash('success', 'Bahan Bukti telah berjaya dikemaskini');
                return redirect()->route('call-center-case.attachment', $mCallCenterCaseDoc->CC_CASEID);
            }
        } else {
            $mCallCenterCaseDoc->CC_REMARKS = $request->CC_REMARKS;
            if ($mCallCenterCaseDoc->save()) {
                $request->session()->flash('success', 'Bahan Bukti telah berjaya dikemaskini');
                return redirect()->route('call-center-case.attachment', $mCallCenterCaseDoc->CC_CASEID);
            }
        }
    }
    
    public function GetDataJpn($DOCNO) {
        $AgencyCode = "110012";
        $BranchCode = "eAduan";
        $TransactionCode = "T2"; // T2 - Admin Page, T7 - Public Page
        $RequestDateTime = date('Y-m-d H:i:s'); //date("Y-m-d") . "T" . date("H:i:s");
        $RequestIndicator = "A";
        $UserId = $DOCNO;
        $ICNumber = $DOCNO;

        try {
            $soapClientOptions = ['stream_context' => 
				stream_context_create([
					'ssl' => [
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					]
				])
			];
			
            $client = new SoapClient("https://eaduan.kpdnkk.gov.my/tojpn/tomyidentiti/crsservice.wsdl", $soapClientOptions);

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
            if ($response->ReplyIndicator == '1' || $response->ReplyIndicator == '2') {
                $arrData = $response;
                // Umur
                $Age = Carbon::createFromDate(date('Y', strtotime($response->DateOfBirth)), 5, 21)->age;
                $arrData->Age = $Age;
                $arrData->Daerah = KodMapping::MapDistrict($response->CorrespondenceAddressCityCode);
                $arrData->Gdr = ($response->Gender == '0') ? '0' : ($response->Gender == 'L' ? 'M' : 'F'); // Jantina
                // Warganegara
                if ($response->ResidentialStatus == 'B' ||
                        $response->ResidentialStatus == 'C' ||
                        $response->ResidentialStatus == 'M' ||
                        $response->ResidentialStatus == 'P') {
                    $arrData->Warganegara = 'malaysia';
                } else {
                    $arrData->Warganegara = 'oth';
                }

                return json_encode($arrData);
            } else {
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

    public function GetDistList($state_cd) {
        $mDistList = DB::table('sys_ref')
            ->where('cat', '18')
            ->where('status', '1')
            ->where('code', 'like', "$state_cd%")
            ->orderBy('sort')
            ->pluck('code', 'descr');
        if(count($mDistList) > 1) {
            $mDistList->prepend('', '-- SILA PILIH --');
        }
        return json_encode($mDistList);
    }

    public function show($CA_CASEID) {
        
    }

    public function edit($id) {
        $mCallCase = CallCenterCase::where('id', $id)->first();
        $mUser = \App\User::find($mCallCase->CA_RCVBY);
        if ($mUser) {
            $RcvBy = $mUser->name;
        } else {
            $RcvBy = '';
        }
        $CountDoc = DB::table('case_doc')
                ->where('CC_CASEID', $id)
                ->count('CC_CASEID');

        return view('aduan/call-center-case.edit', compact('mCallCase', 'RcvBy', 'CountDoc'));
    }

    public function update1(Request $request, $CACASEID) {
        $error = '';
        $StatusPengadu = '';
        $UserAddress = '';
        $UserAge = '';
        $UserGender = '';
        $UserCountry = '';
        $this->validate($request, [
            'CA_DOCNO' => 'required',
            'CA_NAME' => 'required',
            'CA_AGE' => 'required',
//                'CA_EMAIL' => 'required',
            'CA_ADDR' => 'required',
            'CA_STATECD' => 'required',
            'CA_DISTCD' => 'required',
            'CA_CMPLCAT' => 'required',
//                'CA_CMPLCD' => 'required',
            'CA_AGAINST_PREMISE' => 'required',
            'CA_AGAINSTNM' => 'required',
            'CA_AGAINSTADD' => 'required',
            'CA_AGAINST_STATECD' => 'required',
            'CA_AGAINST_DISTCD' => 'required',
            'CA_SUMMARY' => 'required',
                ], [
            'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
            'CA_NAME.required' => 'Ruangan Nama diperlukan.',
            'CA_AGE.required' => 'Ruangan Umur diperlukan.',
//                'CA_EMAIL.required' => 'Ruangan Email diperlukan.',
            'CA_ADDR.required' => 'Ruangan Alamat diperlukan.',
            'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
            'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
            'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
//                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
            'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan.',
            'CA_AGAINSTNM.required' => 'Ruangan Nama(Syarikat/Premis) diperlukan.',
            'CA_AGAINSTADD.required' => 'Ruangan Alamat diperlukan.',
            'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
            'CA_AGAINST_DISTCD.required' => 'Ruangan Negeri diperlukan.',
            'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
        ]);

        $mCallCenterCase = CallCenterCase::find($CACASEID);
        $mCallCenterCase->fill($request->all());

//        if($request->CA_NATCD == 1){
//            $AgencyCode = "110012";
//            $BranchCode = "eAduan";
//            $TransactionCode = "T2"; // T2 - Admin Page, T7 - Public Page
//            $RequestDateTime = date('Y-m-d H:i:s'); //date("Y-m-d") . "T" . date("H:i:s");
//            $RequestIndicator = "A";
//            $UserId = $request->CA_DOCNO;
//            $ICNumber = $request->CA_DOCNO;
//            $Nama_Pengadu = $request->CA_NAME;
//            
//            try {
//                    $client = new SoapClient("http://10.23.150.194/tojpn/tomyidentiti/crswsdev.wsdl");
//
//                    $response = $client->retrieveCitizensData(array(
//                        "AgencyCode" => $AgencyCode,
//                        "BranchCode" => $BranchCode,
//                        "UserId" => $UserId,
//                        "TransactionCode" => $TransactionCode,
//                        "RequestDateTime" => $RequestDateTime,
//                        "ICNumber" => $ICNumber,
//                        "RequestIndicator" => $RequestIndicator,
//                    ));
////                    dd($response);
//                    if($response->ReplyIndicator == '1' || $response->ReplyIndicator == '2')
//                    {
//                        $arrData = $response;
//                        $Name = $response->Name;
//                        $matchname = (strtoupper(implode(explode(' ',$Name))) == strtoupper(implode(explode(' ',$Nama_Pengadu))))?true:false;
//                        // Umur
//                            if($matchname)
//                            {
//                                $Age = Carbon::createFromDate(date('Y', strtotime($response->DateOfBirth)), 5, 21)->age;
//                                $arrData->Age = $Age;
//                                switch ($Age) {
//                                    case ($Age <= 18):
//                                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'100'])->first();
//                                        $arrData->Age = '100';
//                                break;
//                                    case ($Age >= 19 && $Age <= 25):
//                                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'101'])->first();
//                                        $arrData->Age = '101';
//                                break;
//                                    case ($Age >= 26 && $Age <= 40):
//                                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'102'])->first();
//                                        $arrData->Age = '102';
//                                break;
//                                    case ($Age >= 41 && $Age <= 55):
//                                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'103'])->first();
//                                        $arrData->Age = '103';
//                                break;
//                                    case ($Age >= 56):
//                                        $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat'=>'309','code'=>'104'])->first();
//                                        $arrData->Age = '104';
//                                break;
//                                    default:
//                                        $arrData->Age = '0';
//                                }
//                //                $MobileNo = 
//                                $arrData->Gdr = ($response->Gender == '0')? '0' : ($response->Gender == 'L'? 'M' : 'F'); // Jantina
//                                // Warganegara
//                                if ($response->ResidentialStatus=='B' ||
//                                    $response->ResidentialStatus=='C' ||
//                                    $response->ResidentialStatus=='M' ||
//                                    $response->ResidentialStatus=='P' ||
//                                    $response->ResidentialStatus=='') {
//                                    $arrData->Warganegara = 'malaysia';
//                                    if ($response->RecordStatus=='2' ||
//                                        $response->RecordStatus=='B' ||
//                                        $response->RecordStatus=='H') { // Sudah Meninggal
//                                        $arrData->error = "Individu direkodkan telah meninggal dunia";
//                                        $StatusPengadu='6'; // Individu direkodkan telah meninggal dunia
//                                    } else {
//                                      // Dapatkan rekod jpn letak dlm array
//                                        $arrData->error = "";
//                                        if ($response->ResidentialStatus=='B' ||
//                                            $response->ResidentialStatus=='C') {
//                                            $StatusPengadu='1';  // Warganegara
//                                        } else {
//                                            $StatusPengadu='2'; // Pemastautin Tetap
//                                        }
//                                    }
//                                }
//                                $UserAddress = "$response->CorrespondenceAddress1 $response->CorrespondenceAddress2";
//                                $UserAge = $arrData->Age;
//                                $UserGender = $arrData->Gdr;
//                                $UserCountry = $response->CorrespondenceAddressCountryCode;
//                                $error = "";
//                                
//                        } else {
//                            $arrData = $response;
//                           
//                            $error = "Nama tidak sepadan dengan Kad Pengenalan";
//                            $StatusPengadu='4'; // Nama tidak sama dengan No. kp
//                        }
//                    }
//                    else
//                    {
//                        $arrData = $response;
//                        $error = "No. Kad Pengenalan Tidak Sah";
////                        dd($error);
////                        return json_encode($arrData);
//                    }
//                } catch (SoapFault $fault) {
//                        $arrData = $fault;
//
//                        $error = "Masalah teknikal";
////                        return json_encode($arrData);
//                }
////                $error = $arrData->error;
//        } else {
////            $UserAddress = $request->address;
//            $UserAge =  $request['CA_AGE'];
//            $UserGender =  $request['CA_SEXCD'];
//            $UserCountry =  $request['CA_NATCD'];
//            $error = "";
//        }
//        if($error == ""){

        $mCallCenterCase->CA_INVSTS = '1'; // Belum mula
        $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        $mCallCenterCase->CA_DEPTCD = $DeptCd;
        if ($DeptCd == 'BPGK') {
            if ($request->CA_AGAINST_STATECD == '16') {
                $FindBrn = DB::table('sys_brn')
                        ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                        ->where('BR_STATECD', $request->CA_AGAINST_STATECD)
                        ->where(DB::raw("LOCATE(CONCAT(',', '$request->CA_AGAINST_DISTCD' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                        ->where('BR_DEPTCD', 'BGK')
                        ->where('BR_STATUS', 1)
                        ->first();
            } else {
                $FindBrn = DB::table('sys_brn')
                        ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                        ->where('BR_STATECD', $request->CA_AGAINST_STATECD)
                        ->where(DB::raw("LOCATE(CONCAT(',', '$request->CA_AGAINST_DISTCD' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                        ->where('BR_DEPTCD', $DeptCd)
                        ->where('BR_STATUS', 1)
                        ->first();
            }
            $BrnCd = $FindBrn->BR_BRNCD;
        } else {
            $FindBrn = DB::table('sys_brn')
                    ->select('BR_BRNCD', 'BR_BRNNM', 'BR_OTHDIST')
                    ->where('BR_STATECD', 16)
                    ->where(DB::raw("LOCATE(CONCAT(',', '1601' ,','),CONCAT(',',BR_OTHDIST,','))"), ">", 0)
                    ->where('BR_DEPTCD', $DeptCd)
                    ->where('BR_STATUS', 1)
                    ->first();
            $BrnCd = $FindBrn->BR_BRNCD;
        }
        $mCallCenterCase->CA_BRNCD = $BrnCd;
//        dd($FindBrn);
//        dd($mCallCenterCase);
//        dd($request);
        if ($mCallCenterCase->save()) {
            $TemplateSuratPengadu = Letter::where(['letter_type' => '01', 'letter_code' => $mCallCenterCase->CA_INVSTS, 'status' => '1'])->first();
            $DerafSurat = $TemplateSuratPengadu->header . $TemplateSuratPengadu->body . $TemplateSuratPengadu->footer;

            $mDaerah = DB::table('sys_ref')->select('descr')->where(['cat' => '18', 'code' => $mCallCenterCase->CA_DISTCD])->first();
            $mNegeri = DB::table('sys_ref')->select('descr')->where(['cat' => '17', 'code' => $mCallCenterCase->CA_STATECD])->first();
            if ($mDaerah != '')
                $Daerah = $mDaerah->descr;

            if ($mNegeri != '')
                $Negeri = $mNegeri->descr;

            $patterns[1] = "#NAMAPENGADU#";
            $patterns[2] = "#ALAMATPENGADU#";
            $patterns[3] = "#DAERAHPENGADU#";
            $patterns[4] = "#NEGERIPENGADU#";
            $patterns[5] = "#NOADUAN#";
            $replacements[1] = $mCallCenterCase->CA_NAME;
            $replacements[2] = $mCallCenterCase->CA_ADDR;
            $replacements[3] = $Daerah;
            $replacements[4] = $Negeri;
            $replacements[5] = $mCallCenterCase->CA_CASEID;

            $ReplaceSurat = preg_replace($patterns, $replacements, urldecode($DerafSurat));
            $arr_rep = array("#", "#");
            $FinalSurat = str_replace($arr_rep, "", $ReplaceSurat);

            $UserId = Auth::User()->id;
            $CurrDate = date('YmdHis');

            $mPDF = PDF::loadHTML($FinalSurat);
            $FileName = $UserId . '_' . $CACASEID . '_' . $CurrDate . '_Penerimaan.pdf';

            $Year = date('Y');
            $Month = date('m');
            Storage::disk('letter')->makeDirectory('/' . $Year . '/' . $Month . '/');
            Storage::disk('letter')->put('/' . $Year . '/' . $Month . '/' . $FileName, $mPDF->output());

            $mAttachment = new Attachment;
            $mAttachment->doc_title = $TemplateSuratPengadu->title;
            $mAttachment->file_name = $TemplateSuratPengadu->title;
            $mAttachment->file_name_sys = '/' . $Year . '/' . $Month . '/' . $FileName;
            if ($mAttachment->save()) {
                $CallCenterCaseDetailOld = CallCenterCaseDetail::where(['CD_CASEID' => $CACASEID, 'CD_CURSTS' => 1])->first();
                $CallCenterCaseDetailOld->CD_CURSTS = '0';
                if ($CallCenterCaseDetailOld->save()) {
                    $CallCenterCaseDetailNew = new CallCenterCaseDetail();
                    $CallCenterCaseDetailNew->CD_CASEID = $CACASEID;
                    $CallCenterCaseDetailNew->CD_TYPE = '';
                    $CallCenterCaseDetailNew->CD_DESC = '';
                    $CallCenterCaseDetailNew->CD_ACTTYPE = '';
                    $CallCenterCaseDetailNew->CD_INVSTS = '1'; // Belum Mula
                    $CallCenterCaseDetailNew->CD_CASESTS = '1'; // Belum di beri penugasan
                    $CallCenterCaseDetailNew->CD_CURSTS = '1';
                    $CallCenterCaseDetailNew->CD_ACTFROM = '';
                    $CallCenterCaseDetailNew->CD_ACTTO = '';
                    $CallCenterCaseDetailNew->CD_DOCATTCHID_PUBLIC = $mAttachment->id;
                    if ($CallCenterCaseDetailNew->save()) {
                        if ($DeptCd != 'BPGK') {
                            event(new CallCenterSubmit($request));
                        }
                        return back()->with('success', 'Aduan anda telah <b>BERJAYA</b> dihantar');
                    }
                }
            }
        }
////        } else {
////            $mCallCase = CallCenterCase::find($CACASEID);
////            $mUser = \App\User::find($mCallCase->CA_RCVBY);
////            if($mUser) {
////                $RcvBy = $mUser->name;
////            }else{
////                $RcvBy = '';
////            }
////            $CountDoc = DB::table('case_doc')
////                        ->where('CC_CASEID', $CACASEID)
////                        ->count('CC_CASEID');   
////            $request->session()->flash('warning', $error);
////            return view('aduan/call-center-case.edit',compact('mCallCase', 'CountDoc', 'RcvBy', 'CACASEID'));
//        }
    }
    
    public function destroy(Request $request, $id) {
//        dd($id);
        $model = CallCenterCaseDoc::find($id);
        Storage::delete($model->CC_PATH.$model->CC_IMG);
        if($model->delete()){
            $request->session()->flash('success', 'Bahan Bukti telah berjaya dihapus');
            return redirect()->route('call-center-case.attachment',$model->CC_CASEID);
        }
    }

    public function GetDataTableAttachment($CASEID) {
        $mCallCenterCaseDoc = CallCenterCaseDoc::where('CC_CASEID', $CASEID);

        $datatables = Datatables::of($mCallCenterCaseDoc)
                ->addIndexColumn()
                ->editColumn('CC_IMG_NAME', function(CallCenterCaseDoc $CallCenterCaseDoc) {
                    if ($CallCenterCaseDoc->CC_IMG_NAME != '')
                        return '<a href=' . Storage::disk('bahanpath')->url($CallCenterCaseDoc->CC_PATH . $CallCenterCaseDoc->CC_IMG) . ' target="_blank">' . $CallCenterCaseDoc->CC_IMG_NAME . '</a>';
                    else
                        return '';
                })
                ->addColumn('action', function (CallCenterCaseDoc $CallCenterCaseDoc) {
                    return view('aduan.call-center-case.AttachmentActionBtn', compact('CallCenterCaseDoc'))->render();
                })
                ->editColumn('created_at', function(CallCenterCaseDoc $CallCenterCaseDoc) {
                if($CallCenterCaseDoc->updated_at != '')
                    return $CallCenterCaseDoc->updated_at ? with(new Carbon($CallCenterCaseDoc->updated_at))->format('d-m-Y h:i A') : 
                    '';
                else
                    return with(new Carbon($CallCenterCaseDoc->created_at))->format('d-m-Y h:i A');
                })
                ->rawColumns(['CC_IMG_NAME', 'action']);

        return $datatables->make(true);
    }

    public function AjaxValidateCreateAttachment(Request $request) {
        $file = $request->file('file');
        if($file) 
        {
            if($file->getClientOriginalExtension() == 'pdf')
            {
                $validator = Validator::make($request->all(), 
                    [
                        'file' => 'required | max:2048'
                    ], 
                    [
                        'file.required' => 'Ruangan Fail diperlukan.',
                        'file.max' => 'Fail format pdf mesti tidak melebihi 2Mb.',
                    ]
                );
            }
            else
            {
                $validator = Validator::make($request->all(), 
                    [
                        'file' => 'required | mimes:jpeg,jpg,png,pdf'
                    ], 
                    [
                        'file.required' => 'Ruangan Fail diperlukan.',
                        'file.mimes' => 'Format fail mesti jpeg,jpg,png,pdf.',
                    ]
                );
            }
        }
        else
        {
            $validator = Validator::make($request->all(), 
                [
                    'file' => 'required'
                ], 
                [
                    'file.required' => 'Ruangan Fail diperlukan.',
                ]
            );
        }

        if ($validator->fails()) {
            return response()->json(['fails' => $validator->getMessageBag()]);
        } else {
            return response()->json(['success']);
        }
    }
    
    public function AjaxValidateEditAttachment(Request $request) {
        $file = $request->file('file');
        if($file)
        {
            if($file->getClientOriginalExtension() == 'pdf')
            {
                $validator = Validator::make($request->all(), 
                    [
                        'file' => 'max:2048'
                    ], 
                    [
                        'file.max' => 'Fail format pdf mesti tidak melebihi 2Mb.',
                    ]
                );
                
                if ($validator->fails()) {
                    return response()->json(['fails' => $validator->getMessageBag()]);
                } else {
                    return response()->json(['success']);
                }
            }
            else
            {
                $validator = Validator::make($request->all(), 
                    [
                        'file' => 'mimes:jpeg,jpg,png,pdf'
                    ], 
                    [
                        'file.mimes' => 'Format fail mesti jpeg,jpg,png,pdf.',
                    ]
                );
                
                if ($validator->fails()) {
                    return response()->json(['fails' => $validator->getMessageBag()]);
                } else {
                    return response()->json(['success']);
                }
            }
        } else {
            return response()->json(['success']);
        }
    }

    public function StoreAttachment(Request $request) {
//        $date = date('Ymdhis');
//        $userid = Auth()->user()->id;
//        $file = $request->file('file');
//        
//        if($file) {
//            $filename = $userid.'_'.$request->CA_CASEID.'_'.$date.'.'.$file->getClientOriginalExtension();
//            $attachment = $request->file('file')->storeAs('public', $filename);
//            
//            $mAttachment = new Attachment();
//            $mAttachment->doc_title = $request->doc_title;
//            $mAttachment->file_name = $file->getClientOriginalName();
//            $mAttachment->file_name_sys = $filename;
//            if($mAttachment->save()) {
//                $mCallCenterCaseDoc = new CallCenterCaseDoc();
//                $mCallCenterCaseDoc->CC_DOCATTCHID = $mAttachment->id;
//                $mCallCenterCaseDoc->CC_CASEID = $request->CA_CASEID;
//                if($mCallCenterCaseDoc->save()) {
//                    return redirect()->route('call-center-case.create', ['id' => $request->CA_CASEID,'#case-attachment']);
//                }
//            }
//        }

        $date = date('Ymdhis');
        $userid = Auth::user()->id;
        $Year = date('Y');
        $Month = date('m');
        $file = $request->file('file');

        if ($file) {
            $filename = $userid . '_' . $request->CA_CASEID . '_' . $date . '.' . $file->getClientOriginalExtension();
            $directory = '/' . $Year . '/' . $Month . '/';
            Storage::disk('bahan')->makeDirectory($directory);

            if ($file->getClientSize() > 2000000) { // if file size lebih 2mb
                $resize = Image::make($file)->resize(null, 4096, function ($constraint) { // returns Intervention\Image\Image
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $hash = md5($resize->__toString()); // calculate md5 hash of encoded image
                $path = "images/{$hash}.jpg"; // use hash as a name
                $resize->save(public_path($path)); // save it locally to ~/public/images/{$hash}.jpg
                Storage::disk('bahan')->put($directory . $filename, $resize->__toString());
                unlink($path);
            } else {
                Storage::disk('bahan')->putFileAs('/' . $Year . '/' . $Month . '/', $request->file('file'), $filename);
            }

            $mAttachment = new \App\Aduan\PublicCaseDoc();
            $mAttachment->CC_CASEID = $request->CA_CASEID;
            $mAttachment->CC_PATH = Storage::disk('bahan')->url($directory);
            $mAttachment->CC_IMG = $filename;
            $mAttachment->CC_IMG_NAME = $file->getClientOriginalName();
            $mAttachment->CC_REMARKS = $request->doc_title;
            if ($mAttachment->save()) {
//                    return redirect('call-center-case.edit'.$request->CA_CASEID.'/edit#attachment');
                return redirect('/call-center-case/' . $request->CA_CASEID . '/edit#attachment');
            }
        }
    }

    public function AttachmentDestroy($CASEID, $DOCATTCHID) {
        $mAttachment = Attachment::find($DOCATTCHID);

        if (Storage::disk('public')->delete($mAttachment->file_name_sys)) {
            if (CallCenterCaseDoc::where(['CC_DOCATTCHID' => $DOCATTCHID, 'CC_CASEID' => $CASEID])->delete()) {
                if ($mAttachment->delete()) {
                    return redirect()->route('call-center-case.edit', ['id' => $CASEID, '#case-attachment']);
                }
            }
        }
    }

    public function AttachmentEdit($DOCATTCHID) {
        $mAttachment = DB::table('doc_attach')
                ->where('id', $DOCATTCHID)
                ->first();
        return view('aduan.call-center-case.AttachmentEdit', compact('mAttachment'));
    }

    public function AttachmentUpdate(Request $request, $id) {
        $mAttachment = Attachment::find($id);
        $mCaseDoc = CallCenterCaseDoc::where(['CC_DOCATTCHID' => $id])->first();

        $file = $request->file('file');
        $date = date('Ymdhis');
        $userid = Auth()->user()->id;

        if ($file) {
            if (Storage::disk('public')->delete($mAttachment->file_name_sys)) {
                $filename = $userid . '_' . $mCaseDoc->CC_CASEID . '_' . $date . '.' . $file->getClientOriginalExtension();
                $attachment = $request->file('file')->storeAs('public', $filename);

                $mAttachment->file_name = $file->getClientOriginalName();
                $mAttachment->file_name_sys = $filename;

                if ($mAttachment->save()) {
                    return redirect()->route('call-center-case.edit', ['id' => $mCaseDoc->CC_CASEID, '#case-attachment']);
                }
            }
        } else {
            $mAttachment->doc_title = $request->doc_title;
            if ($mAttachment->save()) {
                return redirect()->route('call-center-case.edit', ['id' => $mCaseDoc->CC_CASEID, '#case-attachment']);
            }
        }
    }

    public function GetCmplList($cat_cd) {
        $mCatList = DB::table('sys_ref')
                ->where('cat', '634')
                ->where('code', 'like', "$cat_cd%")
                ->where('status', '1')
                ->orderBy('sort', 'asc')
//                ->orderBy('descr', 'asc')
                ->pluck('code', 'descr');
//                ->prepend('', '-- SILA PILIH --');
        if(count($mCatList) != 1){
            $mCatList->prepend('', '-- SILA PILIH --');
        }
        return json_encode($mCatList);
    }

    public function GetstateList($state_cd) {
        $mDistList = DB::table('sys_ref')
                ->where('cat', '18')
                ->where('code', 'like', "$state_cd%")
                ->orderBy('sort')
                ->pluck('code', 'descr')
                ->prepend('', '-- SILA PILIH --');
        return json_encode($mDistList);
    }

    public function GetDataTableTransaction($CASEID) {
        $mCallCenterCaseDetail = CallCenterCaseDetail::where('CD_CASEID', $CASEID)
                ->orderBy('CD_CREDT', 'DESC')
                ->get();

        $datatables = Datatables::of($mCallCenterCaseDetail)
                ->addIndexColumn()
                ->editColumn('CD_INVSTS', function(CallCenterCaseDetail $CallCenterCaseDetail) {
                    if ($CallCenterCaseDetail->CD_INVSTS != '')
                        return $CallCenterCaseDetail->StatusAduan->descr;
                    else
                        return '';
                })
                ->editColumn('CD_ACTFROM', function(CallCenterCaseDetail $CallCenterCaseDetail) {
//                    if ($CallCenterCaseDetail->CD_ACTFROM != '')
//                        return $CallCenterCaseDetail->UserDaripada->name;
//                    else
//                        return '';
                    if ($CallCenterCaseDetail->CD_ACTFROM != ''){
                        if ($CallCenterCaseDetail->UserDaripada){
                            return $CallCenterCaseDetail->UserDaripada->name;
                        } else {
                            return $CallCenterCaseDetail->CD_ACTFROM;
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('CD_CREDT', function(CallCenterCaseDetail $CallCenterCaseDetail) {
                    if ($CallCenterCaseDetail->CD_CREDT != '')
                        return date('d-m-Y H:m:s', strtotime ($CallCenterCaseDetail->CD_CREDT));
                    else
                        return '';
                })
                ->editColumn('CD_ACTTO', function(CallCenterCaseDetail $CallCenterCaseDetail) {
//                    if ($CallCenterCaseDetail->CD_ACTTO != '')
//                        return $CallCenterCaseDetail->UserKepada->name;
//                    else
//                        return '';
                    if ($CallCenterCaseDetail->CD_ACTTO != ''){
                        if($CallCenterCaseDetail->UserKepada){
                            return $CallCenterCaseDetail->UserKepada->name;
                        } else {
                            return $CallCenterCaseDetail->CD_ACTTO;
                        }
                    } else {
                        return '';
                    }
        });

        return $datatables->make(true);
    }

    public function ShowSummary($CASEID) {
        $model = CallCenterCase::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = PenugasanCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = CallCenterCaseDoc::where(['CC_CASEID' => $CASEID])->get();
        return view('aduan.call-center-case.show_summary_modal', compact('model', 'trnsksi', 'img'));
    }

    public function PrintSummary($CASEID) {
        $model = CallCenterCase::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = CallCenterCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = CallCenterCaseDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('aduan.call-center-case.show_summary_modal', compact('model', 'trnsksi', 'img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }
    
    public function delete(Request $request, $id) {
        $mCallCenterCase = CallCenterCase::where('id', $id)->whereNull('CA_CASEID')->first();
        $mCallCenterCaseDetail = CallCenterCaseDetail::where('CD_CASEID', $id)->first();
        $mCallCenterCaseDoc = CallCenterCaseDoc::where('CC_CASEID', $id)->get();

        if (!empty($mCallCenterCaseDoc)) {
            foreach ($mCallCenterCaseDoc as $doc) {
                Storage::delete('public/'.$doc->CC_PATH.$doc->CC_IMG);
                $doc->delete();
            }
        }

        if (!empty($mCallCenterCaseDetail)) {
            $mCallCenterCaseDetail->delete();
        }

        if (!empty($mCallCenterCase)) {
            $mCallCenterCase->delete();
        }

        session()->flash('success', 'Aduan call center berstatus deraf telah berjaya dihapus');
        return redirect()->back();
    }

}

?>
