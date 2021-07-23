<?php

namespace App\Http\Controllers;

use App;
use App\KodMapping;
use App\Models\Cases\CaseEnquiryPaper;
use App\Notifications\SendActivationEmail;
use App\Repositories\MyIdentityRepository;
use App\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SoapClient;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        // something here
    }

    public function __construct()
    {
        $this->middleware('locale');
        $this->middleware('auth', ['only' => ['dashboard']]);
    }

    public function userLogin()
    {
        return view('login.user_login');
    }

    public function webhook()
    {
        if (isset($_POST)) {
            $dir = public_path();
//            dd($dir);
            shell_exec("cd $dir && nohup bash $dir/hook.sh >> hook.log 2> hook_err.log &> /dev/null &");
            echo 'Done';
            exit();
        }
    }

    public function userAuth(Request $request)
    {
        if (strlen(Request('password')) >= 8) {
            if (Auth::attempt(['username' => Request('username'), 'password' => Request('password'), 'user_cat' => '2', 'status' => '1'])) {
                return redirect()->route('dashboard')->with('status', 'popupmodal');;
            } else {
                $user = \App\User::where(['username' => Request('username'), 'user_cat' => '2'])->first();

                if ($user && \Hash::check($request->password, $user->password) && $user->status != 1) {
                    return redirect()->back()
                        ->withInput($request->only('username'))
                        ->withErrors([
                            'username' => 'Your account is not active.',
                        ]);
                } else {
                    return redirect()->back()
                        ->withInput($request->only('username'))
                        ->withErrors([
                            'username' => trans('auth.failed'),
                        ]);
                }
            }
        } else {
            return redirect()->back()
                ->withInput($request->only('username'));
        }
    }

    public function adminLogin()
    {
        return view('login.admin_login');
    }

    public function adminAuth(Request $request)
    {
        if (Auth::attempt(['username' => Request('username'), 'password' => Request('password'), 'user_cat' => '1', 'status' => '1'])) {
            return redirect()->route('dashboard')->with('status', 'popupmodal');
        } else {
            $user = \App\User::where(['username' => Request('username'), 'user_cat' => '1'])->first();

            if ($user && \Hash::check($request->password, $user->password) && $user->status != 1) {
                return redirect()->back()
                    ->withInput($request->only('username'))
                    ->withErrors([
                        'username' => 'Your account is not active.',
                    ]);
            } else {
                return redirect()->back()
                    ->withInput($request->only('username'))
                    ->withErrors([
                        'username' => trans('auth.failed'),
                    ]);
            }
        }
    }

    public function userRegister()
    {
        if (config('app.locale') == 'ms') {
            $placeholder = "Nama seperti di dalam kad pengenalan";
        } else {
            $placeholder = "Name as in passport";
        }
        return view('login.user_register', compact('placeholder'));
    }

    public function userSubmit(Request $request)
    {
        $error = '';
        $StatusPengadu = '';
        $User = new User();
        $UserAddress = '';
        $UserAge = '';
        $UserGender = '';
        $UserCountry = '';
        $UserPostcode = '';
        $UserCity = '';
        $UserState = '';
        if ($request->mobile == 1) { // Mobile
            if ($request->citizen == '0') { // Bukan warganegara
                $this->validate($request, [
                    'email' => 'required|string|email|max:255|unique:sys_users',
                    'passport' => 'required|unique:sys_users,username,1,user_cat',
                ],
                    [
                        'email.unique' => 'The email has already been taken.',
                        'passport.unique' => 'The passport has already been taken.'
                    ]);
            } else {
                $this->validate($request, [
                    'email' => 'required|string|email|max:255|unique:sys_users',
                    'username' => 'required|max:12|unique:sys_users,icnew,1,user_cat',
                ],
                    [
                        'email.unique' => 'Emel telah wujud.',
                        'username.unique' => 'No. Kad Pengenalan (Baru) telah wujud.'
                    ]);
            }
        } else { // Web
            if ($request->citizen == '0') { // Bukan warganegara
                $this->validate($request, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:sys_users',
                    // 'mobile_no' => 'required|regex:/(01)[0-9]/|min:10|max:11',
                    'mobile_no' => 'required|min:10|max:15',
//                    'username' => 'required|unique:sys_users,username,1,user_cat',
                    'passport' => 'required|unique:sys_users,username,1,user_cat',
                    'gender' => 'required',
                    'age' => 'required',
                    'address' => 'required',
//                    'postcode' => 'required|max:5',
                    'state_cd' => 'required',
                    'distrinct_cd' => 'required',
                    'ctry_cd' => 'required',
//                    'password' => 'required|string|min:6|confirmed',
                    'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
//                    'password_confirmation' => 'required|string|min:6|same:password',
                    'password_confirmation' => 'required|string|min:8|same:password',
//                    'g-recaptcha-response' => 'required|captcha',
                    'captcha' => 'required|captcha',
                ]);
            } else {
                $this->validate($request, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:sys_users',
                    // 'mobile_no' => 'required|regex:/(01)[0-9]/|min:10|max:11',
                    'mobile_no' => 'required|regex:/(01)[0-9]/|min:10|max:12',
//                    'username' => 'required|max:12|min:12|unique:sys_users,icnew,1,user_cat',
                    'username' => 'required|max:12|unique:sys_users,icnew,1,user_cat',
//                    'usernameicno' => 'required|digits:12|unique:sys_users,icnew,1,user_cat',
//                    'password' => 'required|string|min:6|confirmed',
                    'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
//                    'password_confirmation' => 'required|string|min:6|same:password',
                    'password_confirmation' => 'required|string|min:8|same:password',
//                    'g-recaptcha-response' => 'required|captcha',
                    'captcha' => 'required|captcha',
                ]);
            }
        }
