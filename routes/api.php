<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function() {

    // USER
    Route::post('logout', 'Auth\LoginController@logout');
    Route::post('updateprofile', 'UserController@PublicUpdateProfile');
    Route::post('updateprofileadmin/{id}', 'UserController@UpdateProfile');
    Route::post('updatepassword/{id}', 'UserController@PublicUpdatePassword');
    Route::post('updatepasswordadmin/{id}', 'UserController@updatepassword');
    Route::get('propassind/{username}/{language}', 'Api\AduanController@getProPassInd');
    
    // ADUAN
    Route::post('aduanlist', 'Aduan\PublicCaseController@getDatatable');
    Route::post('aduansend', 'Aduan\PublicCaseController@store');
    Route::post('lokasiaduansend', 'Api\AduanController@postLokasiAduan');
    Route::get('aduansemak/{id}', 'Api\AduanController@getSemakAduan');
    Route::post('aduansubmit/{id}', 'Aduan\PublicCaseController@submit');
    Route::post('aduanupdate/{id}', 'Aduan\PublicCaseController@update');

    // PERTANYAAN / CADANGAN
    Route::post('cadangansend', 'Pertanyaan\PertanyaanPublicController@store');
    Route::post('cadangansubmit/{id}', 'Pertanyaan\PertanyaanPublicController@submit');
    Route::post('cadanganupdate/{id}', 'Pertanyaan\PertanyaanPublicController@update');
    Route::post('cadanganlist', 'Pertanyaan\PertanyaanPublicController@getDatatable');
    Route::get('cadangansemak/{id}', 'Api\AduanController@getSemakCadangan');

    // DASHBOARD
    Route::get('counttugasan', 'Api\CategoryController@getCountTugasan');
    Route::get('countsiasatan', 'Api\CategoryController@getCountSiasatan');
    Route::get('countpenutupan', 'Api\CategoryController@getCountPenutupan');
    Route::post('countmonth', 'DashboardController@AduanIkutBulan');
    Route::get('getyear', 'Api\CategoryController@getYear');

    // PENUGASAN ADUAN
    Route::post('penugasanaduansenarai', 'Aduan\TugasController@getDataTable');
    Route::post('penugasanaduanuser', 'Aduan\TugasController@getDataTableUser');
    Route::post('penugasanaduanhantar/{CA_CASEID}', 'Aduan\TugasController@TugasKepada');
    Route::post('penugasanaduangabunghantar', 'Aduan\GabungController@update');
    Route::post('penugasanaduankelompokhantar', 'Aduan\GabungController@update');

    // PINDAH ADUAN
    Route::post('pindahaduansenarai', 'Aduan\PindahController@getdatatablecase');
    Route::post('pindahaduandoc/{CASEID}', 'Aduan\PindahController@getdatatableattachment');
    // Route::post('pindahaduanmerge/{CASEID}', 'Aduan\PindahController@getdatatablemergecase');
    Route::post('pindahaduanmerge/{CASEID}', 'Aduan\SiasatController@getGabung');
    // Route::post('pindahaduanuser', 'Aduan\PindahController@getDataTableUser');
    Route::post('pindahaduanuser', 'Api\AduanController@getUserPindahAduan');
    Route::post('pindahaduanhantar/{CASEID}', 'Aduan\PindahController@update');
    Route::post('pindahaduankelompokhantar', 'Aduan\PindahController@submitKelompok');

    // PENUGASAN SEMULA
    Route::post('penugasansemulasenarai', 'Aduan\TugasSemulaController@getdatatablecase');
    Route::post('penugasansemulahantar/{CASEID}', 'Aduan\TugasSemulaController@update');

    // PENYIASATAN
    Route::post('penyiasatansenarai', 'Aduan\SiasatController@getDataTable');
    Route::post('penyiasatanbhnbukti/{CASEID}', 'Aduan\SiasatController@getattachment');
    Route::post('penyiasatanbhnsiasat/{CASEID}', 'Aduan\SiasatController@getAttachmentSiasat');
    Route::post('penyiasatansmpn/{CASEID}', 'Aduan\SiasatController@update');
    Route::post('penyiasatanhntr/{CASEID}', 'Aduan\SiasatController@update');
    Route::post('aktasenarai/{CASEID}', 'Aduan\SiasatController@getKesSiasat');
    Route::post('aktatambah/{CASEID}', 'Aduan\SiasatController@storekessiasat');
    Route::post('aktaedit/{id}', 'Aduan\SiasatController@updateKesSiasat');
    Route::post('aktabuang/{id}', 'Aduan\SiasatController@destroyKesSiasat');

    // BUKA SEMULA
    Route::post('bukasemulasenarai', 'Aduan\BukaSemulaController@getdatatablecase');
    Route::get('bukasemulanoaduan/{CASEID}', 'Aduan\BukaSemulaController@edit');
    Route::post('bukasemulahantar/{CASEID}', 'Aduan\BukaSemulaController@update');

    // PENUTUPAN
    Route::post('penutupansenarai', 'Aduan\TutupController@getDataTable');
    Route::post('penutupansenaraistatus', 'Api\AduanController@getSiasatanSelesaiTutup');
    Route::post('penutupanuser', 'Aduan\TugasController@getDataTableUser');
    // Route::post('penutupanuser', 'Aduan\TutupController@getdatatableuser');
    Route::get('penutupanlatestdetail/{CASEID}', 'Api\CategoryController@getLatestCaseDetail');
    Route::post('penutupanhantar/{CA_CASEID}', 'Aduan\TutupController@tutupAduan');

    // PERTANYAAN / CADANGAN ADMIN
    Route::post('pertanyaansenarai', 'Pertanyaan\PertanyaanAdminController@getDatatable');
    Route::post('pertanyaansemakmykad/{DOCNO}', 'Aduan\AdminCaseController@getDataJpn');
    Route::post('pertanyaansimpan', 'Pertanyaan\PertanyaanAdminController@store');
    Route::post('pertanyaankemaskini/{id}', 'Pertanyaan\PertanyaanAdminController@updateadmin');
    Route::post('pertanyaanhantarjwpn', 'Pertanyaan\PertanyaanAdminController@update');
    Route::post('pertanyaanhantarsmpn', 'Pertanyaan\PertanyaanAdminController@update');
    Route::post('pertanyaansubmit/{id}', 'Pertanyaan\PertanyaanAdminController@submit');
    
    // TRANSAKSI ADUAN
    Route::post('transaksiaduan', 'Api\AduanController@getTransaksiAduan');
    
    // PENGUMUMAN
    Route::get('pengumuman/{cat}', 'Api\AduanController@getPengumuman');

    // Consumer complaint count.
    Route::get('consumercomplaintcount', 'Api\AduanController@consumercomplaintcount');
});

