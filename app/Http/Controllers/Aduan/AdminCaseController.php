<?php

namespace App\Http\Controllers\Aduan;

use App;
use App\Aduan\AdminCase;
use App\Aduan\AdminCaseDetail;
use App\Aduan\AdminCaseDoc;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\Letter;
use App\Mail\AduanTerimaAdmin;
use App\Models\Feedback\FeedTelegram;
use App\Models\Feedback\FeedTelegramDetail;
use App\Models\Feedback\FeedWhatsapp;
use App\Models\Feedback\FeedWhatsappDetail;
use App\Repositories\ConsumerComplaint\CaseInfoRepository;
use App\Repositories\Feedback\Telegram\TelegramDetailRepository;
use App\Repositories\Feedback\Telegram\TelegramRepository;
use App\Repositories\Feedback\Whatsapp\WaboxappRepository;
use App\Repositories\Feedback\Whatsapp\WhatsappDetailRepository;
use App\Repositories\MyIdentityRepository;
use App\Repositories\RunnerRepository;
use App\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use JavaScript;
use PDF;
use SoapClient;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $DOCNO
     * @return \Illuminate\Http\Response
     * @throws \SoapFault
     */
    public function GetDataJpn($DOCNO = null)
    {
        $AgencyCode = "110012";
        $BranchCode = "eAduan";
        $TransactionCode = "T2"; // T2 - Admin Page, T7 - Public Page
        $RequestDateTime = date('Y-m-d H:i:s'); //date("Y-m-d") . "T" . date("H:i:s");
        $RequestIndicator = "A";
        $UserId = $DOCNO;
        $ICNumber = $DOCNO;

        if ($DOCNO == null) {
            return json_encode('');
        }

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

            libxml_disable_entity_loader(false);
            // $client = new SoapClient("https://eaduan.kpdnkk.gov.my/tojpn/tomyidentiti/crsservice.wsdl", $soapClientOptions);
            if (App::environment(['production'])) {
                $client = new SoapClient(public_path("tojpn/tomyidentiti/crsservice.wsdl"), $soapClientOptions);
            } else {
                $client = new SoapClient(public_path("tojpn/tomyidentiti/crswsdev.wsdl"), $soapClientOptions);
            }
            libxml_disable_entity_loader(true);

            $response = $client->retrieveCitizensData(array(
                "AgencyCode" => $AgencyCode,
                "BranchCode" => $BranchCode,
                "UserId" => $UserId,
                "TransactionCode" => $TransactionCode,
                "RequestDateTime" => $RequestDateTime,
                "ICNumber" => $ICNumber,
                "RequestIndicator" => $RequestIndicator,
            ));
            //var_dump($response);
            $response = get_object_vars($response); // Pull parameters from SOAP connection
            // Sort out the parameters and grab their data
            $AgencyCode = $response['AgencyCode'] ?? null;
            $BranchCode = $response['BranchCode'] ?? null;
            $UserId = $response['UserId'] ?? null;
            $TransactionCode = $response['TransactionCode'] ?? null;
            $ReplyDateTime = $response['ReplyDateTime'] ?? null;
            $ReplyIndicator = trim($response['ReplyIndicator']) ?? null;
            $ICNumber = $response['ICNumber'] ?? null;
            $Name = isset($response['Name']) ? $response['Name'] : null;
            $DateOfBirth = isset($response['DateOfBirth']) ? $response['DateOfBirth'] : null;
            $Gender = isset($response['Gender']) ? $response['Gender'] : null;
            $Race = isset($response['Race']) ? $response['Race'] : null;
            $Religion = isset($response['Religion']) ? $response['Religion'] : null;
            $PermanentAddress1 = isset($response['PermanentAddress1']) ? $response['PermanentAddress1'] : null;
            $PermanentAddress2 = isset($response['PermanentAddress2']) ? $response['PermanentAddress2'] : null;
            $PermanentAddress3 = isset($response['PermanentAddress3']) ? $response['PermanentAddress3'] : null;
            $PermanentAddressPostcode = isset($response['PermanentAddressPostcode']) ? $response['PermanentAddressPostcode'] : null;
            $PermanentAddressCityCode = isset($response['PermanentAddressCityCode']) ? $response['PermanentAddressCityCode'] : null;
            $PermanentAddressCityDesc = isset($response['PermanentAddressCityDesc']) ? $response['PermanentAddressCityDesc'] : null;
            $PermanentAddressStateCode = isset($response['PermanentAddressStateCode']) ? $response['PermanentAddressStateCode'] : null;
            $CorrespondenceAddress1 = isset($response['CorrespondenceAddress1']) ? $response['CorrespondenceAddress1'] : null;
            $CorrespondenceAddress2 = isset($response['CorrespondenceAddress2']) ? $response['CorrespondenceAddress2'] : null;
            $CorrespondenceAddress3 = isset($response['CorrespondenceAddress3']) ? $response['CorrespondenceAddress3'] : null;
            $CorrespondenceAddress4 = isset($response['CorrespondenceAddress4']) ? $response['CorrespondenceAddress4'] : null;
            $CorrespondenceAddress5 = isset($response['CorrespondenceAddress5']) ? $response['CorrespondenceAddress5'] : null;
            $CorrespondenceAddressPostcode = isset($response['CorrespondenceAddressPostcode']) ? $response['CorrespondenceAddressPostcode'] : null;
            $CorrespondenceAddressCityCode = isset($response['CorrespondenceAddressCityCode']) ? $response['CorrespondenceAddressCityCode'] : null;
            $CorrespondenceAddressCityDescription = isset($response['CorrespondenceAddressCityDescription']) ? $response['CorrespondenceAddressCityDescription'] : null;
            $CorrespondenceAddressStateCode = isset($response['CorrespondenceAddressStateCode']) ? $response['CorrespondenceAddressStateCode'] : null;
            $CorrespondenceAddressCountryCode = isset($response['CorrespondenceAddressCountryCode']) ? $response['CorrespondenceAddressCountryCode'] : null;
            $OldICnumber = isset($response['OldICnumber']) ? $response['OldICnumber'] : null;
            $DateOfDeath = isset($response['DateOfDeath']) ? $response['DateOfDeath'] : null;
            $CitizenshipStatus = isset($response['CitizenshipStatus']) ? $response['CitizenshipStatus'] : null;
            $ResidentialStatus = isset($response['ResidentialStatus']) ? trim($response['ResidentialStatus']) : null;
            $EmailAddress = isset($response['EmailAddress']) ? $response['EmailAddress'] : null;
            $MobilePhoneNumber = isset($response['MobilePhoneNumber']) ? $response['MobilePhoneNumber'] : null;
            $AddressStatus = isset($response['AddressStatus']) ? $response['AddressStatus'] : null;
            $RecordStatus = isset($response['RecordStatus']) ? trim($response['RecordStatus']) : null;
            $NewICNumber = isset($response['NewICNumber']) ? $response['NewICNumber'] : null;
            $CorrespondenceAddressUpdateDate = isset($response['CorrespondenceAddressUpdateDate']) ? $response['CorrespondenceAddressUpdateDate'] : null;
            $CorrespondenceAddressUpdateBy = isset($response['CorrespondenceAddressUpdateBy']) ? $response['CorrespondenceAddressUpdateBy'] : null;
            $VerifyStatus = isset($response['VerifyStatus']) ? $response['VerifyStatus'] : null;
            $MessageCode = isset($response['MessageCode']) ? $response['MessageCode'] : null;
            $Message = isset($response['Message']) ? $response['Message'] : null;
            //header("Content-Type: image/png");
            //$hexpic= $response['Photo'];
            //$data = pack("H" . strlen($hexpic), $hexpic);

            //echo $data;
            //echo "Request :<br>", htmlentities($soapClient->__getLastRequest()), "<br>";

            $proceedgetdata = false;
            if ($ReplyIndicator == '1' || $ReplyIndicator == '2') { //1 - Success  2 - Alert)
                /* if (($nocheckname)) {
                    $matchname=true;
                }else {
                    // untuk buang space dalam nama yg disi dan nama dari jpn kemudian bandingkan
                    $matchname= (strtoupper(implode(explode(' ',$Name))) == strtoupper(implode(explode(' ',$Nama_Pengadu))))?true:false;
                } */
                //if ($matchname){
                if ($ResidentialStatus == 'B' ||
                    $ResidentialStatus == 'C' ||
                    $ResidentialStatus == 'M' ||
                    $ResidentialStatus == 'P' ||
                    $ResidentialStatus == ''
                ) { // Warganegara dan Pemastautin Tetap
                    if ($RecordStatus == '2' ||
                        $RecordStatus == 'B' ||
                        $RecordStatus == 'H') { // Sudah Meninggal
                        $Msg = "Individu direkodkan telah meninggal dunia";
                        $Message = $Msg;
                        $StatusPengadu = '6'; // Individu direkodkan telah meninggal dunia
                        $proceedgetdata = true; // kalu nok juga data
                    } else {
                        // Dapatkan rekod jpn letak dlm array
                        $Msg = "";
                        $proceedgetdata = true;
                        if ($ResidentialStatus == 'B' ||
                            $ResidentialStatus == 'C') {
                            $StatusPengadu = '1';  // Warganegara
                            $Msg = "Warganegara";
                        } else {
                            $StatusPengadu = '2'; // Pemastautin Tetap
                            $Msg = "Pemastautin Tetap";
                        }

                    }
                } else {
                    $Msg = "Bukan Warga Negara";
                    $proceedgetdata = true;
                    $StatusPengadu = '3'; // Bukan Warganegara
                }
                //} else {
                //$Msg = "Nama tidak sepadan dengan Kad Pengenalan";
                //$StatusPengadu='4'; // Nama tidak sama dengan No. kp
                //}
            } else {
                //$Msg = "No. Kp tiada dalam pangkalan data MyIdentiti"; // Status Pengadu = 4
                $Msg = "No. Kad Pengenalan/Pasport Tidak Sah";
                $StatusPengadu = '5'; // No. Kp tidak Sah
            }

            $arr = array();
            $arr['StatusPengadu'] = $StatusPengadu;
            $arr['Matching'] = 'Y';
            $arr['Msg'] = $Msg;
            $arr['ReplyIndicator'] = $ReplyIndicator;
            $arr['ResidentialStatus'] = $ResidentialStatus;
            $arr['RecordStatus'] = $RecordStatus;
            $arr['Message'] = $Message;
            $arr['Name'] = $Name;
            $arr['CorrespondenceAddress1'] = trim($CorrespondenceAddress1);
            $arr['CorrespondenceAddress2'] = trim($CorrespondenceAddress2);
            $arr['CorrespondenceAddressPostcode'] = trim($CorrespondenceAddressPostcode);
            $arr['CorrespondenceAddressCityCode'] = trim($CorrespondenceAddressCityCode);
            $arr['CorrespondenceAddressCityDescription'] = trim($CorrespondenceAddressCityDescription);
            $arr['CorrespondenceAddressStateCode'] = trim($CorrespondenceAddressStateCode);
            $arr['CorrespondenceAddressCountryCode'] = trim($CorrespondenceAddressCountryCode);
            $arr['EmailAddress'] = trim($EmailAddress);
            $arr['MobilePhoneNumber'] = trim(str_ireplace("-", "", $MobilePhoneNumber));
            $arr['Gender'] = ($Gender == null) ? '' : (($Gender == 'L') ? 'M' : 'F');
            $arr['InfoGender'] = ($Gender == '0') ? '' : (($Gender == 'L') ? 'Lelaki' : 'Perempuan');
            $arr['DateOfBirth'] = trim($DateOfBirth);
            $arr['Race'] = $Race;
            $RaceCode = trim($Race);
            $arr['RaceCode'] = $RaceCode;
            if (!empty($RaceCode)) { // BANGSA
                if ($RaceCode == '0100') { // MELAYU
                    $mRefRaceCode = DB::table('sys_ref')
                        ->select('code')
                        ->where(['cat' => '580'])
                        ->where('descr', 'like', "%melayu%")
                        ->first();
                    if ($mRefRaceCode) {
                        $mRefRace = $mRefRaceCode->code;
                    } else {
                        $mRefRace = '';
                    }
                } else if ($RaceCode == '0200') { // CINA
                    $mRefRaceCode = DB::table('sys_ref')
                        ->select('code')
                        ->where(['cat' => '580'])
                        ->where('descr', 'like', "%cina%")
                        ->first();
                    if ($mRefRaceCode) {
                        $mRefRace = $mRefRaceCode->code;
                    } else {
                        $mRefRace = '';
                    }
                } else if ($RaceCode == '0300') { // INDIA
                    $mRefRaceCode = DB::table('sys_ref')
                        ->select('code')
                        ->where(['cat' => '580'])
                        ->where('descr', 'like', "%india%")
                        ->first();
                    if ($mRefRaceCode) {
                        $mRefRace = $mRefRaceCode->code;
                    } else {
                        $mRefRace = '';
                    }
                } else if ($RaceCode == '0800') { // BUMIPUTERA SABAH
                    $mRefRaceCode = DB::table('sys_ref')
                        ->select('code')
                        ->where(['cat' => '580'])
                        ->where('descr', 'like', "%sabah%")
                        ->first();
                    if ($mRefRaceCode) {
                        $mRefRace = $mRefRaceCode->code;
                    } else {
                        $mRefRace = '';
                    }
                } else if ($RaceCode == '1000') { // BUMIPUTERA SARAWAK
                    $mRefRaceCode = DB::table('sys_ref')
                        ->select('code')
                        ->where(['cat' => '580'])
                        ->where('descr', 'like', "%sarawak%")
                        ->first();
                    if ($mRefRaceCode) {
                        $mRefRace = $mRefRaceCode->code;
                    } else {
                        $mRefRace = '';
                    }
                } else if ($RaceCode == '0000' || $RaceCode == '9999') { // TIADA MAKLUMAT
                    $mRefRace = '';
                } else { // LAIN-LAIN
                    $mRefRaceCode = DB::table('sys_ref')
                        ->select('code')
                        ->where(['cat' => '580'])
                        ->where('descr', 'like', "%lain%")
                        ->first();
                    if ($mRefRaceCode) {
                        $mRefRace = $mRefRaceCode->code;
                    } else {
                        $mRefRace = '';
                    }
                }
            } else {
                $mRefRace = '';
            }
            $arr['RaceCd'] = $mRefRace;

            if ($arr['DateOfBirth'] != '')
                $arr['Age'] = date_diff(date_create($DateOfBirth), date_create('today'))->y;
            else
                $arr['Age'] = '';

            if ($ResidentialStatus == 'B' ||
                $ResidentialStatus == 'C' ||
                $ResidentialStatus == 'M' ||
                $ResidentialStatus == 'P' ||
                $ResidentialStatus == '') {
                $arr['Warganegara'] = '1';
                $arr['WarganegaraInfo'] = 'Warganegara/Pemastautin Tetap';
            } else {
                $arr['Warganegara'] = '0';
                $arr['WarganegaraInfo'] = 'Lain-lain';
            }
            // Daerah
            if ($arr['CorrespondenceAddressCityCode'] != '') {
                $KodDaerahEaduan = DB::table('kodmapping')->select('kodsistem')->where(['koddiberi' => $arr['CorrespondenceAddressCityCode']])->first();
                if ($KodDaerahEaduan == '') {
                    $arr['KodDaerah'] = '';
                } else {
                    $arr['KodDaerah'] = $KodDaerahEaduan->kodsistem;
                }
            } else {
                $arr['KodDaerah'] = '';
            }
            $arr['UserId'] = $UserId;
            $arr['ICNumber'] = $ICNumber;
            $arr['ReqRepDateTime'] = $RequestDateTime . " (Request Time) <==> " . $ReplyDateTime . " (Reply Time)";

            // start create log file for myIdentity server response
            $arrayLog = array_merge($response, $arr);
            $arrayLog['UserId'] = Auth::check() ? Auth::user()->username : $UserId;
            $arrayLog['RequestDateTime'] = $RequestDateTime;
            $arrayLog['RequestIndicator'] = $RequestIndicator;
            $arrayLog['MessageLog'] = $Msg;
            MyIdentityRepository::generatelog($arrayLog);
            // end create log file for myIdentity server response

            return json_encode($arr);

        // } catch (SoapFault $fault) {
        } catch (Exception $fault) {
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
            $arrData->Gender = '';
            $arrData->Warganegara = '';

            $arrData->error = "Masalah teknikal";

            // start create log file for myIdentity server response
            $arrayLog = get_object_vars($arrData);
            $arrayLog['AgencyCode'] = $AgencyCode;
            $arrayLog['BranchCode'] = $BranchCode;
            $arrayLog['UserId'] = Auth::check() ? Auth::user()->username : $UserId;
            $arrayLog['TransactionCode'] = $TransactionCode;
            $arrayLog['RequestDateTime'] = $RequestDateTime;
            $arrayLog['ICNumber'] = $ICNumber;
            $arrayLog['RequestIndicator'] = $RequestIndicator;
            $StatusPengadu = '7'; // Masalah teknikal
            $arrayLog['StatusPengadu'] = $StatusPengadu;
            $arrayLog['MessageLog'] = $arrData->error;
            MyIdentityRepository::generatelog($arrayLog);
            // end create log file for myIdentity server response

            // return json_encode($arrData);
            return response()->json($arrayLog);
        }

    }

    public function index()
    {
        return view('aduan.admin-case.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param null $feedback
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $feedback = $request->only('feedback');
        $sender = $request->only('sender');
        $data['CA_CMPLCAT'] = $request->old('CA_CMPLCAT') ?? null;
        $data['CA_AGAINST_PREMISE'] = $request->old('CA_AGAINST_PREMISE') ?? null;
        $data['CA_ONLINECMPL_PROVIDER'] = $request->old('CA_ONLINECMPL_PROVIDER') ?? null;
        $data['CA_ONLINECMPL_IND'] = $request->old('CA_ONLINECMPL_IND') ?? null;
        $data['CA_ONLINEADD_IND'] = $request->old('CA_ONLINEADD_IND') ?? null;
        $data['againstonlinecomplaint'] = $data['CA_CMPLCAT'] == 'BPGK 19' || $data['CA_AGAINST_PREMISE'] == 'P25';
        $data['providercaseno'] = $data['CA_ONLINECMPL_IND'] == 'on' && $data['againstonlinecomplaint'];
        $data['providerurl'] = $data['againstonlinecomplaint'] && $data['CA_ONLINECMPL_PROVIDER'] == '999';
        $data['againstaddress'] = (empty($data['CA_CMPLCAT']) && empty($data['CA_AGAINST_PREMISE']))
            || ($data['CA_CMPLCAT'] != 'BPGK 19' && $data['CA_AGAINST_PREMISE'] != 'P25')
            || ($data['CA_ONLINEADD_IND'] == 'on' && $data['againstonlinecomplaint']);

        JavaScript::put([
            'feedback' => $feedback,
            'sender' => $sender
        ]);

        $mRefWarganegara = DB::table('sys_ref')
            ->where(['cat' => '947', 'status' => '1'])
            ->select('code', 'descr')
            ->get();

        return view('aduan.admin-case.createcomplain')
            ->with(compact('mRefWarganegara', 'feedback', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        // suppose using this method or
        // if need more secure method using ->only()
        $data = $request->all();

        $request->merge(['CA_ONLINECMPL_AMOUNT' => str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT)]);
        if ($request->CA_CMPLCAT != 'BPGK 19') {
            $request->merge([
                // 'CA_ONLINECMPL_PROVIDER' => NULL,
                // 'CA_ONLINECMPL_URL' => NULL,
                'CA_ONLINECMPL_ACCNO' => NULL,
                // 'CA_ONLINECMPL_IND' => NULL,
                // 'CA_ONLINECMPL_CASENO' => NULL,
                // 'CA_ONLINEADD_IND' => NULL
            ]);
            // $this->validate($request, [
            //     'CA_RCVTYP' => 'required',
            //     'CA_DOCNO' => 'required',
            //     'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
            //     'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
            //     'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
            //     'CA_NAME' => 'required',
            //     'CA_STATECD' => 'required',
            //     'CA_DISTCD' => 'required',
            //     'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
            //     'CA_CMPLCAT' => 'required',
            //     'CA_CMPLCD' => 'required',
            //     'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
            //     'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
            //     'CA_AGAINST_PREMISE' => 'required',
            //     'CA_TTPMTYP' => 'required_if:CA_CMPLCAT,BPGK 08',
            //     'CA_TTPMNO' => 'required_if:CA_CMPLCAT,BPGK 08',
            //     'CA_AGAINSTNM' => 'required',
            //     'CA_AGAINSTADD' => 'required',
            //     'CA_AGAINST_STATECD' => 'required',
            //     'CA_AGAINST_DISTCD' => 'required',
            //     'CA_SUMMARY' => 'required',
            // ],
            //     [
            //         'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
            //         'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
            //         'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            //         'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            //         'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
            //         'CA_NAME.required' => 'Ruangan Nama diperlukan.',
            //         'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
            //         'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
            //         'CA_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
            //         'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
            //         'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
            //         'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian (RM) diperlukan.',
            //         'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
            //         'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan.',
            //         'CA_TTPMTYP.required_if' => 'Ruangan Penuntut/Penentang diperlukan.',
            //         'CA_TTPMNO.required_if' => 'Ruangan No. TTPM diperlukan.',
            //         'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis/Penjual) diperlukan.',
            //         'CA_AGAINSTADD.required' => 'Ruangan Alamat diperlukan.',
            //         'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
            //         'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan.',
            //         'CA_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
            //     ]);
        }
        // $this->validate($request, [
        $validator = Validator::make($request->all(), [
            'CA_RCVTYP' => 'required',
            'CA_SERVICENO' => 'required_if:CA_RCVTYP,S33',
            'CA_DOCNO' => 'required|max:15',
            'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO|max:60',
            'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL|max:20',
            'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL|max:20',
            'CA_FAXNO' => 'max:20',
            'CA_NAME' => 'required|max:60',
            'CA_AGE' => 'max:20',
            'CA_POSCD' => 'max:5',
            'CA_STATECD' => 'required',
            'CA_DISTCD' => 'required',
            'CA_NATCD' => 'required',
            'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
            'CA_CMPLCAT' => 'required',
            'CA_CMPLCD' => 'required',
            'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
            'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
            'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19|required_if:CA_AGAINST_PREMISE,P25',
            'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999|max:190',
            'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_ONLINECMPL_PYMNTTYP,TB,CA_CMPLCAT,BPGK 19',
            // 'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on',
            'CA_ONLINECMPL_CASENO' => 'max:80',
            'CA_AGAINSTNM' => 'required|max:190',
            'CA_AGAINST_TELNO' => 'max:20',
            'CA_AGAINST_MOBILENO' => 'max:20',
            'CA_AGAINST_EMAIL' => 'max:60',
            'CA_AGAINST_FAXNO' => 'max:20',
            'CA_AGAINST_POSTCD' => 'max:5',
            'CA_SUMMARY' => 'required',
        ],
            [
                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
                'CA_SERVICENO.required_if' => 'Ruangan No. Tali Khidmat diperlukan.',
                'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
                'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_EMAIL.max' => 'Jumlah Emel mesti tidak melebihi :max aksara.',
                'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_NAME.required' => 'Ruangan Nama diperlukan.',
                'CA_NAME.max' => 'Jumlah Nama mesti tidak melebihi 60 aksara.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_AGAINST_PREMISE.required_unless' => 'Ruangan Jenis Premis diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis/Penjual) diperlukan.',
                'CA_AGAINST_EMAIL.max' => 'Jumlah Emel mesti tidak melebihi :max aksara.',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_AGAINSTADD.required' => 'Ruangan Alamat diperlukan.',
                'CA_AGAINST_POSTCD.required_if' => 'Ruangan Poskod diperlukan.',
                'CA_AGAINST_POSTCD.required' => 'Ruangan Poskod diperlukan.',
                'CA_AGAINST_POSTCD.required_unless' => 'Ruangan Poskod diperlukan.',
                'CA_AGAINST_POSTCD.min' => 'Poskod mesti sekurang-kurangnya 5 angka.',
                'CA_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
                'CA_NATCD.required' => 'Ruangan Warganegara diperlukan.',
                'CA_POSCD.min' => 'Poskod mesti sekurang-kurangnya 5 angka.',
                'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
                'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
                'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan.',
                'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL / ID diperlukan.',
                'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian (RM) diperlukan.',
                'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan.',
                'CA_ONLINECMPL_BANKCD.required' => 'Ruangan Nama Bank diperlukan.',
                'CA_ONLINECMPL_ACCNO.required' => 'Ruangan No. Akaun Bank diperlukan.',
//            'CA_ONLINECMPL_CASENO.required_if' => 'Ruangan No. Aduan Rujukan diperlukan.',
            ]);
        $validator->sometimes(['CA_AGAINSTADD', 'CA_AGAINST_STATECD', 'CA_AGAINST_DISTCD'], 'required', function ($input) {
            return (empty($input->CA_CMPLCAT) && empty($input->CA_AGAINST_PREMISE))
                ||
                ($input->CA_CMPLCAT != 'BPGK 19' && $input->CA_AGAINST_PREMISE != 'P25')
                ||
                ($input->CA_ONLINEADD_IND == 'on' &&
                    ($input->CA_CMPLCAT == 'BPGK 19' || $input->CA_AGAINST_PREMISE == 'P25')
                );
        });
        $validator->sometimes(['CA_ONLINECMPL_BANKCD', 'CA_ONLINECMPL_ACCNO'], 'required', function ($input) {
            return $input->CA_CMPLCAT == 'BPGK 19'
                && in_array($input->CA_ONLINECMPL_PYMNTTYP, ['CRC', 'OTF', 'CDM']);
        });
        $validator->validate();

//        $mDate = date('Y');
//        $admincasetable = DB::table('case_info')
//            ->select('CA_CASEID')
//            ->whereYear('CA_RCVDT', $mDate)
//            ->orderBy('CA_RCVDT', 'desc')
//            ->limit(1)
//            ->first()
//        ;
//        
//        if(!empty($admincasetable)){
//            $caseidnew = $admincasetable->CA_CASEID;
//        } else {
//            $caseidnew = '';
//        }
//        
//        if ($caseidnew == '') {
//            $caseidnew = "00001";
//            $x = $caseidnew;
//        } else if ($caseidnew != '') {
//            $caseidnew = $caseidnew + 1;
//            $x = substr("$caseidnew", 2);
//        }
//        $y = substr("$mDate", 2, 4);
//        $z = "0" . $y . $x;

        $mAdminCase = new AdminCase;
        $mAdminCase->fill($request->all());

        if (in_array($request->CA_CMPLCAT, ['BPGK 01', 'BPGK 03'])) {
            $mAdminCase->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
        } else {
            $mAdminCase->CA_CMPLKEYWORD = NULL;
        }

        if ($request->CA_CMPLCAT == 'BPGK 19' || $request->CA_AGAINST_PREMISE == 'P25') {
            if ($request->CA_ONLINECMPL_IND) {
                $mAdminCase->CA_ONLINECMPL_IND = '1';
                $mAdminCase->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            } else {
                $mAdminCase->CA_ONLINECMPL_IND = '0';
                $mAdminCase->CA_ONLINECMPL_CASENO = NULL;
            }

            if ($request->CA_ONLINEADD_IND) {
                $mAdminCase->CA_ONLINEADD_IND = '1';
            } else {
                $mAdminCase->CA_ONLINEADD_IND = '0';
            }

            $mAdminCase->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            if ($request->CA_CMPLCAT == 'BPGK 19') {
                $mAdminCase->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
                if (in_array($request->CA_ONLINECMPL_PYMNTTYP, ['CRC', 'OTF', 'CDM'])) {
                    $mAdminCase->CA_ONLINECMPL_BANKCD = $request->CA_ONLINECMPL_BANKCD;
                    $mAdminCase->CA_ONLINECMPL_ACCNO = $request->CA_ONLINECMPL_ACCNO;
                }
            }
        } else {
            $mAdminCase->CA_ONLINECMPL_URL = NULL;
        }

//        $mAdminCase->CA_CASEID = $z;
        $mAdminCase->CA_INVSTS = '10'; // DERAF
//        $mAdminCase->CA_INVSTS = 1;
        $mAdminCase->CA_CASESTS = 1;
        $mAdminCase->CA_RCVTYP = request('CA_RCVTYP');
        $mAdminCase->CA_RCVBY = request('CA_RCVBY');
        // $mAdminCase->CA_RCVDT = Carbon::now();
        $mAdminCase->CA_RCVDT = Carbon::parse(request('CA_RCVDT'))->toDateTimeString();
        $mAdminCase->CA_DOCNO = request('CA_DOCNO');
        $mAdminCase->CA_NAME = request('CA_NAME');
        $mAdminCase->CA_EMAIL = request('CA_EMAIL');
        $mAdminCase->CA_MOBILENO = request('CA_MOBILENO');
        $mAdminCase->CA_TELNO = request('CA_TELNO');
        $mAdminCase->CA_FAXNO = request('CA_FAXNO');
        $mAdminCase->CA_SEXCD = request('CA_SEXCD');
        $mAdminCase->CA_AGE = request('CA_AGE');
        $mAdminCase->CA_ADDR = request('CA_ADDR');
        $mAdminCase->CA_RACECD = request('CA_RACECD');
        $mAdminCase->CA_POSCD = request('CA_POSCD');
        $mAdminCase->CA_STATECD = request('CA_STATECD');
        $mAdminCase->CA_DISTCD = request('CA_DISTCD');
        $mAdminCase->CA_NATCD = request('CA_NATCD');
        $mAdminCase->CA_COUNTRYCD = request('CA_COUNTRYCD');
        $mAdminCase->CA_CMPLCAT = request('CA_CMPLCAT');
        $mAdminCase->CA_CMPLCD = request('CA_CMPLCD');
        $mAdminCase->CA_AGAINST_PREMISE = request('CA_AGAINST_PREMISE');
        $mAdminCase->CA_AGAINSTNM = request('CA_AGAINSTNM');
        $mAdminCase->CA_AGAINST_TELNO = request('CA_AGAINST_TELNO');
        $mAdminCase->CA_AGAINST_MOBILENO = request('CA_AGAINST_MOBILENO');
        $mAdminCase->CA_AGAINST_EMAIL = request('CA_AGAINST_EMAIL');
        $mAdminCase->CA_AGAINST_FAXNO = request('CA_AGAINST_FAXNO');
        $mAdminCase->CA_AGAINSTADD = request('CA_AGAINSTADD');
        $mAdminCase->CA_AGAINST_POSTCD = request('CA_AGAINST_POSTCD');
        $mAdminCase->CA_AGAINST_STATECD = request('CA_AGAINST_STATECD');
        $mAdminCase->CA_AGAINST_DISTCD = request('CA_AGAINST_DISTCD');
        $mAdminCase->CA_SUMMARY = request('CA_SUMMARY');
        $DeptCd = explode(' ', $mAdminCase->CA_CMPLCAT)[0];
        $mAdminCase->CA_DEPTCD = $DeptCd;
        $mAdminCase->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);

        // if ($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19') {
        if (($request->CA_CMPLCAT != 'BPGK 19' && $request->CA_AGAINST_PREMISE != 'P25')
            ||
            ($request->CA_ONLINEADD_IND == 'on' &&
                ($request->CA_CMPLCAT == 'BPGK 19' || $request->CA_AGAINST_PREMISE == 'P25')
            )) {
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        } else {
            $StateCd = $mAdminCase->CA_STATECD;
            $DistCd = $mAdminCase->CA_DISTCD;
            $mAdminCase->CA_AGAINSTADD = NULL;
            $mAdminCase->CA_AGAINST_POSTCD = NULL;
            $mAdminCase->CA_AGAINST_STATECD = NULL;
            $mAdminCase->CA_AGAINST_DISTCD = NULL;
        }
        if ($request->CA_ROUTETOHQIND && $request->CA_ROUTETOHQIND == 'on') {
            $mAdminCase->CA_ROUTETOHQIND = '1';
//            $mAdminCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, true);
            $mAdminCase->CA_BRNCD = 'WHQR5';
        } else {
            $mAdminCase->CA_ROUTETOHQIND = '0';
            // $mAdminCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, false);
            $mAdminCase->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
        }

        // For feedback module usage:
        if (in_array($data['FEED_TYPE'], ['ws', 'telegram'])) {
            $ids = explode(';', $data['FEED_ID']);

            switch ($data['FEED_TYPE']) {
                case 'ws':
                    $feedback_module = FeedWhatsappDetail::find($ids[0]);
                    $feedback_module_id = $feedback_module->feed_whatsapp_id;
                    break;
                case 'telegram':
                    $feedback_module = FeedTelegramDetail::find($ids[0]);
                    $feedback_module_id = $feedback_module->feed_telegram_id;
                    break;
            }

            $mAdminCase->feedback_module_id = $feedback_module_id;
        }


        if ($mAdminCase->save()) {
            $mCaseDetail = new AdminCaseDetail;
            $mCaseDetail->CD_CASEID = $mAdminCase->id;
            $mCaseDetail->CD_TYPE = 'D';
            $mCaseDetail->CD_ACTTYPE = 'CLS';
            $mCaseDetail->CD_INVSTS = $mAdminCase->CA_INVSTS;
            $mCaseDetail->CD_CASESTS = $mAdminCase->CA_CASESTS;
            $mCaseDetail->CD_CURSTS = '1';
            if ($mCaseDetail->save()) {

                if ($data['CA_IMAGE'] != '') {
                    $ca_images = explode(';', $data['CA_IMAGE']);

                    foreach ($ca_images as $ca_image) {
                        $this->getAttachmentByUrl($mAdminCase->id, $ca_image);
                    }
                }

                // For feedback module usage:
                // Change all selected feed to is_ticketed = 1
                switch ($data['FEED_TYPE']) {
                    case 'ws':
                        WhatsappDetailRepository::linkFeedWithTicket($data['FEED_ID'], $mAdminCase->id);
                        break;
                    case 'telegram':
                        TelegramDetailRepository::linkFeedWithTicket($data['FEED_ID'], $mAdminCase->id);
                        break;
                }

//                $request->session()->flash('success', 'Aduan telah berjaya ditambah');
//                return redirect()->route('admin-case.edit', $mAdminCase->CA_CASEID);
                return redirect()->route('admin-case.attachment', $mAdminCase->id);
            }
        }


    }

    /**
     * Display the specified resource.
     *
     * @param \App\AdminCase $adminCase
     * @return \Illuminate\Http\Response
     */
    public function show(AdminCase $adminCase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\AdminCase $adminCase
     * @return \Illuminate\Http\Response
     */
//    public function edit(AdminCase $adminCase)
    public function edit(Request $request, $id)
    {
//        $mAdminCase = AdminCase::where('CA_CASEID',$CA_CASEID)->first();
//        $mAdminCaseDoc = AdminCaseDoc::where('CC_CASEID', $CA_CASEID)->first();
//        $mAdminCaseDoc = AdminCaseDoc::where(['CC_CASEID' => $CA_CASEID]);
//        $count = DB::table('case_doc')->where('CC_CASEID', $CA_CASEID)->count('CC_CASEID');
        $model = AdminCase::where(['id' => $id])->first();
        $count = DB::table('case_doc')
            ->where('CC_CASEID', $id)
            ->count('CC_CASEID');
        $mAdminCaseDoc = AdminCaseDoc::where(['CC_CASEID' => $id])->first();
        $mUser = User::find($model->CA_RCVBY);
        if ($mUser) {
            $RcvBy = $mUser->name;
        } else {
            $RcvBy = '';
        }
        $data['CA_CMPLCAT'] = $request->old('CA_CMPLCAT') ?? ($model->CA_CMPLCAT ?? null);
        $data['CA_AGAINST_PREMISE'] = $request->old('CA_AGAINST_PREMISE') ?? ($model->CA_AGAINST_PREMISE ?? null);
        $data['CA_ONLINECMPL_PROVIDER'] = $request->old('CA_ONLINECMPL_PROVIDER') ?? ($model->CA_ONLINECMPL_PROVIDER ?? null);
        $data['CA_ONLINECMPL_IND'] = $request->old('CA_ONLINECMPL_IND') ?? ($model->CA_ONLINECMPL_IND ?? null);
        $data['CA_ONLINEADD_IND'] = $request->old('CA_ONLINEADD_IND') ?? ($model->CA_ONLINEADD_IND ?? null);
        $data['againstonlinecomplaint'] =
            ($data['CA_CMPLCAT'] == 'BPGK 19' || $data['CA_AGAINST_PREMISE'] == 'P25')
                ? true : false;
        $data['providercaseno'] =
            (in_array($data['CA_ONLINECMPL_IND'], ['1', 'on']) && $data['againstonlinecomplaint'])
                ? true : false;
        $data['providerurl'] =
            $data['againstonlinecomplaint'] && $data['CA_ONLINECMPL_PROVIDER'] == '999'
                ? true : false;
        $data['againstaddress'] =
            (empty($data['CA_CMPLCAT']) && empty($data['CA_AGAINST_PREMISE']))
            ||
            ($data['CA_CMPLCAT'] != 'BPGK 19' && $data['CA_AGAINST_PREMISE'] != 'P25')
            ||
            (in_array($data['CA_ONLINEADD_IND'], ['1', 'on']) && $data['againstonlinecomplaint'])
                ? true : false;
//        return view('aduan.admin-case.edit',compact('mAdminCase', 'mAdminCaseDoc', 'count', 'RcvBy'));
        return view('aduan.admin-case.editcomplain', compact('model', 'mAdminCaseDoc', 'count', 'RcvBy', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\AdminCase $adminCase
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, AdminCase $adminCase)
    public function update(Request $request, $caseid)
    {
        $request->merge(['CA_ONLINECMPL_AMOUNT' => str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT)]);
        if ($request->CA_CMPLCAT != 'BPGK 19') {
            $request->merge([
                // 'CA_ONLINECMPL_PROVIDER' => NULL,
                // 'CA_ONLINECMPL_URL' => NULL,
                // 'CA_ONLINECMPL_AMOUNT' => NULL,
                'CA_ONLINECMPL_ACCNO' => NULL,
                // 'CA_ONLINECMPL_IND' => NULL,
                // 'CA_ONLINECMPL_CASENO' => NULL,
                // 'CA_ONLINEADD_IND' => NULL
            ]);
//             $this->validate($request, [
//                 'CA_RCVTYP' => 'required',
//                 'CA_DOCNO' => 'required',
//                 'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
//                 'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
//                 'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
//                 'CA_NAME' => 'required',
//                 'CA_STATECD' => 'required',
//                 'CA_DISTCD' => 'required',
//                 'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
//                 'CA_CMPLCAT' => 'required',
//                 'CA_CMPLCD' => 'required',
//                 'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
//                 'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
//                 'CA_AGAINST_PREMISE' => 'required',
//                 'CA_AGAINSTNM' => 'required',
//                 'CA_AGAINSTADD' => 'required',
// //                'CA_AGAINST_POSTCD' => 'min:5|max:5'
//                 'CA_AGAINST_STATECD' => 'required',
//                 'CA_AGAINST_DISTCD' => 'required',
//                 'CA_SUMMARY' => 'required',
//             ],
//                 [
//                     'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
//                     'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
//                     'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                     'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                     'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                     'CA_NAME.required' => 'Ruangan Nama diperlukan.',
//                     'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
//                     'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
//                     'CA_COUNTRYCD.required_if' => 'Ruangan Negara diperlukan.',
//                     'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
//                     'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
//                     'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian (RM) diperlukan.',
//                     'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
//                     'CA_AGAINST_PREMISE.required' => 'Ruangan Jenis Premis diperlukan.',
//                     'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis) diperlukan.',
//                     'CA_AGAINSTADD.required' => 'Ruangan Alamat diperlukan.',
//                     'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
//                     'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan.',
//                     'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
//                 ]);
//         } else {
//             $this->validate($request, [
//                 'CA_RCVTYP' => 'required',
//                 'CA_DOCNO' => 'required',
//                 'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO',
//                 'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL',
//                 'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL',
//                 'CA_NAME' => 'required',
//                 'CA_STATECD' => 'required',
//                 'CA_DISTCD' => 'required',
//                 'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
//                 'CA_CMPLCAT' => 'required',
//                 'CA_CMPLCD' => 'required',
//                 'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
//                 'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19',
//                 'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999',
//                 'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
//                 'CA_ONLINECMPL_BANKCD' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
//                 'CA_ONLINECMPL_ACCNO' => 'required_unless:CA_ONLINECMPL_PYMNTTYP,COD,CA_ONLINECMPL_PYMNTTYP,,CA_CMPLCAT,BPGK 19',
//                 // 'CA_ONLINECMPL_CASENO' => 'required_if:CA_ONLINECMPL_IND,on|required_if:CA_ONLINECMPL_IND,1',
//                 'CA_ONLINECMPL_CASENO' => 'max:80',
// //                'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
//                 'CA_AGAINSTNM' => 'required',
//                 'CA_AGAINSTADD' => 'required_if:CA_ONLINEADD_IND,on',
// //                'CA_AGAINST_POSTCD' => 'required_if:CA_ONLINEADD_IND,on'
//                 'CA_AGAINST_STATECD' => 'required_if:CA_ONLINEADD_IND,on',
//                 'CA_AGAINST_DISTCD' => 'required_if:CA_ONLINEADD_IND,on',
//                 'CA_SUMMARY' => 'required',
//             ],
//                 [
//                     'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
//                     'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
//                     'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                     'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                     'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
//                     'CA_NAME.required' => 'Ruangan Nama diperlukan.',
//                     'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
//                     'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
//                     'CA_COUNTRYCD.required_if' => 'Ruangan Negara diperlukan.',
//                     'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
//                     'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
//                     'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian (RM) diperlukan.',
//                     'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan.',
//                     'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL / ID diperlukan.',
//                     'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan.',
//                     'CA_ONLINECMPL_BANKCD.required_unless' => 'Ruangan Nama Bank diperlukan.',
//                     'CA_ONLINECMPL_ACCNO.required_unless' => 'Ruangan No. Akaun Bank diperlukan.',
//                     'CA_ONLINECMPL_CASENO.required_if' => 'Ruangan No. Aduan Rujukan diperlukan.',
//                     'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat / Premis) diperlukan.',
//                     'CA_AGAINSTADD.required_if' => 'Ruangan Alamat diperlukan.',
//                     'CA_AGAINST_STATECD.required_if' => 'Ruangan Negeri diperlukan.',
//                     'CA_AGAINST_DISTCD.required_if' => 'Ruangan Daerah diperlukan.',
//                     'CA_SUMMARY.required' => 'Ruangan Aduan diperlukan.',
//                 ]);
        }
        $validator = Validator::make($request->all(), [
            'CA_RCVTYP' => 'required',
            'CA_SERVICENO' => 'required_if:CA_RCVTYP,S33',
            'CA_DOCNO' => 'required|max:15',
            'CA_EMAIL' => 'required_without_all:CA_MOBILENO,CA_TELNO|max:60',
            'CA_MOBILENO' => 'required_without_all:CA_TELNO,CA_EMAIL|max:20',
            'CA_TELNO' => 'required_without_all:CA_MOBILENO,CA_EMAIL|max:20',
            'CA_FAXNO' => 'max:20',
            'CA_NAME' => 'required|max:60',
            'CA_AGE' => 'max:20',
            'CA_POSCD' => 'max:5',
            'CA_STATECD' => 'required',
            'CA_DISTCD' => 'required',
            'CA_NATCD' => 'required',
            'CA_COUNTRYCD' => 'required_if:CA_NATCD,0',
            'CA_CMPLCAT' => 'required',
            'CA_CMPLCD' => 'required',
            'CA_CMPLKEYWORD' => 'required_if:CA_CMPLCAT,BPGK 01|required_if:CA_CMPLCAT,BPGK 03',
            'CA_AGAINST_PREMISE' => 'required_unless:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_AMOUNT' => 'required|numeric|max:99999999.99',
            'CA_ONLINECMPL_PROVIDER' => 'required_if:CA_CMPLCAT,BPGK 19|required_if:CA_AGAINST_PREMISE,P25',
            'CA_ONLINECMPL_URL' => 'required_if:CA_ONLINECMPL_PROVIDER,999|max:190',
            'CA_ONLINECMPL_PYMNTTYP' => 'required_if:CA_CMPLCAT,BPGK 19',
            'CA_ONLINECMPL_ACCNO' => 'max:80',
            'CA_ONLINECMPL_CASENO' => 'max:80',
            'CA_AGAINSTNM' => 'required|max:190',
            'CA_AGAINST_TELNO' => 'max:20',
            'CA_AGAINST_MOBILENO' => 'max:20',
            'CA_AGAINST_EMAIL' => 'max:60',
            'CA_AGAINST_FAXNO' => 'max:20',
            'CA_AGAINST_POSTCD' => 'max:5',
            'CA_SUMMARY' => 'required',
        ],
            [
                'CA_RCVTYP.required' => 'Ruangan Cara Penerimaan diperlukan.',
                'CA_SERVICENO.required_if' => 'Ruangan No. Tali Khidmat diperlukan.',
                'CA_DOCNO.required' => 'Ruangan No. Kad Pengenalan/Pasport diperlukan.',
                'CA_EMAIL.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_EMAIL.max' => 'Jumlah Emel mesti tidak melebihi :max aksara.',
                'CA_MOBILENO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_TELNO.required_without_all' => 'Sila isikan salah satu maklumat berikut: Emel / No. Telefon (Bimbit) / No. Telefon (Rumah)',
                'CA_NAME.required' => 'Ruangan Nama diperlukan.',
                'CA_NAME.max' => 'Jumlah Nama mesti tidak melebihi 60 aksara.',
                'CA_CMPLCAT.required' => 'Ruangan Kategori diperlukan.',
                'CA_CMPLCD.required' => 'Ruangan Subkategori diperlukan.',
                'CA_AGAINST_PREMISE.required_unless' => 'Ruangan Jenis Premis diperlukan.',
                'CA_AGAINSTNM.required' => 'Ruangan Nama (Syarikat/Premis/Penjual) diperlukan.',
                'CA_AGAINST_EMAIL.max' => 'Jumlah Emel mesti tidak melebihi :max aksara.',
                'CA_AGAINST_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_AGAINST_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_AGAINSTADD.required' => 'Ruangan Alamat diperlukan.',
                'CA_AGAINST_POSTCD.required_if' => 'Ruangan Poskod diperlukan.',
                'CA_AGAINST_POSTCD.required' => 'Ruangan Poskod diperlukan.',
                'CA_AGAINST_POSTCD.required_unless' => 'Ruangan Poskod diperlukan.',
                'CA_AGAINST_POSTCD.min' => 'Poskod mesti sekurang-kurangnya 5 angka.',
                'CA_SUMMARY.required' => 'Ruangan Keterangan Aduan diperlukan.',
                'CA_NATCD.required' => 'Ruangan Warganegara diperlukan.',
                'CA_POSCD.min' => 'Poskod mesti sekurang-kurangnya 5 angka.',
                'CA_STATECD.required' => 'Ruangan Negeri diperlukan.',
                'CA_DISTCD.required' => 'Ruangan Daerah diperlukan.',
                'CA_COUNTRYCD.required_if' => 'Ruangan Negara Asal diperlukan.',
                'CA_CMPLKEYWORD.required_if' => 'Ruangan Jenis Barangan diperlukan.',
                'CA_ONLINECMPL_PROVIDER.required_if' => 'Ruangan Pembekal Perkhidmatan diperlukan.',
                'CA_ONLINECMPL_URL.required_if' => 'Ruangan Laman Web / URL / ID diperlukan.',
                'CA_ONLINECMPL_AMOUNT.required' => 'Ruangan Jumlah Kerugian (RM) diperlukan.',
                'CA_ONLINECMPL_PYMNTTYP.required_if' => 'Ruangan Cara Pembayaran diperlukan.',
                'CA_ONLINECMPL_BANKCD.required' => 'Ruangan Nama Bank diperlukan.',
                'CA_ONLINECMPL_ACCNO.required' => 'Ruangan No. Akaun Bank diperlukan.',
            ]);
        $validator->sometimes(['CA_AGAINSTADD', 'CA_AGAINST_STATECD', 'CA_AGAINST_DISTCD'], 'required', function ($input) {
            return (empty($input->CA_CMPLCAT) && empty($input->CA_AGAINST_PREMISE))
                ||
                ($input->CA_CMPLCAT != 'BPGK 19' && $input->CA_AGAINST_PREMISE != 'P25')
                ||
                ($input->CA_ONLINEADD_IND == 'on' &&
                    ($input->CA_CMPLCAT == 'BPGK 19' || $input->CA_AGAINST_PREMISE == 'P25')
                );
        });
        $validator->sometimes(['CA_ONLINECMPL_BANKCD', 'CA_ONLINECMPL_ACCNO'], 'required', function ($input) {
            return $input->CA_CMPLCAT == 'BPGK 19'
                && in_array($input->CA_ONLINECMPL_PYMNTTYP, ['CRC', 'OTF', 'CDM']);
        });
        $validator->validate();

        $mAdminCase = AdminCase::find($caseid);
//        $mAdminCase = AdminCase::where(['CA_CASEID' => $caseid])->first();
        $mAdminCase->fill($request->all());
        $DeptCd = explode(' ', $request->CA_CMPLCAT)[0];
        $mAdminCase->CA_DEPTCD = $DeptCd;
        if ($request->CA_ONLINECMPL_AMOUNT == NULL) {
            $mAdminCase->CA_ONLINECMPL_AMOUNT = 0.00;
        } else {
            $mAdminCase->CA_ONLINECMPL_AMOUNT = str_replace(',', '', $request->CA_ONLINECMPL_AMOUNT);
        }
        if ($mAdminCase->CA_NATCD == '1') {
            $mAdminCase->CA_COUNTRYCD = NULL;
        }
//        else if ($mAdminCase->CA_NATCD == '0') {
//            $mAdminCase->CA_POSCD = '';
//            $mAdminCase->CA_STATECD = '';
//            $mAdminCase->CA_DISTCD = '';
//        }
        if (in_array($request->CA_CMPLCAT, ['BPGK 01', 'BPGK 03'])) {
            $mAdminCase->CA_CMPLKEYWORD = $request->CA_CMPLKEYWORD;
            $mAdminCase->CA_ONLINECMPL_IND = NULL;
            $mAdminCase->CA_ONLINECMPL_CASENO = NULL;
            $mAdminCase->CA_ONLINECMPL_URL = NULL;
        } else {
            $mAdminCase->CA_CMPLKEYWORD = NULL;
        }
        // if ($request->CA_CMPLCAT == 'BPGK 19') {
        if ($request->CA_CMPLCAT == 'BPGK 19' || $request->CA_AGAINST_PREMISE == 'P25') {
            if ($request->CA_ONLINECMPL_IND) {
                $mAdminCase->CA_ONLINECMPL_IND = '1';
                $mAdminCase->CA_ONLINECMPL_CASENO = $request->CA_ONLINECMPL_CASENO;
            } else {
                $mAdminCase->CA_ONLINECMPL_IND = '0';
                $mAdminCase->CA_ONLINECMPL_CASENO = NULL;
            }
            if ($request->CA_ONLINEADD_IND) {
                $mAdminCase->CA_ONLINEADD_IND = '1';
            } else {
                $mAdminCase->CA_ONLINEADD_IND = '0';
                // $mAdminCase->CA_AGAINSTADD = NULL;
                // $mAdminCase->CA_AGAINST_STATECD = NULL;
                // $mAdminCase->CA_AGAINST_DISTCD = NULL;
                // $mAdminCase->CA_AGAINST_POSTCD = NULL;
            }
            $mAdminCase->CA_ONLINECMPL_URL = $request->CA_ONLINECMPL_URL;
            if ($request->CA_CMPLCAT == 'BPGK 19') {
                $mAdminCase->CA_ONLINECMPL_PYMNTTYP = $request->CA_ONLINECMPL_PYMNTTYP;
                if (in_array($request->CA_ONLINECMPL_PYMNTTYP, ['CRC', 'OTF', 'CDM'])) {
                    $mAdminCase->CA_ONLINECMPL_BANKCD = $request->CA_ONLINECMPL_BANKCD;
                    $mAdminCase->CA_ONLINECMPL_ACCNO = $request->CA_ONLINECMPL_ACCNO;
                }
            }
            // $mAdminCase->CA_AGAINST_PREMISE = NULL;
        } else {
            $mAdminCase->CA_ONLINECMPL_URL = NULL;
        }
        // if ($request->CA_ONLINEADD_IND == 'on' || $request->CA_CMPLCAT != 'BPGK 19') {
        if (($request->CA_CMPLCAT != 'BPGK 19' && $request->CA_AGAINST_PREMISE != 'P25')
            ||
            ($request->CA_ONLINEADD_IND == 'on' &&
                ($request->CA_CMPLCAT == 'BPGK 19' || $request->CA_AGAINST_PREMISE == 'P25')
            )) {
            $StateCd = $request->CA_AGAINST_STATECD;
            $DistCd = $request->CA_AGAINST_DISTCD;
        } else {
            $StateCd = $mAdminCase->CA_STATECD;
            $DistCd = $mAdminCase->CA_DISTCD;
            $mAdminCase->CA_AGAINSTADD = NULL;
            $mAdminCase->CA_AGAINST_POSTCD = NULL;
            $mAdminCase->CA_AGAINST_STATECD = NULL;
            $mAdminCase->CA_AGAINST_DISTCD = NULL;
        }
        if ($request->CA_ROUTETOHQIND && $request->CA_ROUTETOHQIND == 'on') {
            $mAdminCase->CA_ROUTETOHQIND = '1';
//            $mAdminCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, true);
            $mAdminCase->CA_BRNCD = 'WHQR5';
        } else {
            $mAdminCase->CA_ROUTETOHQIND = '0';
            // $mAdminCase->CA_BRNCD = $this->AduanRoute($StateCd, $DistCd, $DeptCd, false);
            $mAdminCase->CA_BRNCD = CaseInfoRepository::routeBranch($StateCd, $DistCd, $DeptCd, false);
        }
        if ($mAdminCase->save()) {
//            $request->session()->flash('success', 'Aduan telah berjaya dikemaskini');
//            return redirect()->back();
            return redirect()->route('admin-case.attachment', $mAdminCase->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\AdminCase $adminCase
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminCase $adminCase)
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
            $mAdminCase = AdminCase::select(DB::raw('case_info.id, CA_CASEID, CA_SUMMARY, CA_NAME, CA_INVSTS, CA_RCVTYP, CA_RCVDT'))
                ->whereNotIn('CA_RCVTYP', ['S01', 'S02', 'S04', 'S05', 'S13', 'S14', 'S28', 'S29', 'S34', 'S35'])
//                ->where(['CA_CREBY'=>Auth::user()->id])
//                ->orWhere(['CA_INVBY'=>Auth::user()->id])
                ->where(function ($query) {
                    $query->where('CA_CREBY', '=', Auth::user()->id)
                        ->orWhere('CA_INVBY', '=', Auth::user()->id);
                })
                ->orderBy('CA_RCVDT', 'desc');

            $datatables = DataTables::of($mAdminCase)
                ->addIndexColumn()
                ->editColumn('CA_CASEID', function (AdminCase $penugasan) {
                    return view('aduan.tugas.show_summary_link', compact('penugasan'))->render();
                })
                ->editColumn('CA_SUMMARY', function (AdminCase $adminCase) {
                    if ($adminCase->CA_SUMMARY != '')
                        return implode(' ', array_slice(explode(' ', $adminCase->CA_SUMMARY), 0, 7)) . ' ...';
                    // return substr($adminCase->CA_SUMMARY, 0, 20) . '...';
                    else
                        return '';
                })
                ->editColumn('CA_INVSTS', function (AdminCase $adminCase) {
                    if ($adminCase->CA_INVSTS != '')
                        return $adminCase->statusAduan->descr;
                    else
                        return '';
                })
                ->editColumn('CA_CASESTS', function (AdminCase $adminCase) {
                    if ($adminCase->CA_CASESTS != '')
                        return $adminCase->statusPerkembangan->descr;
                    else
                        return '';
                })
                ->editColumn('CA_RCVDT', function (AdminCase $adminCase) {
                    return $adminCase->CA_RCVDT ? with(new Carbon($adminCase->CA_RCVDT))->format('d-m-Y h:i A') : '';
                })
                ->addColumn('tempoh', function (AdminCase $adminCase) use ($TempohPertama, $TempohKedua, $TempohKetiga) {
                    if ($adminCase->CA_RCVDT && $adminCase->CA_INVSTS != '10') {
                        $totalDuration = $adminCase->getduration($adminCase->CA_RCVDT, $adminCase->CA_CASEID);
                        if ($totalDuration >= 0 && $totalDuration <= $TempohPertama->code)
                            return '<div style="background-color:#3F6; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                        else if ($totalDuration > $TempohPertama->code && $totalDuration <= $TempohKedua->code)
                            return '<div style="background-color:#FF3;" align="center"><strong>' . $totalDuration . '</strong></div>';
                        else if ($totalDuration > $TempohKedua->code && $totalDuration <= $TempohKetiga->code)
                            return '<div style="background-color:#F0F; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                        else if ($totalDuration > $TempohKetiga->code)
                            return '<div style="background-color:#F00; color: white;" align="center"><strong>' . $totalDuration . '</strong></div>';
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', function (AdminCase $adminCase) {
                    return view('aduan.admin-case.actionbutton', compact('adminCase'))->render();
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
                    if ($request->has('CA_INVSTS')) {
                        $query->where('CA_INVSTS', $request->get('CA_INVSTS'));
                    }
                    if ($request->has('sa')) {
                        if ($request->get('sa') == '0') {
                            $query->where(DB::raw("substr(CA_CASEID, 1, 1)"), '=', '0')
                                ->where(DB::raw("substr(CA_CASEID, 2, 1)"), '<>', '0');
                        } elseif ($request->get('sa') == '00') {
                            $query->where(DB::raw("substr(CA_CASEID, 1, 2)"), '=', '00')
                                ->where(DB::raw("substr(CA_CASEID, 3, 1)"), '<>', '0');
                        } elseif ($request->get('sa') == '000') {
                            $query->where(DB::raw("substr(CA_CASEID, 1, 3)"), '=', '000');
                        } elseif ($request->get('sa') == 'SAS') {
                            $query->where(DB::raw("substr(CA_CASEID, 1, 3)"), '=', 'SAS');
                        }
                    }
                });
        }
        return $datatables->make(true);
    }

//    public function getdatatableuser(Datatables $datatables, Request $request) {
    public function getdatatableuser(Request $request)
    {
        $mUser = User::where('user_cat', '1');

        $datatables = Datatables::of($mUser)
            ->addIndexColumn()
            ->editColumn('state_cd', function (User $user) {
                if ($user->state_cd != '')
                    return $user->Negeri->descr;
                else
                    return '';
            })
            ->editColumn('brn_cd', function (User $user) {
                if ($user->brn_cd != '')
                    return $user->Cawangan->BR_BRNNM;
                else
                    return '';
            })
            ->addColumn('action', '
            <a class="btn btn-xs btn-primary" onclick="myFunction({{ $id }})"><i class="fa fa-arrow-down"></i></a>
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
            });

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

    public function GetUserDetail($id)
    {
        $UserDetail = DB::table('sys_users')
            ->where('id', $id)
            ->pluck('id', 'name');
        return json_encode($UserDetail);
    }

    public function GetDistList($state_cd = null)
    {
        $mDistList = [];

        if ($state_cd) {
            $mDistList = DB::table('sys_ref')
                ->where('cat', '18')
                ->where('code', 'like', $state_cd . '%')
                ->orderBy('sort')
                ->pluck('code', 'descr');

            if (count($mDistList) > 1) {
                $mDistList->prepend('', '-- SILA PILIH --');
            }
        }

        return json_encode($mDistList);
    }

    public function GetCmplList($cat_cd)
    {
        $mCatList = DB::table('sys_ref')
            ->where('cat', '634')
            ->where('code', 'like', "$cat_cd%")
            ->where('status', '1')
            ->orderBy('sort', 'asc')
//            ->orderBy('descr', 'asc')
            ->pluck('code', 'descr');
//            ->prepend('', '-- SILA PILIH --');
        if (count($mCatList) != 1) {
            $mCatList->prepend('0', '-- SILA PILIH --');
        }
        return json_encode($mCatList);
    }

    public function getattachment($CASEID)
    {
//        CC_DOCATTCHID
        $mAdminCaseDoc = AdminCaseDoc::where('CC_CASEID', $CASEID);
//        $mAdminCase = AdminCase::where('CA_CASEID', $CASEID)->first();
//        $mAdminCase = AdminCase::where('CA_CASEID', $CASEID);
        $datatables = Datatables::of($mAdminCaseDoc)
            ->addIndexColumn()
            ->editColumn('id', function (AdminCaseDoc $adminCaseDoc) {
                if ($adminCaseDoc->CC_DOCATTCHID != '')
                    return $adminCaseDoc->attachment->id;
                else
                    return '';
            })
            ->editColumn('doc_title', function (AdminCaseDoc $adminCaseDoc) {
                if ($adminCaseDoc->CC_DOCATTCHID != '')
                    return $adminCaseDoc->attachment->doc_title;
                else
                    return '';
            })
            ->editColumn('file_name_sys', function (AdminCaseDoc $adminCaseDoc) {
                if ($adminCaseDoc->CC_DOCATTCHID != '')
                    return '<a href=' . url("uploads/{$adminCaseDoc->attachment->file_name_sys}") . ' target="_blank">' . $adminCaseDoc->attachment->file_name . '</a>';
                else
                    return '';
            })
            ->rawColumns(['file_name_sys', 'action'])
            ->addColumn('action',
//                        <a href="#caseAttachmenteditbutton" class="btn btn-xs btn-primary" data-toggle="modal" data-placement="right" title="Kemaskini" id="ggfc">
//                        <i class="fa fa-pencil"></i>
//                    </a>
                '<a onclick="caseattachmenteditbutton({{ $CC_DOCATTCHID }})" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="right" title="Kemaskini">
                <i class="fa fa-pencil"></i></a>
                {!! Form::open(["url" => "admin-case/destroyattachment/$CC_CASEID/$CC_DOCATTCHID", "method" => "DELETE", "id"=>"form-delete", "style"=>"display:inline"]) !!}
                {!! Form::button("<i class=\'fa fa-trash\'></i>", ["type" => "submit", "class" => "btn btn-danger btn-xs", "data-toggle"=>"tooltip", "data-placement"=>"right", "title"=>"Hapus", "onclick" => "return confirm(\'Anda pasti untuk hapuskan rekod ini?\')"] ) !!}
                {!! Form::close() !!}
            ');

        return $datatables->make(true);
    }

    public function getdocdetail($id)
    {
        $docDetail = DB::table('doc_attach')
            ->where('id', $id)
            ->pluck('id', 'doc_title');
        return json_encode($docDetail);
    }

    public function createattachment(Request $request)
    {
        $file = $request->file('file_upload');
        $date = date('Ymdhis');
        $user_id = Auth::user()->id;
        $filename = $user_id . '_' . $date . '.' . $file->getClientOriginalExtension();

        $uploadPath = 'public';
//        $file->move($uploadPath, $filename);
        $file->storeAs($uploadPath, $filename);

        $mAttachment = new Attachment;
        $mAttachment->doc_title = $request->input('doc_title');
        $mAttachment->file_name = $file->getClientOriginalName();
        $mAttachment->file_name_sys = $filename;
        if ($mAttachment->save()) {
            $mAdminCaseDoc = new AdminCaseDoc;
            $mAdminCaseDoc->CC_DOCATTCHID = $mAttachment->id;
            $mAdminCaseDoc->CC_CASEID = $request->CA_CASEID;
            if ($mAdminCaseDoc->save()) {
//                $request->session()->flash('success', 'Bahan Bukti telah berjaya ditambah');
                return redirect('/admin-case/edit/' . $request->CA_CASEID . '#case-attachment');
            }
        }
    }

    public function getdatatabletransaction($CASEID)
    {
        $mAdminCaseDetail = AdminCaseDetail::where(['CD_CASEID' => $CASEID])->orderBy('CD_CREDT', 'ASC');
        $datatables = Datatables::of($mAdminCaseDetail)
            ->addIndexColumn()
            ->editColumn('CD_INVSTS', function (AdminCaseDetail $adminCaseDetail) {
                if ($adminCaseDetail->CD_INVSTS != '')
                    return $adminCaseDetail->statusaduan->descr;
                else
                    return '';
            })
            ->editColumn('CD_ACTFROM', function (AdminCaseDetail $adminCaseDetail) {
//                if($adminCaseDetail->CD_ACTFROM != '')
//                    return $adminCaseDetail->actfrom->name;
//                else
//                    return '';
                if ($adminCaseDetail->CD_ACTFROM != '') {
                    if ($adminCaseDetail->actfrom) {
                        return $adminCaseDetail->actfrom->name;
                    } else {
                        return $adminCaseDetail->CD_ACTFROM;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CD_ACTTO', function (AdminCaseDetail $adminCaseDetail) {
//                if($adminCaseDetail->CD_ACTTO != '')
//                    return $adminCaseDetail->actto->name;
//                else
//                    return '';
                if ($adminCaseDetail->CD_ACTTO != '') {
                    if ($adminCaseDetail->actto) {
                        return $adminCaseDetail->actto->name;
                    } else {
                        return $adminCaseDetail->CD_ACTTO;
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('CD_CREDT', function (AdminCaseDetail $adminCaseDetail) {
                return $adminCaseDetail->CD_CREDT ? with(new Carbon($adminCaseDetail->CD_CREDT))->format('d-m-Y h:i A') : '';
            })
            ->editColumn('CD_DESC', function (AdminCaseDetail $adminCaseDetail) {
                if ($adminCaseDetail->CD_CASEID != '')
                    return $adminCaseDetail->CD_DESC;
                else
                    return '';
            });
        return $datatables->make(true);
    }

    public function GetDataTableAttachment($CASEID)
    {
        $mAdminDoc = CallCenterCaseDoc::where('CC_CASEID', $CASEID);

        $datatables = Datatables::of($mAdminDoc)
            ->addIndexColumn()
//                ->editColumn('CC_IMG_NAME', function(CallCenterCaseDoc $CallCenterCaseDoc) {
//                    if($CallCenterCaseDoc->CC_DOCATTCHID != '')
//                    return $CallCenterCaseDoc->attachment->CC_IMG_NAME;
//                    else
//                    return '';
//                })
            ->editColumn('CC_IMG_NAME', function (AdminCaseDoc $AdminCaseDoc) {
                if ($AdminCaseDoc->CC_IMG_NAME != '')
                    return '<a href=' . Storage::disk('bahan')->url($AdminCaseDoc->CC_PATH . $AdminCaseDoc->CC_IMG) . ' target="_blank">' . $AdminCaseDoc->CC_IMG_NAME . '</a>';
                else
                    return '';
            })
//                
            ->addColumn('action', function (AdminCaseDoc $AdminCaseDoc) {
                return view('aduan.admin.case.AttachmentActionBtn', compact('AdminCaseDoc'))->render();
            })
            ->rawColumns(['CC_IMG_NAME', 'action']);

        return $datatables->make(true);
    }

    public function AjaxValidateCreateAttachment(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'doc_title' => 'required',
                'file' => 'required'
            ],
            [
                'doc_title.required' => 'Ruangan Nama Fail diperlukan.',
                'file.required' => 'Ruangan Fail diperlukan.',
            ]);

        if ($validator->fails()) {
            return response()->json(['fails' => $validator->getMessageBag()]);
        } else {
            return response()->json(['success']);
        }
    }

    public function StoreAttachment(Request $request)
    {
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

            if ($file->getClientSize() > 2000000) // if file size lebih 2mb
            {
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

    /**
     * @param $CA_CASEID
     * @param $url
     * @throws \Exception
     */
    public function getAttachmentByUrl($CA_CASEID, $url)
    {
//        $url = "http://www.google.co.in/intl/en_com/images/srpr/logo1w.png";
        $directory = '/' . date('Y') . '/' . date('m') . '/';
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        $filename = bin2hex(random_bytes(10)) . $name;

        dump($filename);

        Storage::disk('bahan')->put($directory . $filename, $contents);

        $mAttachment = new \App\Aduan\PublicCaseDoc();
        $mAttachment->CC_CASEID = $CA_CASEID;
        $mAttachment->CC_PATH = Storage::disk('bahan')->url($directory);
        $mAttachment->CC_IMG = $filename;
        $mAttachment->CC_IMG_NAME = $name;
        $mAttachment->CC_REMARKS = '';
        $mAttachment->save();

        return;
    }

    public function updateattachment(Request $request)
    {
        $mAttachment = Attachment::find($request->doc_title_id);

        $date = date('Ymdhis');
        $user_id = Auth::user()->id;
        $file = $request->file('file_upload');

        if ($file) {
            Storage::delete('public/' . $mAttachment->file_name_sys);
            $filename = $user_id . '_' . $date . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $filename);
            $mAttachment->file_name = $file->getClientOriginalName();
            $mAttachment->file_name_sys = $filename;
        }
        $mAttachment->doc_title = $request->doc_title;
        if ($mAttachment->save()) {
//            $request->session()->flash('success', 'Bahan Bukti telah berjaya dikemaskini');
            return redirect('/admin-case/edit/' . $request->CA_CASEID . '#case-attachment');
        }
    }

    public function destroyattachment(Request $request, $caseid, $docattachid)
    {
        $mAttachment = Attachment::find($docattachid);
        $path = 'public/' . $mAttachment->file_name_sys;
        Storage::delete($path);
        $mAdminCaseDoc = AdminCaseDoc::where(['CC_CASEID' => $caseid, 'CC_DOCATTCHID' => $docattachid]);
        if ($mAdminCaseDoc->delete()) {
            if ($mAttachment->delete()) {
//                $request->session()->flash('success', 'Bahan Bukti telah berjaya dihapus');
                return redirect('/admin-case/edit/' . $caseid . '#case-attachment');
            }
        }
    }

    public function AduanRoute($StateCd, $DistCd, $DeptCd, $RouteToHQ = false)
    {
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
            if ($RouteToHQ) {
                return 'WHQR5';
            } else {
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

    public function pdf($id)
    {
        $mAdminCase = AdminCase::find($id);
        $data = [
            'mAdminCase' => $mAdminCase,
        ];
        $pdf = PDF::loadView('aduan.admin-case.pdf', $data);
        return $pdf->stream('document.pdf');
    }

    public function attachment($id)
    {
        $model = AdminCase::find($id);
        $countDoc = DB::table('case_doc')
            ->where('CC_CASEID', $id)
            ->count('CC_CASEID');
        $mAdminCaseDoc = AdminCaseDoc::where(['CC_CASEID' => $id])->first();
        return view('aduan.admin-case.attachment', compact('model', 'countDoc', 'mAdminCaseDoc'));
    }

    public function preview($id)
    {
        $model = AdminCase::find($id);
        $mAdminCaseDoc = AdminCaseDoc::where(['CC_CASEID' => $id])->get();
        $mUser = User::find($model->CA_RCVBY);
        if ($mUser) {
            $RcvBy = $mUser->name;
        } else {
            $RcvBy = '';
        }
        return view('aduan.admin-case.preview', compact('model', 'mAdminCaseDoc', 'RcvBy'));
    }

    public function submit(Request $Request, $id)
    {
        $mAdminCase = AdminCase::find($id);
        if ($mAdminCase->CA_INVSTS == '10') {
            if ($mAdminCase->CA_RCVTYP == 'S37') {
                $rule = '000';
            } else {
                $rule = '0';
            }
            $mAdminCase->CA_CASEID = RunnerRepository::generateAppNumber('X', date('y'), $rule);
            $mAdminCase->CA_INVSTS = '1'; //Aduan Diterima
            // $mAdminCase->CA_RCVDT = Carbon::now();
            if ($mAdminCase->save()) {
                AdminCaseDoc::where('CC_CASEID', $id)->update(['CC_CASEID' => $mAdminCase->CA_CASEID]);
                AdminCaseDetail::where(['CD_CASEID' => $id, 'CD_CURSTS' => '1'])->update(['CD_CURSTS' => '0']);
                AdminCaseDetail::where('CD_CASEID', $id)->update(['CD_CASEID' => $mAdminCase->CA_CASEID]);
                $date = date('YmdHis');
                $userid = Auth::user()->id;
                $mSuratPublic = Letter::where(['letter_type' => '01', 'letter_code' => $mAdminCase->CA_INVSTS])->first();
                $ContentSuratPublic = $mSuratPublic->header . $mSuratPublic->body . $mSuratPublic->footer;

                if ($mAdminCase->CA_STATECD != '') {
                    $StateNm = DB::table('sys_ref')->select('descr')->where(['cat' => '17', 'code' => $mAdminCase->CA_STATECD])->first();
                    if (!$StateNm) {
                        $CA_STATECD = $mAdminCase->CA_STATECD;
                    } else {
                        $CA_STATECD = $StateNm->descr;
                    }
                } else {
                    $CA_STATECD = '';
                }
                if ($mAdminCase->CA_DISTCD != '') {
                    $DestrictNm = DB::table('sys_ref')->select('descr')->where(['cat' => '18', 'code' => $mAdminCase->CA_DISTCD])->first();
                    if (!$DestrictNm) {
                        $CA_DISTCD = $mAdminCase->CA_DISTCD;
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
                $replacements[1] = $mAdminCase->CA_NAME;
                $replacements[2] = $mAdminCase->CA_ADDR != '' ? $mAdminCase->CA_ADDR : '';
                $replacements[3] = $mAdminCase->CA_POSCD != '' ? $mAdminCase->CA_POSCD : '';
                $replacements[4] = $CA_DISTCD;
                $replacements[5] = $CA_STATECD;
                $replacements[6] = $mAdminCase->CA_CASEID;
                $replacements[7] = date('d/m/Y', strtotime($mAdminCase->CA_RCVDT));
                $replacements[8] = date('h:i A', strtotime($mAdminCase->CA_RCVDT));

                $ContentReplace = preg_replace($patterns, $replacements, urldecode($ContentSuratPublic));
                $arr_rep = array("#", "#");
                $ContentFinal = str_replace($arr_rep, "", $ContentReplace);
                $pdf = PDF::loadHTML($ContentFinal); // Generate PDF from HTML


                $filename = $userid . '_' . $mAdminCase->CA_CASEID . '_' . $date . '.pdf';
                Storage::disk('letter')->put($filename, $pdf->output()); // Store PDF to storage

                $mAttachment = new Attachment();
                $mAttachment->doc_title = $mSuratPublic->title;
                $mAttachment->file_name = $mSuratPublic->title;
                $mAttachment->file_name_sys = $filename;
                if ($mAttachment->save()) {
                    $mAdminCaseDetail = new AdminCaseDetail();
                    $mAdminCaseDetail->fill([
                        'CD_CASEID' => $mAdminCase->CA_CASEID,
                        'CD_TYPE' => 'D',
                        'CD_ACTTYPE' => 'NEW',
                        'CD_INVSTS' => '1',
                        'CD_CASESTS' => '1',
                        'CD_CURSTS' => '1',
                        'CD_DOCATTCHID_PUBLIC' => $mAttachment->id,
                    ]);
                    if ($mAdminCaseDetail->save()) {
                        if ($mAdminCase->CA_EMAIL != '') {
//                            Mail::to($mAdminCase->CA_EMAIL)->queue(new AduanTerimaAdmin($mAdminCase)); // Send pakai queue
                            Mail::to($mAdminCase->CA_EMAIL)->send(new AduanTerimaAdmin($mAdminCase)); // Send biasa
                        }

                        // For feedback module usage.
                        // Send template when data is successfully submitted
                        if (in_array($mAdminCase->CA_RCVTYP, ['S37', 'S38'])) { // S37 - whatsapp; S38 - Telegram
                            $message = 'Aduan anda telah didaftarkan ke dalam Sistem e-Aduan KPDNHEP.
No. Aduan: *' . $mAdminCase->CA_CASEID . '*

Semakan Aduan boleh dibuat melalui:
a) Portal eAduan https://eaduan.kpdnhep.gov.my 
b) Aplikasi Ez ADU KPDNHEP (Android dan IOS)
c) Call Center : 1800 – 886 - 800
d) Emel ke e-aduan@kpdnhep.gov.my 

**Pendaftaran menggunakan Nama dan No. K/P diperlukan untuk semakan melalui Aplikasi Ez ADU.

Sekian, terima kasih
KPDNHEP';
                            if ($mAdminCase->CA_RCVTYP == 'S37') {
                                WaboxappRepository::send($mAdminCase->CA_TELNO, $message);
                            }

                            if ($mAdminCase->CA_RCVTYP == 'S38') {
                                $feed_telegram = FeedTelegram::find($mAdminCase->feedback_module_id);
                                TelegramRepository::sendMessageToReceiver($feed_telegram->user_id, $message);
                            }
                        }
                        // if whatsapp then send the case number to user
                        $Request->session()->flash(
                            'success', 'Aduan anda telah diterima. No. Aduan: ' . $mAdminCase->CA_CASEID . '.'
                        );
//                        return redirect()->route('admin-case.index');
                    }
                }

            }
        } else {
            $Request->session()->flash(
                'warning', 'Harap maaf, Aduan anda telah <b>diterima</b>. <br />No. Aduan: ' . $mAdminCase->CA_CASEID
            );
        }
        return redirect()->route('admin-case.index');
    }

    public function check($CASEID)
    {
        $model = AdminCase::where(['CA_CASEID' => $CASEID])->first();
        $mAdminCaseDoc = AdminCaseDoc::where(['CC_CASEID' => $CASEID, 'CC_IMG_CAT' => 1])->get();
        $mUser = User::find($model->CA_RCVBY);
        if ($mUser) {
            $RcvBy = $mUser->name;
        } else {
            $RcvBy = '';
        }
        return view('aduan.admin-case.check', compact('model', 'mAdminCaseDoc', 'RcvBy'));
    }

    public function ShowSummary($CASEID)
    {
        $model = AdminCase::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = AdminCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = AdminCaseDoc::where(['CC_CASEID' => $CASEID])->get();
        return view('aduan.admin-case.show_summary_modal', compact('model', 'trnsksi', 'img'));
    }

    public function PrintSummary($CASEID)
    {
        $model = AdminCase::where(['CA_CASEID' => $CASEID])->first();
        $trnsksi = AdminCaseDetail::where(['CD_CASEID' => $CASEID])->get();
        $img = AdminCaseDoc::where(['CC_CASEID' => $CASEID])->get();
        $GeneratePdfSummary = PDF::loadView('aduan.admin-case.show_summary_modal', compact('model', 'trnsksi', 'img'), [], ['default_font_size' => 7]);
        $GeneratePdfSummary->stream();
    }

    public function SaluranAduan()
    {

//       $model = AdminCase::where(DB::raw("substr('CA_CASEID', 1, 1)"),'=',0)
//               ->where(DB::raw("substr('CA_CASEID', 2, 1)"),'<>',0)
////               ->first();
//        $model = AdminCase::where(DB::raw("substr(CA_CASEID, 1, 1)"),'=',0)
//               ->where(DB::raw("substr(CA_CASEID, 2, 1)"),'<>',0)
//               ->first();
//        $model = AdminCase::where( DB::raw("substr(CA_CASEID, 1, 2)"),'=','00')
//               ->where(DB::raw("substr(CA_CASEID, 3, 1)"),'<>',0)
//               ->first();
//        $model = AdminCase::where( DB::raw("substr(CA_CASEID, 1, 3)"),'=','SAS')->get();
//               ->where(DB::raw("substr(CA_CASEID, 3, 1)"),'<>',0)
//               ->first();
//       $model = AdminCase::whereRaw("substr('CA_CASEID', 1, 1)='0'")->first();
//       $model = AdminCase::where('CA_CASEID', 'like', "%0%")->first();
//        dd($model);
    }

    public function delete(Request $request, $id)
    {
        $mAdminCase = AdminCase::where('id', $id)->whereNull('CA_CASEID')->first();
        $mAdminCaseDetail = AdminCaseDetail::where('CD_CASEID', $id)->first();
        $mAdminCaseDoc = AdminCaseDoc::where('CC_CASEID', $id)->get();

        if (!empty($mAdminCaseDoc)) {
            foreach ($mAdminCaseDoc as $doc) {
                Storage::delete('public/' . $doc->CC_PATH . $doc->CC_IMG);
                $doc->delete();
            }
        }

        if (!empty($mAdminCaseDetail)) {
            $mAdminCaseDetail->delete();
        }

        if (!empty($mAdminCase)) {
            $mAdminCase->delete();
        }

        session()->flash('success', 'Aduan berstatus deraf telah berjaya dihapus');
        return redirect()->back();
    }

    public function getcmplkeywordlist($cat_cd)
    {
        if ($cat_cd == 'BPGK 03') {
            $mRefList = DB::table('sys_ref')
                ->where('cat', '1051')
                ->whereNotIn('code', ['101', '102', '103'])
                ->where('status', '1')
                ->orderBy('sort', 'asc')
                ->pluck('code', 'descr');
        } else {
            $mRefList = DB::table('sys_ref')
                ->where('cat', '1051')
                ->where('status', '1')
                ->orderBy('sort', 'asc')
                ->pluck('code', 'descr');
        }
        return json_encode($mRefList);
    }
}