//        $User = new User();
        $User->fill($request->all());
        if ($request->citizen == '1') {
            $AgencyCode = "110012";
            $BranchCode = "eAduan";
            $TransactionCode = "T7"; // T2 - Admin Page, T7 - Public Page
            $RequestDateTime = date('Y-m-d H:i:s'); //date("Y-m-d") . "T" . date("H:i:s");
            $RequestIndicator = "A";
            $UserId = $request->username;
            $ICNumber = $request->username;
            $Nama_Pengadu = $request->name;

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
//                    if($response->ReplyIndicator != ''){
                /**
                 * iff has ReplyIndicator and ReplyIndicator is 1 or 2
                 */
                if (isset($response->ReplyIndicator) && in_array($response->ReplyIndicator, [1, 2])) {
                    $arrData = $response;
                    $Name = $response->Name;
                    $matchname = (strtoupper(implode(explode(' ', $Name))) == strtoupper(implode(explode(' ', $Nama_Pengadu)))) ? true : false;
                    // Umur
                    if ($matchname) {
                        $DateOfBirth = $response->DateOfBirth ?? null;
                        // $Age = Carbon::createFromDate(date('Y', strtotime($response->DateOfBirth)), 5, 21)->age;
                        $Age = !empty($DateOfBirth) ? Carbon::createFromDate(date('Y', strtotime($DateOfBirth)), 5, 21)->age : null;
                        $arrData->Age = $Age;
                        switch ($Age) {
                            case null:
                                $arrData->Age = null;
                                break;
                            case ($Age <= 18):
                                $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat' => '309', 'code' => '100'])->first();
                                $arrData->Age = '100';
                                break;
                            case ($Age >= 19 && $Age <= 25):
                                $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat' => '309', 'code' => '101'])->first();
                                $arrData->Age = '101';
                                break;
                            case ($Age >= 26 && $Age <= 40):
                                $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat' => '309', 'code' => '102'])->first();
                                $arrData->Age = '102';
                                break;
                            case ($Age >= 41 && $Age <= 55):
                                $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat' => '309', 'code' => '103'])->first();
                                $arrData->Age = '103';
                                break;
                            case ($Age >= 56):
                                $mRefAge = DB::table('sys_ref')->select('descr')->where(['cat' => '309', 'code' => '104'])->first();
                                $arrData->Age = '104';
                                break;
                            default:
                                $arrData->Age = '0';
                                break;
                        }
                        // $arrData->Gdr = ($response->Gender == '0') ? '0' : ($response->Gender == 'L' ? 'M' : 'F'); // Jantina
                        $Gender = $response->Gender ?? null; // Jantina
                        switch ($Gender) {
                            case '0':
                                $arrData->Gdr = '0';
                                break;
                            case 'L': // LELAKI
                                $arrData->Gdr = 'M';
                                break;
                            case 'P': // PEREMPUAN
                                $arrData->Gdr = 'F';
                                break;
                            case 'R': // RAGU
                                $arrData->Gdr = 'R';
                                break;
                            default:
                                $arrData->Gdr = null;
                                break;
                        }
                        $ResidentialStatus = isset($response->ResidentialStatus) ? trim($response->ResidentialStatus) : null;
                        // Warganegara
                        // if ($response->ResidentialStatus == 'B' ||
                        //     $response->ResidentialStatus == 'C' ||
                        //     $response->ResidentialStatus == 'M' ||
                        //     $response->ResidentialStatus == 'P' ||
                        //     $response->ResidentialStatus == '') {
                        if ($ResidentialStatus == 'B' ||
                            $ResidentialStatus == 'C' ||
                            $ResidentialStatus == 'M' ||
                            $ResidentialStatus == 'P' ||
                            $ResidentialStatus == ''
                        ) { // Warganegara dan Pemastautin Tetap
                            $arrData->Warganegara = 'malaysia';
                            $RecordStatus = isset($response->RecordStatus) ? trim($response->RecordStatus) : null;
                            // if ($response->RecordStatus == '2' ||
                            //     $response->RecordStatus == 'B' ||
                            //     $response->RecordStatus == 'H') { // Sudah Meninggal
                            if (in_array($RecordStatus, ['2', 'B', 'H'])) { // Sudah Meninggal
//                                        $arrData->error = "Individu direkodkan telah meninggal dunia";
                                if (config('app.locale') == 'ms') {
                                    // $error = "Individu direkodkan telah meninggal dunia";
                                    $error = "No. Kad Pengenalan tidak sah";
                                } else {
                                    $error = "NRIC No. Not Valid";
                                }
                                $StatusPengadu = '6'; // Individu direkodkan telah meninggal dunia
                                $MessageLog = "Individu direkodkan telah meninggal dunia";

                                if ($request->expectsJson()) {
                                    if ($request->language == 'ms') {
                                        return response()->json(['data' => 'No. Kad Pengenalan Tidak Sah'], 422);
                                    } else {
                                        return response()->json(['data' => 'NRIC No. Not Valid'], 422);
                                    }
                                }
                            } else {
                                // Dapatkan rekod jpn letak dlm array
                                $arrData->error = "";
                                // if ($response->ResidentialStatus == 'B' ||
                                //     $response->ResidentialStatus == 'C') {
                                if (in_array($ResidentialStatus, ['B', 'C'])) {
                                    $StatusPengadu = '1';  // Warganegara
                                    $MessageLog = "Warganegara";
                                } else {
                                    $StatusPengadu = '2'; // Pemastautin Tetap
                                    $MessageLog = "Pemastautin Tetap";
                                }
                            }
                            $UserAddress = "$response->CorrespondenceAddress1 $response->CorrespondenceAddress2";
                            $UserPostcode = "$response->CorrespondenceAddressPostcode";
                            $UserCity = KodMapping::MapDistrict($response->CorrespondenceAddressCityCode);
                            $UserState = "$response->CorrespondenceAddressStateCode";
                            $UserAge = $arrData->Age;
                            $UserGender = $arrData->Gdr;
                            $UserCountry = $response->CorrespondenceAddressCountryCode;
                            $BahasaPengadu = 'ms';
                            //                                $UserName =  $request['usernameicno'];
                            $arrData->error = "";
                        } else {
                            $UserAddress = $request->address;
                            $UserAge = $request['age'];
                            $UserGender = $request['gender'];
                            $UserCountry = $request['ctry_cd'];
                            $UserPostcode = $request['postcode'];
                            $UserCity = $request['distrinct_cd'];
                            $UserState = $request['state_cd'];
                            $StatusPengadu = 3;
                            $BahasaPengadu = 'en';
                            //            $UserName =  $request['usernamepassport'];
                            $error = "";
                        }
                        // start create log file for myIdentity server response
                        $arrayLog = get_object_vars($arrData);
                        $arrayLog['RequestDateTime'] = $RequestDateTime;
                        $arrayLog['RequestIndicator'] = $RequestIndicator;
                        $arrayLog['StatusPengadu'] = $StatusPengadu;
                        $arrayLog['Nama_Pengadu'] = $Nama_Pengadu;
                        $arrayLog['MessageLog'] = $MessageLog;
                        MyIdentityRepository::generatelog($arrayLog);
                        // end create log file for myIdentity server response
                    } else {
                        $arrData = $response;
                        if (config('app.locale') == 'ms') {
                            $arrData->error = "Nama TIDAK sepadan dengan Kad Pengenalan";
                            $error = "Nama TIDAK sepadan dengan Kad Pengenalan";
                        } else {
                            $arrData->error = "Name and IC No. NOT Match";
                            $error = "Name and IC No. NOT Match";
                        }

                        $StatusPengadu = '4'; // Nama tidak sama dengan No. kp

                        // start create log file for myIdentity server response
                        $arrayLog = get_object_vars($arrData);
                        $arrayLog['RequestDateTime'] = $RequestDateTime;
                        $arrayLog['RequestIndicator'] = $RequestIndicator;
                        $arrayLog['StatusPengadu'] = $StatusPengadu;
                        $arrayLog['Nama_Pengadu'] = $Nama_Pengadu;
                        $arrayLog['MessageLog'] = $error;
                        MyIdentityRepository::generatelog($arrayLog);
                        // end create log file for myIdentity server response

                        if ($request->expectsJson()) {
                            if ($request->language == 'ms') {
                                return response()->json(['data' => 'Nama TIDAK sepadan dengan Kad Pengenalan'], 422);
                            } else {
                                return response()->json(['data' => 'Name and IC No. NOT Match'], 422);
                            }
                        }
                        $StatusPengadu = '4'; // Nama tidak sama dengan No. kp
                    }
                } else {
                    $arrData = $response;
                    if (config('app.locale') == 'ms') {
//                                $arrData->error = "No. Kad Pengenalan Tidak Sah";
                        $error = "No. Kad Pengenalan/Pasport Tidak Sah";
                    } else {
//                                $arrData->error = "IC No. Not Valid";
                        $error = "NRIC /Passport No. Not Valid";
                    }

                    $StatusPengadu = '5'; // No. Kp tidak Sah

                    // start create log file for myIdentity server response
                    $arrayLog = get_object_vars($arrData);
                    $arrayLog['RequestDateTime'] = $RequestDateTime;
                    $arrayLog['RequestIndicator'] = $RequestIndicator;
                    $arrayLog['StatusPengadu'] = $StatusPengadu;
                    $arrayLog['Nama_Pengadu'] = $Nama_Pengadu;
                    $arrayLog['MessageLog'] = $error;
                    MyIdentityRepository::generatelog($arrayLog);
                    // end create log file for myIdentity server response

                    if ($request->expectsJson()) {
                        if ($request->language == 'ms') {
                            return response()->json(['data' => 'No. Kad Pengenalan/Pasport Tidak Sah'], 422);
                        } else {
                            return response()->json(['data' => 'NRIC /Passport No. Not Valid'], 422);
                        }
                    }
                }
//                    } else {
//                        if (config('app.locale') == 'ms') {
//                                $arrData->error = "Masalah teknikal";
//                            } else {
//                                $arrData->error = "Technical Problem";
//                            }
//                    }
            // } catch (SoapFault $fault) {
            } catch (Exception $fault) {
                $arrData = $fault;
                if (config('app.locale') == 'ms') {
//                            $arrData->error = "Masalah teknikal";
                    $error = "Masalah teknikal";
                } else {
//                            $arrData->error = "Technical Problem";
                    $error = "Technical Problem";
                }

                // start create log file for myIdentity server response
                $arrayLog = get_object_vars($arrData);
                $arrayLog['AgencyCode'] = $AgencyCode;
                $arrayLog['BranchCode'] = $BranchCode;
                $arrayLog['UserId'] = $UserId;
                $arrayLog['TransactionCode'] = $TransactionCode;
                $arrayLog['RequestDateTime'] = $RequestDateTime;
                $arrayLog['ICNumber'] = $ICNumber;
                $arrayLog['RequestIndicator'] = $RequestIndicator;
                $arrayLog['Nama_Pengadu'] = $Nama_Pengadu;
                $StatusPengadu = '7'; // Masalah teknikal
                $arrayLog['StatusPengadu'] = $StatusPengadu;
                $arrayLog['MessageLog'] = $error;
                MyIdentityRepository::generatelog($arrayLog);
                // end create log file for myIdentity server response

                if ($request->expectsJson()) {
                    if ($request->language == 'ms') {
                        return response()->json(['data' => 'Masalah teknikal'], 422);
                    } else {
                        return response()->json(['data' => 'Technical Problem'], 422);
                    }
                }
//                        print "Catch\n";
//                        return redirect()->route('userregister')->withInput();
            }