// APP_URL
Route::get('appurl', function() {
    return response()->json(['data' => env('APP_URL')]);
});

// FAQ
Route::post('faq', 'Api\CategoryController@getFaq');

// USER
Route::post('login', 'Auth\LoginController@login');
Route::post('loginadmin', 'Auth\LoginController@loginAdmin');
Route::get('role', 'Auth\LoginController@withUserAccess');
// Route::post('register', 'Auth\RegisterController@create');
Route::post('register', 'LoginController@userSubmit');
Route::post('retrievepassword', 'Auth\ResetPasswordController@getPassword');
Route::get('user/{id}', 'Api\CategoryController@getUser');
Route::get('getuser/{id}', 'Api\CategoryController@getDataUser');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

// HANTAR ADUAN
Route::post('postref', 'Api\CategoryController@postRef');
Route::get('getref/{type}/{language}', 'Api\CategoryController@getRef');
Route::get('getsubcategory/{type}/{category}/{language}', 'Api\CategoryController@getSubCategory');
Route::get('state', 'Api\StateDistrictController@getState');
Route::get('district/{state}', 'Api\StateDistrictController@getDistrict');

// SEMAK ADUAN
Route::get('getdata/{cat}/{code}/{language}', 'Api\CategoryController@getData');
Route::get('checkstate/{statecode}', 'Api\CategoryController@getCheckState');
Route::get('checkdistrict/{districtcode}', 'Api\CategoryController@getCheckDistrict');
Route::get('checkcasedetails/{caseid}/{language}', 'Api\CategoryController@getCheckCaseDetails');
Route::get('checkaskdetails/{askid}/{language}', 'Api\CategoryController@getCheckAskDetails');
Route::get('checkevidencefile/{caseid}', 'Api\CategoryController@getCheckEvidenceFile');
Route::get('checkattachmentfile/{askid}', 'Api\CategoryController@getCheckAttachmentFile');