//                finally
//                {
//                    return redirect()->route('userregister')->withInput()->with('warning', trans('auth.register.validation_submit'));
//                }
//                $error = $arrData->error;
        } else {
            // $UserAddress = $request->address;
            $UserAddress = null;
            $UserAge = $request['age'];
            $UserGender = $request['gender'];
            $UserCountry = $request['ctry_cd'];
            // $UserPostcode = $request['postcode'];
            $UserPostcode = null;
            // $UserCity = $request['distrinct_cd'];
            $UserCity = null;
            // $UserState = $request['state_cd'];
            $UserState = null;
            $StatusPengadu = 3;
            $BahasaPengadu = 'en';
//            $UserName =  $request['usernamepassport'];
            $error = "";
        }
        if ($error == "") {
//            $User->address = $UserAddress;
//            $User->postcode = $UserPostcode;
//            $User->distrinct_cd = trim($UserCity);
//            $User->state_cd = trim($UserState);
            $User->age = $UserAge;
            $User->gender = $UserGender;
//            $User->ctry_cd = $UserCountry;
//            $User->icnew = $request['username'];
//            $User->username = $request['username'];
            $User->icnew = $request->citizen == 1 ? $request['username'] : $request['passport'];
            $User->username = $request->citizen == 1 ? $request['username'] : $request['passport'];
            // $User->myidentity_address = $UserAddress;
            $User->myidentity_address = $UserAddress ?? null;
            // $User->myidentity_postcode = $UserPostcode;
            $User->myidentity_postcode = $UserPostcode ?? null;
            // $User->myidentity_distrinct_cd = trim($UserCity);
            $User->myidentity_distrinct_cd = !empty($UserCity) ? trim($UserCity) : null;
            // $User->myidentity_state_cd = trim($UserState);
            $User->myidentity_state_cd = !empty($UserState) ? trim($UserState) : null;
            $User->email = $request['email'];
            $User->mobile_no = $request['mobile_no'];
            $User->name = $request['name'];
            $User->citizen = $request['citizen'];
            $User->status_pengadu = $StatusPengadu;
            $User->email_token = hash_hmac('sha256', str_random(40), config('app.key'));
            $User->user_cat = 2;
            $User->status = 1;
            $User->lang = $BahasaPengadu;
            $User->password = bcrypt($request['password']);
            $User->profile_ind = '0';
            if ($User->save()) {
                $local = $request['locale'];
                $User->notify(new SendActivationEmail($User, $request, $local));
                if ($request->expectsJson()) {
                    return response()->json(['data' => 'Pendaftaran Berjaya!'], 200);
                }
                $request->session()->flash('success', 'Pendaftaran Berjaya!');
                if ($request['citizen'] == '1') {
                    if (Auth::attempt(['username' => $request['username'], 'password' => $request['password'], 'user_cat' => '2', 'status' => '1'])) {
                        return redirect()->route('dashboard');
                    }
                } elseif ($request['citizen'] == '0') {
                    if (Auth::attempt(['username' => $request['passport'], 'password' => $request['password'], 'user_cat' => '2', 'status' => '1'])) {
                        return redirect()->route('dashboard');
                    }
                }
            }
        } else {
            if (config('app.locale') == 'ms') {
                $placeholder = "Nama seperti di dalam kad pengenalan/pasport";
            } else {
                $placeholder = "Name as in identity card/passport";
            }
            /* if ($request->expectsJson()) {
                return response()->json(['error' => 422]);
            } */
            if ($request->expectsJson()) {
                if ($request->language == 'ms') {
                    return response()->json(['data' => 'Nama seperti di dalam kad pengenalan/pasport'], 422);
                } else {
                    return response()->json(['data' => 'Name as in identity card/passport'], 422);
                }
            }

//            dd($error);
            $request->session()->flash('alert', $error);
            return redirect()->route('userregister')->withInput();
//            return view('login.user_register', compact('placeholder'));
        }


//        $User = User::create([
//            'username' => $request['username'],
//            'name' => $request['name'],
//            'icnew' => $request['username'],
//            'email' => $request['email'],
//            'email_token' => hash_hmac('sha256', str_random(40), config('app.key')),
//            'user_cat' => '2',
//            'status' => '0',
//            'password' => bcrypt($request['password']),
//        ]);

//        event(new UserRegistered($user));
//        session()->flash('success', 'You have successfully submit your registration. An email is sent to you for verification.');
        return redirect()->route('submitregistration');
    }

    public function submitRegistration()
    {
        return view('login.submit_registration');
    }

    public function GetDistList($state_cd)
    {
        if (config('app.locale') == 'ms') {
            $choose = '-- SILA PILIH --';
        } else {
            $choose = '-- PLEASE SELECT --';
        }
        $mDistList = DB::table('sys_ref')
            ->where('cat', '18')
            ->where('code', 'like', "$state_cd%")
            ->orderBy('sort')
            ->pluck('code', 'descr')
            ->prepend('', $choose);
        return json_encode($mDistList);
    }

    public function verifyEmail($token)
    {

        $user = User::where(['email_token' => $token, 'status' => '0'])->first();
        if ($user) {
            $user->status = '1';
            $user->email_token = null;
            if ($user->save()) {
                session()->flash('success', 'Email anda telah disahkan dan akaun anda telah aktif. Sila login.');
                return view('login.submit_registration');
            }
//            if(Auth::attempt(['username' => $user->username, 'password' => $user->password, 'user_cat' => '2'])) {
//                return redirect()->intended('dashboard');
//            }
        } else {
            session()->flash('success', 'Akaun anda telah aktif.');
            return view('login.submit_registration');
        }
//        if (Auth::attempt(['email_token' => $token, 'user_cat' => '2'])) {
//            return redirect()->intended('dashboard');
//        }
//        dd($user);
    }

    /**
     * Prepare dashboard view based on user login type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function dashboard()
    {
        $countCollections['epAssignment'] = CaseEnquiryPaper::where('kod_cawangan', Auth::user()->brn_cd)
            ->whereIn('case_status_code', [2, 3])->count();
        $countCollections['epInvestigation'] = CaseEnquiryPaper::where('kod_cawangan', Auth::user()->brn_cd)
            ->where('io_user_id', Auth::user()->id)->whereIn('case_status_code', [4])->count();
        $countCollections['epClosure'] = CaseEnquiryPaper::where('kod_cawangan', Auth::user()->brn_cd)
            ->whereIn('case_status_code', [5])->count();
        if (Auth::user()->user_cat == '1') {
            $mRoleMapping = DB::table('sys_role_mapping')
                ->where(['role_code' => Auth::User()->role->role_code])
                ->pluck('menu_id');
            if ($mRoleMapping != null && count($mRoleMapping) > 0) {
                $dashboardView = DB::table('sys_menu')
                    ->select('remarks')
                    ->whereIn('id', $mRoleMapping)
                    ->where(['sort' => '99'])
                    ->value('remarks');
                if ($dashboardView != null && count($dashboardView) > 0)
                    return view('login.dashboard_' . $dashboardView);
                else
                    return view('login.admin_dashboard', compact('countCollections'));
            } else {
                return view('login.admin_dashboard', compact('countCollections'));
            }
        } elseif (Auth::user()->user_cat == '2' || Auth::user()->user_cat == '3') {
            $mUser = User::find(Auth::user()->id);
            return view('login.user_dashboard', compact('mUser'));
        } else {
            return redirect()->back();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