Route::get('getcmplduration/{CA_CASEID}', 'Api\CategoryController@getCmplDuration');

// CAWANGAN
Route::get('getbrn/{state_cd}', 'Api\CategoryController@getBrn');
Route::get('getdatabrn/{brn_cd}', 'Api\CategoryController@getDataBrn');

// SURAT ADMIN DAN PUBLIC
Route::get('getletteradmin/{id}', 'Api\CategoryController@getLetterAdmin');
Route::get('getletterpublic/{id}', 'Api\CategoryController@getLetterPublic');

// PENUGASAN ADUAN
Route::get('penugasanaduanstatus', 'Api\CategoryController@getPenugasanAduanStatus');

// PINDAH ADUAN
Route::get('pindahaduantransaction/{CASEID}', 'Api\CategoryController@getTransaction');
Route::get('pindahaduanstatus', 'Api\CategoryController@getPindahAduanStatus');
Route::get('pindahaduanagency', 'Api\CategoryController@getAgency');

// PENYIASATAN
Route::get('penyiasatanstatus', 'Api\CategoryController@getPenyiasatanStatus');
Route::get('penyiasatandetailpenugasan/{CASEID}', 'Api\CategoryController@getPenyiasatanDetailPenugasan');
Route::get('penyiasatankodakta/{akta}', 'Api\CategoryController@getKodAkta');
Route::get('penyiasatangabung/{CA_CASEID}', 'Api\AduanController@getAduanGabung');

// BUKA SEMULA
Route::get('bukasemulastatus', 'Api\CategoryController@getBukaSemulaStatus');

// PENUTUPAN
Route::get('penutupanstatus', 'Api\CategoryController@getTutupStatus');

// PERTANYAAN / CADANGAN ADMIN
// Route::post('pertanyaansenarai', 'Pertanyaan\PertanyaanAdminController@getDatatable');
Route::get('pertanyaanstatus', 'Api\CategoryController@getStatusPertanyaan');
Route::get('pertanyaandoc/{id}', 'Api\CategoryController@getPertanyaanDoc');
Route::get('pertanyaantransaksi/{ASKID}', 'Api\CategoryController@getPertanyaanTransaction');
Route::get('pertanyaancarapenerimaan', 'Api\CategoryController@getAskRcvTyp');
Route::get('pertanyaancmplcat/{cmplcat}/{askcat}', 'Api\CategoryController@getAllCmplCatList');
Route::get('pertanyaancmplcd/{cmplcat}', 'Api\CategoryController@getAllCmplCdList');

// FAIL ADUAN
Route::post('failaduan', 'Api\BuktiController@simpanBahanBukti');

// FAIL PERTANYAAN / CADANGAN
Route::post('failcadangan', 'Api\BuktiController@simpanLampiranPertanyaan');

// FAIL GAMBAR PENGGUNA
Route::post('failgambarpengguna', 'Api\BuktiController@simpanGambarProfil');

// FAIL PENYIASATAN
Route::post('failpenyiasatan/{CASEID}', 'Api\BuktiController@simpanBahanSiasatan');

// FAIL UKK PERTANYAAN / CADANGAN
Route::post('failpertanyaan', 'Pertanyaan\PertanyaanAdminDocController@store');

// NO ADUAN SEBELUM DIBUKA SEMULA
Route::get('noaduansebelum/{CA_CASEID}', 'Api\AduanController@getNoAduanSebelum');

// CA_SUMMARY SAHAJA
Route::get('summary/{CA_CASEID}', 'Api\AduanController@getSummary');

// MOBILE SLIDER
Route::get('mobileslider', 'Api\CategoryController@getMobileSlider');

Route::prefix('feedback')->group(function () {
    Route::resource('whatsapp', 'Feedback\Whatsapp\WhatsappController', [
        'only' => ['store']
    ]);
    Route::resource('telegram/fetch/{token}', 'Feedback\Telegram\TelegramController', [
        'only' => ['store']
    ]);
});

// API FETCH ADUAN LIST
Route::get('fetch/case', 'Api\CaseFetchAPIController@index');

// API Fetch Consumer Complaint List For Integration with IEMS
Route::resource('consumercomplaints', 'Api\ConsumerComplaintController', ['only' => ['index', 'show', 'update']]);
