<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
include 'trash.php';

Route::get('/', 'WelcomeController@index')->name('welcome');
Route::get('kepenggunaan', 'PortalConsumerismController');
Route::get('integriti', 'PortalIntegrityController');
Route::any('welcome/webhook', 'WelcomeController@Webhook');
Route::get('portal/{menu_id}', 'WelcomeController@article')->name('portal.article');
Route::get('checkcase', 'WelcomeController@checkcase');
Route::get('checkenquiry', 'WelcomeController@checkenquiry');
Route::get('checkintegriti', 'WelcomeController@checkintegriti');
Route::get('editprofile/{id}', 'UserController@EditProfile');
Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('locale/{locale}', 'LocaleController@SetLocale')->middleware('locale')->name('locale.l10n');
Route::get('fontincrease', 'LocaleController@FontIncrease')->middleware('locale');
Route::get('fontdecrease', 'LocaleController@FontDecrease')->middleware('locale');
Route::get('fontreset', 'LocaleController@FontReset')->middleware('locale');
Route::get('fontcolor/{color}', 'LocaleController@FontColor')->middleware('locale');

Route::prefix('log')->group(function () {
    Route::get('/', 'LogController@Index')->name('logindex');
    Route::get('getdatatable', 'LogController@GetDatatable');
});

Route::any('/webhook', 'LoginController@webhook');
Route::get('/user-register', 'LoginController@userRegister')->name('userregister');
Route::post('/user-submit', 'LoginController@userSubmit');
Route::get('/submit-registration', 'LoginController@submitRegistration')->name('submitregistration');
Route::get('/verify-email/{token}', 'LoginController@verifyEmail');
Route::get('/user-login', 'LoginController@userLogin');
Route::get('/login2', 'LoginController@adminLogin')->name('auth.login2');

Route::post('/user-auth', 'LoginController@userAuth');
Route::any('/admin-auth', 'LoginController@adminAuth');
Route::get('/dashboard', 'LoginController@dashboard')->name('dashboard');
Route::get('/getdistlist/{state_cd}', 'LoginController@GetDistList');
Route::get('authlogout', 'Auth\LoginController@logout');

Route::prefix('user')->group(function () {
    Route::get('/', 'UserController@AdminUser')->name('adminuser');
    Route::get('getdatatableadmin', 'UserController@GetDatatableAdmin');
    Route::get('createadmin', 'UserController@CreateAdmin')->name('createadmin');
    Route::post('storeadmin', 'UserController@StoreAdmin');
    Route::get('editadmin/{id}', 'UserController@EditAdmin')->name('user.editadmin');

    Route::get('changepassword/{id}', 'UserController@changepassword')->name('changepassword');
    Route::patch('updatepassword/{id}', 'UserController@updatepassword');
    Route::patch('updateprofile/{id}', 'UserController@UpdateProfile');

    Route::get('pubeditprofile', 'UserController@PublicEditProfile');
    Route::patch('pubupdateprofile/{id}', 'UserController@PublicUpdateProfile');
    Route::get('pubchangepassword/{id}', 'UserController@PublicChangePassword');
    Route::patch('pubupdatepassword/{id}', 'UserController@PublicUpdatePassword');
    Route::get('pubdeleteuserphoto', 'UserController@PublicDeleteUserPhoto');
    Route::get('admindeleteuserphoto', 'UserController@AdminDeleteUserPhoto');

    Route::post('updateuserphoto/{id}', 'UserController@AdminUpdateUserPhoto');
    Route::get('language/{locale}/{tab?}', 'UserController@ChangeLanguage');
    Route::patch('patchadmin/{id}', 'UserController@PatchAdmin');
    Route::get('getbrnlist/{state_cd}', 'UserController@GetBrnList')->name('getbrnlist');
    Route::get('generatedoc/{id}', 'UserController@generatedoc')->name('user.generatedoc');

    Route::get('impersonate/{id}', 'UserImpersonateController@impersonate')->name('admin.users.impersonate');
    Route::post('impersonate/leave', 'UserImpersonateController@leave')->name('admin.users.impersonateLeave');
});

Route::prefix('publicuser')->group(function () {
    Route::get('/', 'UserController@PublicUser')->name('publicuser');
    Route::get('/getdatatablepublic', 'UserController@GetDataTablePublic');
    Route::get('create', 'UserController@CreatePublic')->name('createpublic');
    Route::get('edit/{id}', 'UserController@EditPublic')->name('publicuser.edit');
    Route::get('changepasspublic/{id}', 'UserController@ChangePassUser')->name('publicuser.changepasspublic');
    Route::patch('updatepassword/{id}', 'UserController@UpdatePassUser');
    Route::post('store', 'UserController@StorePublic');
    Route::patch('updatepublic/{id}', 'UserController@UpdatePublic');
    Route::get('delete/{id}', 'UserController@DeletePublic');
    Route::get('getdstrtlist/{state_cd}/{lang}', 'UserController@GetDstrtList')->name('getdstrtlist');
    Route::get('complaintpublic', 'UserController@complaintpublic');
});

Route::prefix('ref')->group(function () {
    Route::get('/', 'RefController@IndexKat')->name('ref');
    Route::get('getdatatablekat', 'RefController@GetDatatableKat');
    Route::get('createkat', 'RefController@CreateKat');
    Route::post('storekat', 'RefController@StoreKat');
    Route::get('editkat/{id}', 'RefController@EditKat')->name('ref.editkat');
    Route::patch('putkat/{id}', 'RefController@PutKat');
    Route::get('deletekat/{id}', 'RefController@DeleteKat')->name('ref.deletekat');
    Route::get('pdf', 'RefController@Pdf');

    Route::get('listparam/{id}', 'RefController@ListParam')->name('listparam');
    Route::get('getdatatableparam/{id}', 'RefController@GetDatatableParam');
    Route::get('createparam/{id}', 'RefController@CreateParam');
    Route::post('storeparam', 'RefController@StoreParam');
    Route::get('editparam/{id}', 'RefController@EditParam')->name('ref.editparam');
    Route::patch('patchparam/{id}', 'RefController@PatchParam');
    Route::get('deleteparam/{id}', 'RefController@DeleteParam')->name('ref.deleteparam');
});

Route::prefix('letter')->group(function () {
    Route::get('/', 'LetterController@index');
    Route::get('/get_datatable', 'LetterController@get_datatable');
    Route::get('/create', 'LetterController@create');
    Route::post('/store', 'LetterController@store');
    Route::get('/show/{id}', 'LetterController@show');
    Route::get('/edit/{id}', 'LetterController@edit');
    Route::put('/update/{id}', 'LetterController@update');
    Route::get('/pdf/{id}', 'LetterController@pdf');
    Route::get('/delete/{id}', 'LetterController@delete');
    Route::delete('/destroy/{id}', 'LetterController@destroy');
});

Route::prefix('email')->group(function () {
    Route::get('/', 'EmailController@index');
    Route::get('/get_datatable', 'EmailController@get_datatable');
    Route::get('/create', 'EmailController@create');
    Route::post('/store', 'EmailController@store');
    Route::get('/edit/{id}', 'EmailController@edit');
    Route::put('/update/{id}', 'EmailController@update');
    Route::get('/delete/{id}', 'EmailController@delete');
});

Route::prefix('branch')->group(function () {
    Route::get('/', 'BranchController@index');
    Route::get('/get_datatable', 'BranchController@get_datatable');
    Route::get('/create', 'BranchController@create');
    Route::post('/store', 'BranchController@store');
    Route::get('/show/{branch_code}', 'BranchController@show');
    Route::get('/edit/{branch_code}', 'BranchController@edit');
    Route::put('/update/{branch_code}', 'BranchController@update');
    Route::get('/delete/{branch_code}', 'BranchController@delete');
    Route::get('/getdistlist/{state_cd}', 'BranchController@GetDistList')->name('getdistlist');
});

Route::prefix('article')->group(function () {
    Route::get('/', 'ArticleController@index');
    Route::get('/get_datatable', 'ArticleController@get_datatable');
    Route::get('/create', 'ArticleController@create');
    Route::post('/store', 'ArticleController@store');
    Route::get('/edit/{id}', 'ArticleController@edit')->name('article.edit');
    Route::put('/update/{id}', 'ArticleController@update');
    Route::get('/delete/{id}', 'ArticleController@delete');
});

Route::prefix('menu')->group(function () {
    Route::get('/', 'MenuController@index')->name('menu');
    Route::post('/', 'MenuController@store');
    Route::get('getdatatablekat', 'MenuController@GetDatatableKat');
//    Route::get('/get_datatable', 'MenuController@get_datatable');
    Route::get('/create', 'MenuController@create');
    Route::get('/edit/{menu_id}', 'MenuController@edit')->name('menu.edit');
    Route::put('/update/{menu_id}', 'MenuController@update');
    Route::get('/delete/{menu_id}', 'MenuController@delete')->name('menu.delete');
    Route::delete('/destroy/{menu_id}', 'MenuController@destroy');
    Route::get('/findListMenu', 'MenuController@findListMenu');
});

Route::prefix('role')->group(function () {
    Route::get('/', 'RoleController@index')->name('role');
    Route::get('getdatatable', 'RoleController@GetDatatable');
    Route::get('/create', 'RoleController@create');
    Route::post('/store', 'RoleController@store');
    Route::get('/edit/{role_code}/{menu_id}', 'RoleController@edit');
    Route::put('/update/{role_code}/{menu_id}', 'RoleController@update');
    Route::get('/delete/{role_code}/{menu_id}', 'RoleController@delete');
});

Route::prefix('workingday')->group(function () {
    Route::get('/', 'WorkingDayController@index');
    Route::get('/getdatatable', 'WorkingDayController@get_datatable');
    Route::get('/create', 'WorkingDayController@create');
    Route::post('/store', 'WorkingDayController@store');
    Route::get('/edit/{id}', 'WorkingDayController@edit');
    Route::put('/update/{id}', 'WorkingDayController@update');
    Route::get('/delete/{id}', 'WorkingDayController@destroy');
});

Route::prefix('holiday')->group(function () {
    Route::get('/', 'HolidayController@index');
    Route::get('/getdatatable', 'HolidayController@get_datatable');
    Route::get('/create', 'HolidayController@create');
    Route::post('/store', 'HolidayController@store');
    Route::get('/edit/{id}', 'HolidayController@edit');
    Route::put('/update/{id}', 'HolidayController@update');
    Route::get('/delete/{id}', 'HolidayController@destroy');
});

Route::get('agensi/getdata', 'AgensiController@GetData');
Route::resource('agensi', 'AgensiController');

Route::resource('public-case', 'Aduan\PublicCaseController');
Route::prefix('public-case')->group(function () {
    Route::get('/attachment/{id}/{invsts}', 'Aduan\PublicCaseController@Attachment')->name('public-case.attachment');
    Route::get('/preview/{id}', 'Aduan\PublicCaseController@Preview')->name('public-case.preview');
    Route::any('/submit/{id}', 'Aduan\PublicCaseController@Submit')->name('public-case.submit');
    Route::get('/success/{CASEID}', 'Aduan\PublicCaseController@Success')->name('public-case.success');
    Route::get('/get_datatable/{id}', 'Aduan\PublicCaseController@GetDatatable');
    Route::get('/check/{CASEID}', 'Aduan\PublicCaseController@Check')->name('public-case.check');
    Route::get('/getDatatableTransaction/{CASEID}', 'Aduan\PublicCaseController@GetDatatableTransaction');
    Route::get('/getCmplCdList/{CMPLCD}', 'Aduan\PublicCaseController@getCmplCdList');
    Route::get('/getdaerahlist/{state_cd}', 'Aduan\PublicCaseController@GetDistList');
    Route::get('/getdocdetail/{id}', 'Aduan\PublicCaseController@getdocdetail');
    Route::get('/printsuccess/{CASEID}', 'Aduan\PublicCaseController@PrintSuccess');
    Route::get('/delete/{id}', 'Aduan\PublicCaseController@delete');
});
Route::prefix('public-case-doc')->namespace('Aduan')->group(function () {
    Route::get('getDatatable/{CASEID}', 'PublicCaseDocController@GetDatatable');
    Route::get('create/{CASEID}', 'PublicCaseDocController@create')->name('public-case-doc.create');
    Route::post('/{CASEID}', 'PublicCaseDocController@store')->name('public-case-doc.store');

    Route::get('edit/{id}', 'PublicCaseDocController@edit')->name('public-case-doc.edit');
    Route::put('update/{id}', 'PublicCaseDocController@update')->name('public-case-doc.update');

    Route::get('delete/{id}', 'PublicCaseDocController@destroy')->name('public-case-doc.destroy');
});

/////////////////////// Call Center Start ////////////////////////////
Route::get('call-center-case/getdatatable', 'Aduan\CallCenterCaseController@getdatatable');
Route::get('call-center-case/GenNoAduan', 'Aduan\CallCenterCaseController@GenNoAduan');
//DataTableAttachment
Route::get('call-center-case/GetDataTableAttachment/{CASEID}', 'Aduan\CallCenterCaseController@GetDataTableAttachment');
//CreateAttachment
Route::post('call-center-case/AjaxValidateCreateAttachment', 'Aduan\CallCenterCaseController@AjaxValidateCreateAttachment');
Route::post('call-center-case/StoreAttachment', 'Aduan\CallCenterCaseController@StoreAttachment')->name('call-center-case.storeAttachment');
//UpdateAttachment
Route::get('call-center-case/AttachmentEdit/{DOCATTCHID}', 'Aduan\CallCenterCaseController@AttachmentEdit')->name('call-center-case.AttachmentEdit');
Route::any('call-center-case/AjaxValidateEditAttachment', 'Aduan\CallCenterCaseController@AjaxValidateEditAttachment');
Route::put('call-center-case/AttachmentUpdate/{DOCATTCHID}', 'Aduan\CallCenterCaseController@AttachmentUpdate')->name('call-center-case.AttachmentUpdate');
//DeleteAttachment
Route::get('call-center-case/AttachmentDelete/{CASEID}/{DOCATTCHID}', 'Aduan\CallCenterCaseController@AttachmentDestroy')->name('call-center-case.AttachmentDelete');
//DataTableTransaction
Route::get('call-center-case/GetDataTableTransaction/{CASEID}', 'Aduan\CallCenterCaseController@GetDataTableTransaction');
Route::get('call-center-case/getCmplCdList/{CMPLCD}', 'Aduan\CallCenterCaseController@GetCmplList');
Route::any('call-center-case/tojpn/{DOCNO}', 'Aduan\CallCenterCaseController@GetDataJpn');
Route::get('call-center-case/getdistlist/{state_cd}', 'Aduan\CallCenterCaseController@GetDistList');
Route::get('call-center-case/attachment/{CASEID}', 'Aduan\CallCenterCaseController@attachment')->name('call-center-case.attachment');
Route::get('call-center-case/doccreate/{CASEID}', 'Aduan\CallCenterCaseController@doccreate')->name('call-center-case.doccreate');
Route::get('call-center-case/docedit/{CASEID}', 'Aduan\CallCenterCaseController@docedit')->name('call-center-case.docedit');
Route::put('call-center-case/docupdate/{CASEID}', 'Aduan\CallCenterCaseController@docupdate')->name('call-center-case.docupdate');
Route::post('call-center-case/store-doc/{CASEID}', 'Aduan\CallCenterCaseController@storeDoc')->name('call-center-case.store-doc');
Route::get('call-center-case/docdestroy/{id}', 'Aduan\CallCenterCaseController@destroy')->name('call-center-case.docdestroy');
Route::get('call-center-case/edit/{CASEID}', 'Aduan\CallCenterCaseController@edit')->name('call-center-case.edit');
Route::get('call-center-case/preview/{id}', 'Aduan\CallCenterCaseController@preview')->name('call-center-case.preview');
Route::get('call-center-case/submit/{id}', 'Aduan\CallCenterCaseController@submit')->name('call-center-case.submit');
Route::get('call-center-case/check/{CASEID}', 'Aduan\CallCenterCaseController@check')->name('call-center-case.check');
Route::get('call-center-case/display-noaduan/{CASEID}', 'Aduan\CallCenterCaseController@DisplayNoaduan')->name('call-center-case.display-noaduan');
Route::resource('call-center-case', 'Aduan\CallCenterCaseController');
Route::get('call-center-case/show-summary/{CASEID}', 'Aduan\CallCenterCaseController@ShowSummary')->name('call-center-case.showsummary');
Route::get('call-center-case/print-summary/{CASEID}', 'Aduan\CallCenterCaseController@PrintSummary')->name('call-center-case.printsummary');
Route::get('call-center-case/delete/{id}', 'Aduan\CallCenterCaseController@delete');
/////////////////////// Call Center End ////////////////////////////

Route::prefix('admin-case')->namespace('Aduan')->group(function () {
//    Route::get('/', 'AdminCaseController@index');
    Route::get('/getdatatableuser', 'AdminCaseController@getdatatableuser');
    Route::get('/getdatatablecase', 'AdminCaseController@getdatatablecase');
    Route::get('/getuserdetail/{id}', 'AdminCaseController@GetUserDetail');
//    Route::get('/create', 'AdminCaseController@create');
//    Route::post('/store', 'AdminCaseController@store');
    Route::get('/edit/{CASEID}', 'AdminCaseController@edit');
//    Route::put('/update/{CASEID}', 'AdminCaseController@update');
    Route::get('/getattachment/{CASEID}', 'AdminCaseController@getattachment');
    Route::get('/getdocdetail/{id}', 'AdminCaseController@getdocdetail');
    Route::post('/create-attachment', 'AdminCaseController@createattachment');
    Route::get('/getdatatabletransaction/{CASEID}', 'AdminCaseController@getdatatabletransaction');
//    Route::get('/getdistlist', 'AdminCaseController@GetDistList');
    Route::get('/getdistlist/{state_cd?}', 'AdminCaseController@GetDistList');
    Route::get('/getcmpllist/{cat_cd}', 'AdminCaseController@GetCmplList');
    Route::put('/updateattachment', 'AdminCaseController@updateattachment');
    Route::delete('destroyattachment/{caseid}/{docattachid}', 'AdminCaseController@destroyattachment');
    Route::any('tojpn/{DOCNO?}', 'AdminCaseController@GetDataJpn');
    Route::get('/pdf/{CASEID}', 'AdminCaseController@pdf');
    Route::get('attachment/{id}', 'AdminCaseController@attachment')->name('admin-case.attachment');
    Route::get('preview/{id}', 'AdminCaseController@preview')->name('admin-case.preview');
    Route::patch('submit/{id}', 'AdminCaseController@submit')->name('admin-case.submit');
    Route::get('check/{CASEID}', 'AdminCaseController@check')->name('admin-case.check');
    Route::get('/show-summary/{CASEID}', 'AdminCaseController@ShowSummary')->name('admin-case.showsummary');
    Route::get('/print-summary/{CASEID}', 'AdminCaseController@PrintSummary')->name('admin-case.printsummary');
//    Route::get('/saluran-aduan', 'AdminCaseController@SaluranAduan');
    Route::get('/delete/{id}', 'AdminCaseController@delete');
    Route::get('/getcmplkeywordlist/{cat_cd}', 'AdminCaseController@getcmplkeywordlist');
});
Route::resource('admin-case', 'Aduan\AdminCaseController');

Route::prefix('admin-case-doc')->namespace('Aduan')->group(function () {
    Route::get('create/{id}', 'AdminCaseDocController@create')->name('admin-case-doc.create');
    Route::post('store/{id}', 'AdminCaseDocController@store')->name('admin-case-doc.store');
    Route::get('getdatatable/{CASEID}', 'AdminCaseDocController@getdatatable');
    Route::post('ajaxvalidatestoreattachment', 'AdminCaseDocController@ajaxvalidatestoreattachment');
    Route::get('edit/{id}', 'AdminCaseDocController@edit')->name('admin-case-doc.edit');
    Route::put('update/{id}', 'AdminCaseDocController@update')->name('admin-case-doc.update');
    Route::put('ajaxvalidateupdateattachment', 'AdminCaseDocController@ajaxvalidateupdatettachment');
//    Route::delete('{CASEID}/{DOCATTACHID}', 'AdminCaseDocController@destroy');
    Route::get('delete/{id}', 'AdminCaseDocController@destroy')->name('admin-case-doc.destroy');
});
//Route::resource('admin-case-doc', 'Aduan\AdminCaseDocController');

Route::prefix('sas-case')->group(function () {
    Route::get('/', 'Aduan\SasCaseController@index');
    Route::get('getdata', 'Aduan\SasCaseController@getdata');
    Route::get('create', 'Aduan\SasCaseController@create');
    Route::post('store', 'Aduan\SasCaseController@store');
    Route::get('edit/{CASEID}', 'Aduan\SasCaseController@edit')->name('sas-case.edit');
    Route::patch('update/{CASEID}', 'Aduan\SasCaseController@update');
    Route::post('create-attachment', 'Aduan\SasCaseController@createAttachment');
    Route::get('getdataattach/{CASEID}', 'Aduan\SasCaseController@getdataAttachment');
    Route::get('getdatatransaction/{CASEID}', 'Aduan\SasCaseController@getdataTransaction');
    Route::get('getdistrictlist/{STATECD}', 'Aduan\SasCaseController@getDistrinctList');
    Route::get('getcmplcdlist/{CMPLCAT}', 'Aduan\SasCaseController@getCmplCdList');
    Route::get('getcmplkeywordlist', 'Aduan\SasCaseController@getCmplkeywordList');
    Route::get('getdatatableuser', 'Aduan\SasCaseController@getdatatableuser');
    Route::get('getuserdetail/{id}', 'Aduan\SasCaseController@getuserdetail');
    Route::get('show-summary/{CASEID}', 'Aduan\SasCaseController@ShowSummary')->name('sas-case.showsummary');
    Route::get('print-summary/{CASEID}', 'Aduan\SasCaseController@PrintSummary')->name('sas-case.printsummary');
    Route::get('createdoc/{id}', 'Aduan\SasCaseController@createdoc')->name('sas-case.createdoc');
    Route::post('ajaxvalidatestoreattachment', 'Aduan\SasCaseController@ajaxvalidatestoreattachment');
    Route::get('editdoc/{id}', 'Aduan\SasCaseController@editdoc')->name('sas-case.editdoc');
    Route::put('updatedoc/{id}', 'Aduan\SasCaseController@updatedoc')->name('sas-case.updatedoc');
    Route::get('destroydoc/{id}', 'Aduan\SasCaseController@destroydoc')->name('sas-case.destroydoc');
    Route::get('getdocsiasat/{id}', 'Aduan\SasCaseController@getdocsiasat')->name('sas-case.getdocsiasat');
    Route::get('createdocsiasat/{CASEID}', 'Aduan\SasCaseController@createdocsiasat')->name('sas-case.createdocsiasat');
    Route::post('storedocsiasat', 'Aduan\SasCaseController@storedocsiasat')->name('sas-case.storedocsiasat');
    Route::get('editdocsiasat/{id}', 'Aduan\SasCaseController@editdocsiasat')->name('sas-case.editdocsiasat');
    Route::put('updatedocsiasat/{id}', 'Aduan\SasCaseController@updatedocsiasat')->name('sas-case.updatedocsiasat');
});

Route::prefix('sas-case')->namespace('Aduan')->group(function () {
    Route::any('tojpn/{DOCNO}', 'SasCaseController@GetDataJpn');
    Route::get('getakta/{id}', 'SasCaseController@getakta')->name('sas-case.getakta');
    Route::get('createakta/{CASEID}', 'SasCaseController@createakta')->name('sas-case.createakta');
    Route::post('storeakta/{CASEID}', 'SasCaseController@storeakta')->name('sas-case.storeakta');
    Route::any('destroyakta/{id}', 'SasCaseController@destroyakta')->name('sas-case.destroyakta');
    Route::get('editakta/{id}', 'SasCaseController@editakta')->name('sas-case.editakta');
    Route::put('updateakta/{id}', 'SasCaseController@updateakta')->name('sas-case.updateakta');
});


Route::group(['prefix' => 'tugas', 'namespace' => 'Aduan'], function () {
    Route::resource('/', 'TugasController');
    Route::get('get_datatable', 'TugasController@getDataTable');
    Route::get('penugasan_aduan/{CA_CASEID}', 'TugasController@PenugasanAduan')->name('tugas.penugasanaduan');
    Route::get('getdatatableuser', 'TugasController@getDataTableUser');
    Route::get('getdatatablemultiuser', 'TugasController@getDataTableMultisUser');
    Route::get('/getuserdetail/{id}', 'TugasController@GetUserDetail');
    Route::post('/tugas-kepada/{CA_CASEID}', 'TugasController@TugasKepada');
    Route::get('/gettransaction/{CD_CASEID}', 'TugasController@gettransaction');
    Route::get('/getattachment/{CD_CASEID}', 'TugasController@getattachment');
    Route::get('/getgabungkes/{CA_CASEID}', 'TugasController@getGabungKes');
    Route::get('/cetak_surat/{CA_CASEID}', 'TugasController@CetakSuratPenugasan');
    Route::get('/cetak_surat_penerimaan/{CA_CASEID}', 'TugasController@CetakSuratPenerimaan');
    Route::get('/getcmpllist/{CMPLCAT}', 'TugasController@getcmpllist');
    Route::get('/show-summary/{CASEID}', 'TugasController@ShowSummary')->name('tugas.showsummary');
    Route::get('/print-summary/{CASEID}', 'TugasController@PrintSummary')->name('tugas.printsummary');
    Route::get('/tutup', 'TugasController@TutupAduan');
    Route::get('generateword/{CASEID}/{userid}', 'TugasController@generateword')->name('tugas.generateword');
});

Route::prefix('gabung')->namespace('Aduan')->group(function () {
    Route::get('edit', 'GabungController@edit')->name('gabung.edit');
    Route::post('update', 'GabungController@update')->name('gabung.update');
});

Route::prefix('siasat')->namespace('Aduan')->group(function () {
    Route::get('', 'SiasatController@index')->name('siasat.index');
    Route::get('GetDataTable', 'SiasatController@GetDataTable')->name('siasat.GetDataTable');
    Route::get('edit/{CASEID}', 'SiasatController@edit')->name('siasat.edit');
    Route::post('siasat/{CASEID}', 'SiasatController@siasat')->name('siasat.siasat');
    Route::put('update/{CASEID}', 'SiasatController@update')->name('siasat.update');
    Route::get('getsubakta/{AKTA}', 'SiasatController@GetSubAkta')->name('siasat.getsubakta');
    Route::get('getattachment/{CASEID}', 'SiasatController@getattachment');
    Route::get('getgabung/{CASEID}', 'SiasatController@GetGabung');
    Route::get('gettransaction/{CASEID}', 'SiasatController@gettransaction');
    Route::get('/show-summary/{CASEID}', 'SiasatController@ShowSummary')->name('siasat.showsummary');
    Route::get('/print-summary/{CASEID}', 'SiasatController@PrintSummary')->name('siasat.printsummary');
    Route::get('createattachsiasat/{CASEID}', 'SiasatController@CreateAttachSiasat')->name('siasat.createattachsiasat');
    Route::post('storeattachsiasat/{CASEID}', 'SiasatController@StoreAttachSiasat')->name('siasat.storeattachsiasat');
    Route::get('editattachsiasat/{id}', 'SiasatController@EditAttachSiasat')->name('siasat.editattachsiasat');
    Route::put('updateattachsiasat/{id}', 'SiasatController@UpdateAttachSiasat')->name('siasat.updateattachsiasat');
    Route::get('destroyattachsiasat/{CASEID}', 'SiasatController@DestroyAttachSiasat')->name('siasat.destroyattachsiasat');
    Route::get('create_kes_siasat/{CASEID}', 'SiasatController@CreateKesSiasat')->name('siasat.create_kes_siasat');
    Route::post('storekessiasat/{CASEID}', 'SiasatController@storekessiasat')->name('siasat.storekessiasat');
    Route::get('edit_kes_siasat/{id}', 'SiasatController@EditKesSiasat')->name('siasat.edit_kes_siasat');
    Route::put('updatekessiasat/{id}', 'SiasatController@UpdateKesSiasat')->name('siasat.updatekessiasat');
    Route::get('destroykessiasat/{CASEID}', 'SiasatController@DestroyKesSiasat')->name('siasat.destroykessiasat');
    Route::get('getAttachmentSiasat/{id}', 'SiasatController@getAttachmentSiasat')->name('siasat.getAttachmentSiasat');
    Route::get('getKesSiasat/{id}', 'SiasatController@getKesSiasat')->name('siasat.getKesSiasat');
    Route::get('/getkeslist/{akta}', 'SiasatController@getkeslist');
    Route::post('AjaxValidateKes', 'SiasatController@AjaxValidateKes');
    Route::any('ajaxvalidateeditkes', 'SiasatController@AjaxvalidateEditkes');
//    Route::get('/pdf/{CASEID}', 'SiasatController@pdf');
});

Route::prefix('integritisiasat')->namespace('Integriti')->group(function () {
    Route::get('', 'IntegritiSiasatController@index')->name('integritisiasat.index');
    Route::get('getdatatable', 'IntegritiSiasatController@GetDataTable')->name('integritisiasat.getdatatable');
    Route::get('edit/{CASEID}', 'IntegritiSiasatController@edit')->name('integritisiasat.edit');
    Route::post('siasat/{CASEID}', 'IntegritiSiasatController@siasat')->name('integritisiasat.siasat');
    Route::put('update/{CASEID}', 'IntegritiSiasatController@update')->name('integritisiasat.update');
    Route::get('getsubakta/{AKTA}', 'IntegritiSiasatController@GetSubAkta')->name('integritisiasat.getsubakta');
    Route::get('getattachment/{CASEID}', 'IntegritiSiasatController@getattachment');
    Route::get('getgabung/{CASEID}', 'IntegritiSiasatController@GetGabung');
    Route::get('gettransaction/{CASEID}', 'IntegritiSiasatController@gettransaction');
    Route::get('/show-summary/{CASEID}', 'IntegritiSiasatController@ShowSummary')->name('integritisiasat.showsummary');
    Route::get('/print-summary/{CASEID}', 'IntegritiSiasatController@PrintSummary')->name('integritisiasat.printsummary');
    Route::get('createattachsiasat/{CASEID}', 'IntegritiSiasatController@CreateAttachSiasat')->name('integritisiasat.createattachsiasat');
    Route::post('storeattachsiasat/{CASEID}', 'IntegritiSiasatController@StoreAttachSiasat')->name('integritisiasat.storeattachsiasat');
    Route::get('editattachsiasat/{id}', 'IntegritiSiasatController@EditAttachSiasat')->name('integritisiasat.editattachsiasat');
    Route::put('updateattachsiasat/{id}', 'IntegritiSiasatController@UpdateAttachSiasat')->name('integritisiasat.updateattachsiasat');
    Route::get('destroyattachsiasat/{CASEID}', 'IntegritiSiasatController@DestroyAttachSiasat')->name('integritisiasat.destroyattachsiasat');
    Route::get('create_kes_siasat/{CASEID}', 'IntegritiSiasatController@CreateKesSiasat')->name('integritisiasat.create_kes_siasat');
    Route::post('storekessiasat/{CASEID}', 'IntegritiSiasatController@storekessiasat')->name('integritisiasat.storekessiasat');
    Route::get('edit_kes_siasat/{id}', 'IntegritiSiasatController@EditKesSiasat')->name('integritisiasat.edit_kes_siasat');
    Route::put('updatekessiasat/{id}', 'IntegritiSiasatController@UpdateKesSiasat')->name('integritisiasat.updatekessiasat');
    Route::get('destroykessiasat/{CASEID}', 'IntegritiSiasatController@DestroyKesSiasat')->name('integritisiasat.destroykessiasat');
    Route::get('getAttachmentSiasat/{id}', 'IntegritiSiasatController@getAttachmentSiasat')->name('integritisiasat.getAttachmentSiasat');
    Route::get('getKesSiasat/{id}', 'IntegritiSiasatController@getKesSiasat')->name('integritisiasat.getKesSiasat');
    Route::get('/getkeslist/{akta}', 'IntegritiSiasatController@getkeslist');
    Route::post('AjaxValidateKes', 'IntegritiSiasatController@AjaxValidateKes');
    Route::any('ajaxvalidateeditkes', 'IntegritiSiasatController@AjaxvalidateEditkes');
//    Route::get('/pdf/{CASEID}', 'IntegritiSiasatController@pdf');
});

Route::prefix('pindah')->namespace('Aduan')->group(function () {
    Route::get('getdatatablecase', 'PindahController@getdatatablecase');
    Route::get('getdatatableuser', 'PindahController@GetDataTableUser');
    Route::get('getuserdetail/{id}', 'PindahController@getuserdetail');
    Route::get('getdatatableattachment/{CASEID}', 'PindahController@getdatatableattachment');
    Route::get('getdatatablemergecase/{CASEID}', 'PindahController@getdatatablemergecase');
    Route::get('getdatatabletransaction/{CASEID}', 'PindahController@getdatatabletransaction');
    Route::get('/show-summary/{CASEID}', 'PindahController@ShowSummary')->name('pindah.showsummary');
    Route::get('/print-summary/{CASEID}', 'PindahController@PrintSummary')->name('pindah.printsummary');
    Route::get('editkelompok', 'PindahController@EditKelompok')->name('pindah.editkelompok');
    Route::post('submitkelompok', 'PindahController@SubmitKelompok')->name('pindah.submitkelompok');
});
Route::resource('pindah', 'Aduan\PindahController');

Route::prefix('tugas-semula')->namespace('Aduan')->group(function () {
    Route::get('getdatatablecase', 'TugasSemulaController@getdatatablecase');
    Route::get('getdatatableuser', 'TugasSemulaController@getdatatableuser');
    Route::get('getuserdetail/{id}', 'TugasSemulaController@getuserdetail');
    Route::get('getcmpllist/{CMPLCAT}', 'TugasSemulaController@getcmpllist');
    Route::get('getdatatableattachment/{CASEID}', 'TugasSemulaController@getdatatableattachment');
    Route::get('getdatatablemergecase/{CASEID}', 'TugasSemulaController@getdatatablemergecase');
    Route::get('getdatatabletransaction/{CASEID}', 'TugasSemulaController@getdatatabletransaction');
    Route::get('/show-summary/{CASEID}', 'TugasSemulaController@ShowSummary')->name('tugas-semula.showsummary');
    Route::get('/print-summary/{CASEID}', 'TugasSemulaController@PrintSummary')->name('tugas-semula.printsummary');
});
Route::resource('tugas-semula', 'Aduan\TugasSemulaController');

Route::prefix('bukasemula')->namespace('Aduan')->group(function () {
    Route::get('getdatatablecase', 'BukaSemulaController@getdatatablecase');
    Route::get('getdatatableuser', 'BukaSemulaController@getdatatableuser');
    Route::get('getuserdetail/{id}', 'BukaSemulaController@getuserdetail');
    Route::get('getsubaktalist/{AKTA}', 'BukaSemulaController@getsubaktalist');
    Route::get('getdatatableattachment/{CASEID}', 'BukaSemulaController@getdatatableattachment');
    Route::get('getdatatablemergecase/{CASEID}', 'BukaSemulaController@getdatatablemergecase');
    Route::get('getdatatabletransaction/{CASEID}', 'BukaSemulaController@getdatatabletransaction');
    Route::get('/show-summary/{CASEID}', 'BukaSemulaController@ShowSummary')->name('bukasemula.showsummary');
    Route::get('/print-summary/{CASEID}', 'BukaSemulaController@PrintSummary')->name('bukasemula.printsummary');
});
Route::resource('bukasemula', 'Aduan\BukaSemulaController');

Route::prefix('integritibukasemula')->namespace('Integriti')->group(function () {
    Route::get('getdatatablecase', 'IntegritiBukaSemulaController@getdatatablecase');
    Route::get('getdatatableuser', 'IntegritiBukaSemulaController@getdatatableuser');
    Route::get('getuserdetail/{id}', 'IntegritiBukaSemulaController@getuserdetail');
    Route::get('getsubaktalist/{AKTA}', 'IntegritiBukaSemulaController@getsubaktalist');
    Route::get('getdatatableattachment/{CASEID}', 'IntegritiBukaSemulaController@getdatatableattachment');
    Route::get('getdatatablemergecase/{CASEID}', 'IntegritiBukaSemulaController@getdatatablemergecase');
    Route::get('getdatatabletransaction/{CASEID}', 'IntegritiBukaSemulaController@getdatatabletransaction');
});
Route::resource('integritibukasemula', 'Integriti\IntegritiBukaSemulaController');

Route::group(['prefix' => 'tutup', 'namespace' => 'Aduan'], function () {
    Route::resource('/', 'TutupController');
    Route::get('get_datatable', 'TutupController@getDataTable');
    Route::get('penutupan_aduan/{CA_CASEID}', 'TutupController@PenutupanAduan')->name('tutup.penutupanaduan');
    Route::get('/gettransaction/{CD_CASEID}', 'TutupController@gettransaction');
    Route::get('/getattachment/{CD_CASEID}', 'TutupController@getattachment');
    Route::get('/getgabungkes/{CA_CASEID}', 'TutupController@getGabungKes');
    Route::post('/tutup-aduan/{CA_CASEID}', 'TutupController@TutupAduan');
    Route::get('/show-summary/{CASEID}', 'TutupController@ShowSummary')->name('tutup.showsummary');
    Route::get('/print-summary/{CASEID}', 'TutupController@PrintSummary')->name('tutup.printsummary');
    Route::get('getdatatableuser', 'TutupController@getdatatableuser');
    Route::get('getuserdetail/{id}', 'TutupController@getuserdetail');
    Route::get('getgabung/{id}', 'TutupController@getgabung')->name('tutup.getgabung');
});

Route::group(['prefix' => 'integrititutup', 'namespace' => 'Integriti'], function () {
    Route::resource('/', 'IntegritiTutupController');
    Route::get('get_datatable', 'IntegritiTutupController@getDataTable');
    Route::get('penutupan_aduan/{id}', 'IntegritiTutupController@PenutupanAduan')->name('integrititutup.penutupanaduan');
    Route::get('/gettransaction/{CD_CASEID}', 'IntegritiTutupController@gettransaction');
    Route::get('/getattachment/{CD_CASEID}', 'IntegritiTutupController@getattachment');
    Route::get('/getgabungkes/{CA_CASEID}', 'IntegritiTutupController@getGabungKes');
    Route::post('/tutup-aduan/{id}', 'IntegritiTutupController@TutupAduan');
    Route::get('getdatatableuser', 'IntegritiTutupController@getdatatableuser');
    Route::get('getuserdetail/{id}', 'IntegritiTutupController@getuserdetail');
    Route::get('getgabung/{id}', 'IntegritiTutupController@getgabung')->name('tutup.getgabung');
});

Route::group(['prefix' => 'pengurusanintegriti', 'namespace' => 'Integriti'], function () {
    Route::resource('/', 'PengurusanIntegritiController');
    Route::get('get_datatable_integriti', 'PengurusanIntegritiController@getDataTableIntegriti');
    Route::get('pengurusan_aduan/{IN_CASEID}', 'PengurusanIntegritiController@PengurusanAduan')->name('pengurusanintegriti.pengurusanaduan');
    Route::post('/tukar-status/{IN_CASEID}', 'PengurusanIntegritiController@TukarStatus');
});

Route::get('menucms/getdatatable', 'MenuCmsController@getdatatable');
Route::resource('menucms', 'MenuCmsController');

Route::prefix('bpa')->namespace('Laporan')->group(function () {
    Route::get('penerimaan-penyelesaian-bulan', 'BpaController@PenerimaanPenyelesaianBulan');
    Route::get('penerimaan-penyelesaian-kumulatif', 'BpaController@PenerimaanPenyelesaianKumulatif');
    Route::get('sumber-penerimaan-bulan', 'BpaController@SumberPenerimaanBulan');
    Route::get('sumber-penerimaan-kumulatif', 'BpaController@SumberPenerimaanKumulatif');
    Route::get('tempoh-penyelesaian-bulan', 'BpaController@TempohPenyelesaianBulan');
    Route::get('tempoh-penyelesaian-kumulatif', 'BpaController@TempohPenyelesaianKumulatif');
});
Route::prefix('sumberaduan')->namespace('Laporan')->group(function () {
    Route::get('reportbyyear', 'SumberAduanController@reportbyyear');
    Route::get('reportbyyear1/{year}/{dept}/{state}/{rcvtyp}/{month}', 'SumberAduanController@reportbyyear1')->name('sumberaduan.reportbyyear1');
    Route::get('sumberaduannegeri', 'SumberAduanController@sumberaduannegeri');
    Route::get('sumberaduannegeri1/{year}/{dept}/{rcvtyp}/{state}', 'SumberAduanController@sumberaduannegeri1')->name('sumberaduannegeri1');
    Route::get('senaraikes/{sYear}/{month}/{type}/{sbrn}/{sdepart}', 'SumberAduanController@listkes');
//    Route::get('get_datatable/{$month,$sYear,$type,$sdepart,$sbrn}', 'SumberAduanController@getDataTable');
    Route::get('get_datatable/{sYear}/{month}/{type}/{sbrn}/{sdepart}', 'SumberAduanController@getDataTable');
});
Route::prefix('laporanlainlain')->namespace('Laporan')->group(function () {
    Route::get('kategori', 'LaporanLainlainController@kategori');
    Route::get('kategori1/{CA_RCVDT_dri}/{CA_RCVDT_lst}/{dept}/{state}/{cmplcat}', 'LaporanLainlainController@kategori1')->name('kategori1');

    Route::get('laporan_negeri_status', 'LaporanLainlainController@negeristatus');
    Route::get('laporan_negeri_status1', 'LaporanLainlainController@negeristatus1')->name('laporan_negeri_status1');;
    Route::get('capaian_pelanggan', 'LaporanLainlainController@capaianpelangan');
    Route::get('laporan_pegawai', 'LaporanLainlainController@laporanpegawai');
    Route::get('pegawai1', 'LaporanLainlainController@laporanpegawai1')->name('pegawai1');
    Route::get('laporanlanjutan', 'LaporanLainlainController@laporanlanjutan');
    Route::get('laporanjantina', 'LaporanLainlainController@laporanjantina');
    Route::get('laporanwarganegara', 'LaporanLainlainController@laporanwarganegara');
    Route::get('matrix', 'LaporanLainlainController@matrix');
    Route::get('matrix1', 'LaporanLainlainController@matrix1');
    Route::get('akta', 'LaporanLainlainController@akta');
    Route::get('akta1', 'LaporanLainlainController@akta1')->name('akta1');
    Route::get('pembekalperkhidmatan', 'LaporanLainlainController@PembekalPerkhidmatan');
    Route::get('pembekalperkhidmatan1/{from}/{to}/{provider}/{state}', 'LaporanLainlainController@PembekalPerkhidmatan1')->name('pembekalperkhidmatan1');
    Route::get('senaraikes', 'LaporanLainlainController@listCat');
    Route::get('get_datatable', 'LaporanLainlainController@getDataTable');
    Route::get('getcawangan/{BR_BRNCD}', 'LaporanLainlainController@Getcawangan');
    Route::get('getcawangan/{BR_BRNCD}/{placeholder}', 'LaporanLainlainController@Getcawangan');
    Route::get('getcatlist/{catlist}', 'LaporanLainlainController@GetCatList');
    Route::get('printexcel', 'LaporanLainlainController@DwnloadExcel');
    Route::get('callcenter', 'LaporanLainlainController@CallCenter');
    Route::get('callcenter1/{year}/{month}/{userid}', 'LaporanLainlainController@CallCenter1')->name('laporanlainlain.callcenter1');
    Route::get('ezstar', 'LaporanLainlainController@EzStar');
    Route::get('ezstar1/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'LaporanLainlainController@EzStar1')->name('laporanlainlain.ezstar1');
    Route::get('rating', 'LaporanLainlainController@Rating');
    Route::get('rating1/{year}/{month}/{state}/{brn}/{type}/{rate}', 'LaporanLainlainController@Rating1')->name('laporanlainlain.rating1');
    Route::get('aduan-menghasilkan-kes', 'LaporanLainlainController@AduanMenghasilkanKes');
    Route::get('aduan-menghasilkan-kes1/{year}/{rcvtyp}/{category}', 'LaporanLainlainController@AduanMenghasilkanKes1')->name('laporanlainlain.aduanmenghasilkankes1');
});
Route::prefix('penerimaanpenyelesaianaduan')->namespace('Laporan')->group(function () {
    Route::get('pengagihanaduan', 'PenerimaanPenyelesaianAduanController@pengagihanaduan');
//    Route::get('selesaiaduannegeritahun', 'PenerimaanPenyelesaianAduanController@selesaiaduannegeritahun');
    Route::get('selesaiaduannegeritahun', 'PenerimaanPenyelesaianAduanController@selesaiAduanTahunan');
    Route::get('laporan_kategori_BPGK', 'PenerimaanPenyelesaianAduanController@kategoriBpgk');
    Route::get('laporan_kategori_BPGK/dd', 'PenerimaanPenyelesaianAduanController@ddKategoriBpgk');
    Route::get('laporan_subkategori_BPGK', 'PenerimaanPenyelesaianAduanController@subkategoriBpgk');
    Route::get('laporan_subkategori_BPGK/dd', 'PenerimaanPenyelesaianAduanController@ddSubKategoriBpgk');
    Route::get('getbranchlist/{BR_BRNCD}', 'PenerimaanPenyelesaianAduanController@GetBranchList');
    Route::get('getcategorylist/{bahagianCd}', 'PenerimaanPenyelesaianAduanController@GetCategoryList');
    Route::get('senaraipindah/{DATEDIFF}/{BR_STATECD}/{CA_RCVDT_YEAR}/{CA_RCVDT_MONTH_FROM}/{CA_RCVDT_MONTH_TO}/{CA_DEPTCD}', 'PenerimaanPenyelesaianAduanController@senaraipindah')->name('senaraipindah');
    Route::get('senaraiselesai/{DATEDIFF}/{BR_STATECD}/{CA_RCVDT_FROM}/{CA_RCVDT_TO}/{CA_DEPTCD}', 'PenerimaanPenyelesaianAduanController@senaraiSelesai');
//    Route::get('senaraiselesai', 'PenerimaanPenyelesaianAduanController@senaraiSelesai')->name('senaraiselesai');
    Route::get('senaraiselesaimengikuttempoh/{DATEDIFF}/{CA_RCVDT_FROM}/{CA_RCVDT_TO}/{CA_DEPTCD}', 'PenerimaanPenyelesaianAduanController@senaraiSelesaiMengikutTempoh');
    Route::get('senaraiselesaimengikutnegeri/{BR_STATECD}/{CA_RCVDT_FROM}/{CA_RCVDT_TO}/{CA_DEPTCD}', 'PenerimaanPenyelesaianAduanController@senaraiSelesaiMengikutNegeri');
    Route::get('printexcel/{Parameter}', 'PenerimaanPenyelesaianAduanController@PrintExcel');
    Route::get('getdeptlistbystate/{BR_STATECD}', 'PenerimaanPenyelesaianAduanController@getdeptlistbystate');
    Route::get('laporan_cara_penerimaan', 'PenerimaanPenyelesaianAduanController@caraPenerimaan');
    Route::get('laporan_cara_penerimaan/dd', 'PenerimaanPenyelesaianAduanController@ddCaraPenerimaan');
});
Route::prefix('logmyidentity')->group(function () {
    Route::get('openfile/{LogFile}', 'LogMyIdentityController@openFile');
    Route::get('carian', 'LogMyIdentityController@carianIdentity');
});

Route::prefix('pembandinganaduan')->namespace('Laporan')->group(function () {
    Route::get('kategori', 'PembandinganAduanController@kategori');
    Route::get('laporannegeri', 'PembandinganAduanController@laporannegeri');
    Route::get('laporannegeri1/{CA_RCVDT_YEAR?}/{CA_RCVDT_MONTH_FROM?}/{CA_RCVDT_MONTH_TO?}/{CA_RCVDT_MONTH?}/{CA_DEPTCD?}/{BR_STATECD?}', 'PembandinganAduanController@laporannegeri1')->name('laporannegeri1');
    Route::get('jumlahaduan', 'PembandinganAduanController@jumlahaduan');
//    Route::get('jumlahaduan1', 'PembandinganAduanController@jumlahaduan1');
    Route::get('jumlahaduan1/{CA_RCVDT_YEAR_FROM?}/{CA_RCVDT_YEAR_TO?}/{CA_RCVDT_YEAR?}/{CA_DEPTCD?}/{BR_STATECD?}/{CA_INVSTS?}', 'PembandinganAduanController@jumlahaduan1')->name('jumlahaduan1');
    Route::get('kategoritahun', 'PembandinganAduanController@kategoritahun');
//    Route::get('kategoritahun1', 'PembandinganAduanController@kategoritahun1');
    Route::get('kategoritahun1/{CA_RCVDT_YEAR_FROM?}/{CA_RCVDT_YEAR_TO?}/{CA_RCVDT_YEAR?}/{CA_DEPTCD?}/{CA_STATECD?}/{CA_CMPLCAT?}', 'PembandinganAduanController@kategoritahun1')->name('kategoritahun1');
    Route::get('laporannegeri_bytarikh', 'PembandinganAduanController@negeribytarikh');
//    Route::get('laporannegeri_bytarikh1', 'PembandinganAduanController@negeribytarikh1');
    Route::get('laporannegeri_bytarikh1/{CA_RCVDT_YEAR?}/{CA_RCVDT_MONTH?}/{BR_STATECD?}/{CA_DEPTCD?}/{CA_CMPLCAT?}/{CA_RCVDT_DAY?}', 'PembandinganAduanController@negeribytarikh1')->name('negeribytarikh1');
    Route::get('getcategorylist/{DEPTCD}', 'PembandinganAduanController@getcategorylist');
    Route::get('senarai', 'PembandinganAduanController@senarai');
    Route::get('getdeptlistbystate/{BR_STATECD}', 'PembandinganAduanController@getdeptlistbystate');
    Route::get('laporanjumlahkerugiankategori', 'PembandinganAduanController@laporanjumlahkerugiankategori');
    Route::get('laporanjumlahkerugiansubkategori', 'PembandinganAduanController@laporanjumlahkerugiansubkategori');
});

Route::prefix('laporanpertanyaan')->namespace('Laporan')->group(function () {
    Route::get('statustahun', 'PertanyaanController@statustahun');
    Route::get('statustahun/{year}/{month}/{status}', 'PertanyaanController@statustahun1')->name('laporanpertanyaan.statustahun1');
    Route::get('reportbyyear', 'PertanyaanController@reportbyyear');
    Route::get('reportbyyear1/{year}/{rcvtyp}/{month}', 'PertanyaanController@reportbyyear1')->name('reportbyyear1');
    Route::get('reportbycategory', 'PertanyaanController@reportbycategory');
    Route::get('reportbycategory1/{year}/{rcvtyp}/{month}', 'PertanyaanController@reportbycategory1')->name('reportbycategory1');
    Route::get('reportpegawai', 'PertanyaanController@reportpegawai');
    Route::get('reportpegawai1/{year}/{month}/{id}/{state}/{brn}', 'PertanyaanController@reportpegawai1')->name('reportpegawai1');
});

Route::prefix('laporanisobulanan')->namespace('Laporan')->group(function () {
    Route::get('perbandingansepuluhkategori', 'ISOBulananController@perbandingansepuluhkategori');
    Route::get('perbandingansepuluhkategoridd/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOBulananController@perbandingansepuluhkategoridd')->name('isobulanan.perbandingansepuluhkategoridd');
    Route::get('aduannegeridankategori', 'ISOBulananController@aduannegeridankategori');
    Route::get('aduannegeridankategoridd/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOBulananController@aduannegeridankategoridd')->name('isobulanan.aduannegeridankategoridd');
    Route::get('aduanallikutnegeribahagian', 'ISOBulananController@aduanallikutnegeribahagian');
    Route::get('aduanallikutnegeribahagiandd/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOBulananController@aduanallikutnegeribahagiandd')->name('isobulanan.aduanallikutnegeribahagiandd');
    Route::get('aduanallikutnegeribahagian2', 'ISOBulananController@aduanallikutnegeribahagian2');
    Route::get('aduanallikutnegeribahagian2dd/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOBulananController@aduanallikutnegeribahagiandd2')->name('isobulanan.aduanallikutnegeribahagiandd2');
    Route::get('aduanallikutnegeribahagianpercent', 'ISOBulananController@aduanallikutnegeribahagianpercent');
    Route::get('aduanallikutnegeribahagianddpercent/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOBulananController@aduanallikutnegeribahagianddpercent')->name('isobulanan.aduanallikutnegeribahagianddpercent');
});

Route::prefix('laporanisokumulatif')->namespace('Laporan')->group(function () {
    Route::get('perbandingansepuluhkategori', 'ISOKumulatifController@perbandingansepuluhkategori');
    Route::get('perbandingansepuluhkategoridd/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOKumulatifController@perbandingansepuluhkategoridd')->name('isokumulatif.perbandingansepuluhkategoridd');
    Route::get('aduannegeridankategori', 'ISOKumulatifController@aduannegeridankategori');
    Route::get('aduannegeridankategoridd/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOKumulatifController@aduannegeridankategoridd')->name('isokumulatif.aduannegeridankategoridd');
    Route::get('aduanallikutnegeribahagian', 'ISOKumulatifController@aduanallikutnegeribahagian');
    Route::get('aduanallikutnegeribahagiandd/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOKumulatifController@aduanallikutnegeribahagiandd')->name('isokumulatif.aduanallikutnegeribahagiandd');
    Route::get('aduanallikutnegeribahagian2', 'ISOKumulatifController@aduanallikutnegeribahagian2');
    Route::get('aduanallikutnegeribahagian2dd/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOKumulatifController@aduanallikutnegeribahagiandd2')->name('isokumulatif.aduanallikutnegeribahagiandd2');
    Route::get('aduanallikutnegeribahagianpercent', 'ISOKumulatifController@aduanallikutnegeribahagianpercent');
    Route::get('aduanallikutnegeribahagianddpercent/{year}/{month}/{state}/{brn_cd}/{userid}/{status}', 'ISOKumulatifController@aduanallikutnegeribahagianddpercent')->name('isokumulatif.aduanallikutnegeribahagianddpercent');
});

Route::namespace('Laporan')->group(function() {
    Route::get('senaraipenggunafok', 'LaporanFokController@senaraipenggunafok');
    Route::get('senaraipenggunafok/dd', 'LaporanFokController@senaraipenggunafokdd');
    Route::get('fok2', 'Fok2Controller@fok2');
    Route::get('jenisbarangan', 'LaporanJenisBaranganController@jenisbarangan');
    Route::get('aduankes', 'LaporanAduanKesController@aduankes');
    Route::get('aduankes/dd', 'LaporanAduanKesController@aduankesdd');
    Route::get('aduankes/dddatatable', 'LaporanAduanKesController@aduankesdddatatable');
    Route::get('firstaction', 'ReportFirstActionController@firstaction');
});

Route::prefix('laporanintegriti')->namespace('Laporan')->group(function () {
    Route::get('senaraiaduan', 'IntegritiController@SenaraiAduan');
    Route::get('statistikaduan', 'IntegritiController@StatistikAduan');
    Route::get('statistikaduanmengikutkategori', 'IntegritiController@StatistikAduanMengikutKategori');
    Route::get('statistikaduanmengikutstatus', 'IntegritiController@StatistikAduanMengikutStatus');
});

Route::namespace('Laporan')->group(function () {
    Route::get('integritilaporancawangan', 'IntegritiLaporanCawanganController@index');
    Route::prefix('report')->group(function () {
        Route::prefix('integrity')->namespace('Integrity')->group(function () {
            Route::get('branch', 'ReportIntegrityBranchController@index');
        });
    });
});

Route::prefix('laporan')->namespace('Laporan')->group(function () {
    Route::prefix('feedback')->namespace('Feedback')->group(function () {
        Route::get('r1', 'R1Controller@index')->name('laporan.feedback.r1.index');
        Route::get('r2', 'R2Controller@index')->name('laporan.feedback.r2.index');
        Route::get('r3', 'R3Controller@index')->name('laporan.feedback.r3.index');
    });
});

Route::prefix('pertanyaan-public')->namespace('Pertanyaan')->group(function () {
    Route::get('getdatattable', 'PertanyaanPublicController@GetDatatable');
    Route::get('getdatattable_transaction/{ASKID}', 'PertanyaanPublicController@GetDatatableTransaction');
    Route::get('attachment/{id}', 'PertanyaanPublicController@Attachment')->name('pertanyaan.attachment');
    Route::get('preview/{id}', 'PertanyaanPublicController@Preview')->name('pertanyaan.preview');
    Route::post('submit/{id}', 'PertanyaanPublicController@Submit')->name('pertanyaan.submit');
    Route::get('success/{id}', 'PertanyaanPublicController@Success')->name('pertanyaan.success');
    Route::get('/check/{AS_ASKID}', 'PertanyaanPublicController@Check')->name('pertanyaan.check');
    Route::get('/showsummary/{AS_ASKID}', 'PertanyaanPublicController@ShowSummary')->name('pertanyaan.showsummary');
    Route::get('/printsummary/{AS_ASKID}', 'PertanyaanPublicController@PrintSummary')->name('pertanyaan.printsummary');
    Route::get('/printsuccess/{AS_ASKID}', 'PertanyaanPublicController@PrintSuccess')->name('pertanyaan.printsuccess');
});
Route::resource('pertanyaan-public', 'Pertanyaan\PertanyaanPublicController');

Route::prefix('pertanyaan-public-doc')->namespace('Pertanyaan')->group(function () {
    Route::get('GetDataTable/{id}', 'PertanyaanPublicDocController@GetDataTable');
    Route::get('create/{id}', 'PertanyaanPublicDocController@create')->name('pertanyaan-public-doc.create');
    Route::post('/{id}', 'PertanyaanPublicDocController@store')->name('pertanyaan-public-doc.store');
    Route::get('edit/{id}', 'PertanyaanPublicDocController@edit')->name('pertanyaan-public-doc.edit');

//    Route::get('edit/{id}', 'PublicCaseDocController@edit')->name('public-case-doc.edit');
    Route::put('update/{id}', 'PertanyaanPublicDocController@update')->name('pertanyaan-public-doc.update');

    Route::get('delete/{id}', 'PertanyaanPublicDocController@destroy')->name('pertanyaan-public-doc.destroy');
});

//Route::resource('pertanyaan-public-doc', 'Pertanyaan\PertanyaanPublicDocController');

Route::prefix('pertanyaan-admin')->namespace('Pertanyaan')->group(function () {
    Route::get('getdatattable', 'PertanyaanAdminController@GetDatatable');
    Route::get('getdatattable_transaction/{ASKID}', 'PertanyaanAdminController@GetDatatableTransaction');
    Route::get('getdatattable_askemail/{ASKID}', 'PertanyaanAdminController@GetDatatableEmail');
    Route::get('show-summary/{ASKID}', 'PertanyaanAdminController@ShowSummary')->name('pertanyaan-admin.showsummary');
    Route::get('print-summary/{ASKID}', 'PertanyaanAdminController@PrintSummary')->name('pertanyaan-admin.printsummary');
    Route::get('attachment/{id}', 'PertanyaanAdminController@attachment')->name('pertanyaan-admin.attachment');
    Route::get('editadmin/{id}', 'PertanyaanAdminController@editadmin')->name('pertanyaan-admin.editadmin');
    Route::put('updateadmin/{id}', 'PertanyaanAdminController@updateadmin')->name('pertanyaan-admin.updateadmin');
    Route::get('preview/{id}', 'PertanyaanAdminController@preview')->name('pertanyaan-admin.preview');
    Route::post('submit/{id}', 'PertanyaanAdminController@submit')->name('pertanyaan-admin.submit');
    Route::get('/getcmpllist/{cat_cd}', 'PertanyaanAdminController@GetCmplList');
});
Route::resource('pertanyaan-admin', 'Pertanyaan\PertanyaanAdminController');

Route::prefix('pertanyaan-admin-doc')->namespace('Pertanyaan')->group(function () {
    Route::get('getdatatable/{id}', 'PertanyaanAdminDocController@getdatatable');
    Route::get('create/{id}', 'PertanyaanAdminDocController@create')->name('pertanyaan-admin-doc.create');
    Route::post('ajaxvalidatestoreattachment', 'PertanyaanAdminDocController@ajaxvalidatestoreattachment');
    Route::post('/{id}', 'PertanyaanAdminDocController@store')->name('pertanyaan-admin-doc.store');
    Route::get('edit/{id}', 'PertanyaanAdminDocController@edit')->name('pertanyaan-admin-doc.edit');
    Route::any('ajaxvalidateupdateattachment', 'PertanyaanAdminDocController@ajaxvalidateupdateattachment');
    Route::put('update/{id}', 'PertanyaanAdminDocController@update')->name('pertanyaan-admin-doc.update');
    Route::any('delete/{id}', 'PertanyaanAdminDocController@destroy')->name('pertanyaan-admin-doc.destroy');
});

Route::prefix('pertanyaan-admin-doc-jawapan')->namespace('Pertanyaan')->group(function () {
    Route::get('getdatatable/{id}', 'PertanyaanAdminDocJawapanController@getdatatable');
    Route::get('create/{id}', 'PertanyaanAdminDocJawapanController@create')->name('pertanyaan-admin-doc-jawapan.create');
    Route::post('/{id}', 'PertanyaanAdminDocJawapanController@store')->name('pertanyaan-admin-doc-jawapan.store');
    Route::get('edit/{id}', 'PertanyaanAdminDocJawapanController@edit')->name('pertanyaan-admin-doc-jawapan.edit');
    Route::put('update/{id}', 'PertanyaanAdminDocJawapanController@update')->name('pertanyaan-admin-doc-jawapan.update');
    Route::any('delete/{id}', 'PertanyaanAdminDocJawapanController@destroy')->name('pertanyaan-admin-doc-jawapan.destroy');
});

Route::group(['namespace' => 'Pertanyaan'], function () {
    Route::get('askanswertemplate/dt', 'AskAnswerTemplateController@dt');
    Route::resource('askanswertemplate','AskAnswerTemplateController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update']]);
    Route::resource('inquiry/answertemplates', 'AnswerTemplateController', ['only' => ['index', 'show']]);
    Route::resource('inquiry/consumercomplaints', 'ConsumerComplaintController', ['only' => ['index', 'show']]);
});

Route::any('fail-kes/storevalidate', 'FailKesController@storevalidate')->name('fail-kes.storevalidate');
Route::get('fail-kes/getdatatable', 'FailKesController@GetDataTable');
Route::get('fail-kes/migrate/{id}', 'FailKesController@migrate')->name('fail-kes.migrate');
Route::get('fail-kes/checkfile/{id}', 'FailKesController@CheckFile')->name('fail-kes.checkfile');
Route::get('fail-kes/report', 'FailKesController@Report')->name('fail-kes.report');
Route::get('fail-kes/getdatareport', 'FailKesController@GetDataReport')->name('fail-kes.getdatareport');
Route::resource('fail-kes', 'FailKesController');

Route::get('tips/dt', 'TipController@dt')->name('tips.dt');
Route::resource('tips', 'TipController');

Route::get('carian/admin', 'Aduan\CarianController@admin');
Route::get('carian/getdatatableadmin', 'Aduan\CarianController@GetDataTableAdmin');
Route::get('carian/getdatatablepegawai', 'Aduan\CarianController@GetDataTablePegawai');
Route::get('carian/showinvby/{id}', 'Aduan\CarianController@ShowInvBy')->name('carian.showinvby');
Route::get('carian/getsearchfieldcd/{searchfieldcd}', 'Aduan\CarianController@GetSearchFieldCd');

Route::get('dashboard/status-aduan', 'DashboardController@StatusAduan');
Route::get('dashboard/aduan-by-mnth', 'DashboardController@AduanIkutBulan');

Route::get('test/send-email', 'TestController@SendEmail');
Route::get('test/update-sub-akta', 'TestController@UpdateSubAkta');
Route::get('test/update-ip-no', 'TestController@UpdateIpNo');
Route::get('test/update-password', 'TestController@UpdatePassword');
Route::post('testapp/sendemail', 'TestAppController@sendemail');
Route::resource('testapp', 'TestAppController');
Route::get('testappdoc/getdatatable', 'TestAppDocController@getdatatable');
Route::resource('testappdoc', 'TestAppDocController');

Route::get('portalcms/getdatatable', 'PortalCmsController@getdatatable');
Route::get('portalcms/list-content/{cat}', 'PortalCmsController@ListContent')->name('portalcms.listcontent');
Route::get('portalcms/getlistcontent/{cat}', 'PortalCmsController@GetListContent')->name('portalcms.getlistcontent');
Route::get('portalcms/{cat}/create-content', 'PortalCmsController@create')->name('portalcms.createcontent');
Route::post('portalcms/{cat}/store-content', 'PortalCmsController@store')->name('portalcms.storecontent');
Route::resource('portalcms', 'PortalCmsController');

Route::prefix('kemaskini')->namespace('Aduan')->group(function () {
    Route::get('getdatatable', 'KemaskiniController@getdatatable');
    Route::get('docbuktiaduan/{id}', 'KemaskiniController@docbuktiaduan')->name('kemaskini.docbuktiaduan');
    Route::get('preview/{id}', 'KemaskiniController@preview')->name('kemaskini.preview');
    Route::any('submit/{id}', 'KemaskiniController@submit')->name('kemaskini.submit');
});
Route::resource('kemaskini', 'Aduan\KemaskiniController');

Route::prefix('kemaskini-doc')->namespace('Aduan')->group(function () {
    Route::get('getdatatable/{id}', 'KemaskiniDocController@getdatatable');
    Route::get('create/{id}', 'KemaskiniDocController@create')->name('kemaskini-doc.create');
    Route::post('store', 'KemaskiniDocController@store')->name('kemaskini-doc.store');
    Route::get('edit/{id?}', 'KemaskiniDocController@edit')->name('kemaskini-doc.edit');
    Route::put('update/{id}', 'KemaskiniDocController@update')->name('kemaskini-doc.update');
    Route::any('delete/{id}', 'KemaskiniDocController@destroy')->name('kemaskini-doc.destroy');
});

Route::resource('dataentry', 'Aduan\DataEntryController');

Route::get('dataentrydoc/createdoc/{id}', 'Aduan\DataEntryDocController@create')->name('dataentrydoc.createdoc');
Route::get('dataentrydoc/getdatadoc/{id}', 'Aduan\DataEntryDocController@getdatadoc')->name('dataentrydoc.getdatadoc');
Route::get('dataentrydoc/editdoc/{id}', 'Aduan\DataEntryDocController@edit')->name('dataentrydoc.editdoc');
Route::resource('dataentrydoc', 'Aduan\DataEntryDocController');

Route::get('dataentrydocsiasat/createdoc/{id}', 'Aduan\DataEntryDocSiasatController@create')->name('dataentrydocsiasat.createdoc');
Route::get('dataentrydocsiasat/getdatadoc/{id}', 'Aduan\DataEntryDocSiasatController@getdatadoc')->name('dataentrydocsiasat.getdatadoc');
Route::get('dataentrydocsiasat/editdoc/{id}', 'Aduan\DataEntryDocSiasatController@edit')->name('dataentrydocsiasat.editdocsiasat');
Route::resource('dataentrydocsiasat', 'Aduan\DataEntryDocSiasatController');

Route::group(['namespace' => 'Aduan'], function () {
    Route::get('casereasontemplates/dt', 'CaseReasonTemplateController@dt');
    Route::resource('casereasontemplates', 'CaseReasonTemplateController');
});

Route::group(['namespace' => 'CaseEnquiryPaper', 'prefix' => 'caseenquirypaper', 'as' => 'caseenquirypaper.'], function () {
    Route::get('infos/dt', 'InfoController@dt')->name('infos.dt');
    Route::resource('infos', 'InfoController');
    // Route::get('dataentries/dtcaseact', 'DataEntryController@dtcaseact')->name('dataentries.dtcaseact');
    Route::get('dataentries/getcaseactdetail/{epno}', 'DataEntryController@getcaseactdetail')->name('dataentries.getcaseactdetail');
    Route::get('dataentries/dt', 'DataEntryController@dt')->name('dataentries.dt');
    Route::resource('dataentries', 'DataEntryController');
    Route::resource('dataentries.processes', 'DataEntryProcessController');
    Route::get('assignments/dt', 'AssignmentController@dt')->name('assignments.dt');
    Route::resource('assignments', 'AssignmentController');
    Route::get('investigations/dt', 'InvestigationController@dt')->name('investigations.dt');
    Route::resource('investigations', 'InvestigationController');
    Route::get('closures/dt', 'ClosureController@dt')->name('closures.dt');
    Route::resource('closures', 'ClosureController');
});
Route::group(['namespace' => 'EnquiryPaper', 'prefix' => 'enquirypaper', 'as' => 'enquirypaper.', 'middleware' => 'auth'], function () {
    Route::get('dataentry/{dataentry}/loots/dt', 'DataEntryLootController@dt')->name('dataentry.loots.dt');
    Route::resource('dataentry.loots', 'DataEntryLootController');
    Route::resource('dataentry/step4', 'DataEntryStep4Controller', ['only' => ['show', 'edit', 'update'], 'as' => 'dataentry']);
    // Route::get('distributions/dt', 'DistributionController@dt')->name('distributions.dt');
    // Route::resource('distributions', 'DistributionController');
    Route::get('reassignments/dt', 'ReAssignmentController@dt')->name('reassignments.dt');
    Route::resource('reassignments', 'ReAssignmentController', ['only' => ['index', 'edit', 'update']]);
    // Route::get('reopens/dt', 'ReopenController@dt')->name('reopens.dt');
    // Route::resource('reopens', 'ReopenController');
    Route::resource('users', 'UserController', ['only' => ['index', 'show']]);
});
Route::group(['namespace' => 'CaseAct', 'middleware' => 'auth'], function () {
    Route::get('caseacts/dt', 'CaseActController@dt')->name('caseacts.dt');
    Route::resource('caseacts', 'CaseActController', ['only' => ['show']]);
});
Route::group(['namespace' => 'CaseEnquiryPaper', 'prefix' => 'caseact'], function () {
    Route::get('dt', 'CaseActController@dt');
    Route::get('getcaseactdetail/{epno}', 'CaseActController@getcaseactdetail');
});

Route::prefix('sas-report')->namespace('Laporan')->group(function () {
    Route::get('cara-penerimaan', 'SasController@CaraPenerimaan');
    Route::get('cara-penerimaan1/{year}/{rcvtyp}/{month}', 'SasController@CaraPenerimaan1')->name('sasreport.carapenerimaan1');
    Route::get('menghasilkan-kes', 'SasController@MenghasilkanKes');
    Route::get('menghasilkan-kes1/{year}/{rcvtyp}/{category}', 'SasController@MenghasilkanKes1')->name('sasreport.menghasilkankes1');
});

Route::group(['prefix' => 'laporan', 'namespace' => 'Laporan', 'as' => 'laporan.'], function () {
    Route::get('list', 'ReportListController@index')->name('list');
    Route::group(['prefix' => 'ad51', 'namespace' => 'Ad51', 'as' => 'ad51.'], function () {
        Route::get('report1', 'Report1Controller@index')->name('report1');
        Route::get('report2', 'Report2Controller@index')->name('report2');
    });
    Route::group(['prefix' => 'ad52', 'namespace' => 'Ad52', 'as' => 'ad52.'], function () {
        Route::get('list', 'ListController@index')->name('list');
        Route::get('list/dt', 'ListController@dt')->name('list.dt');
        Route::get('report1', 'Report1Controller@index')->name('report1');
        Route::get('report2', 'Report2Controller@index')->name('report2');
        Route::get('report2new', 'Report2NewController@index')->name('report2new');
        Route::get('report3', 'Report3Controller@index')->name('report3');
        Route::get('report4', 'Report4Controller@index')->name('report4');
        Route::get('report5', 'Report5Controller@index')->name('report5');
        Route::get('report6', 'Report6Controller@index')->name('report6');
        Route::get('report7', 'Report7Controller@index')->name('report7');
        Route::get('report8', 'Report8Controller@index')->name('report8');
        Route::get('report9', 'Report9Controller@index')->name('report9');
    });
});

Route::group(['prefix' => 'report', 'namespace' => 'Report', 'middleware' => 'auth'], function () {
    Route::get('ad52/report2', 'Ad52\Report2Controller@index')->name('report.ad52.report2');
});

Route::prefix('portal-main')->group(function () {
    Route::get('index', 'PortalMainController@index');
    Route::get('getdatatable', 'PortalMainController@GetDataTable');
    Route::get('edit/{id}', 'PortalMainController@edit');
    Route::put('update/{id}', 'PortalMainController@update');
});

Route::group(['prefix' => 'settings'], function () {
    Route::get('edit', 'SettingsController@edit')->name('settings.edit');
    Route::post('update', 'SettingsController@update')->name('settings.update');
    Route::get('changeenv/{key}/{value}', 'SettingsController@changeEnv');
});

// open - api
// to call cross reference subjects
Route::group(['namespace' => 'Api', 'prefix' => 'api'], function () {
    Route::group(['prefix' => 'xref'], function () {
        Route::get('branch', 'XrefsController@branch');
        Route::get('status', 'XrefsController@status');
        Route::get('source', 'XrefsController@source');
        Route::get('state', 'XrefsController@state');
        Route::get('gender', 'XrefsController@gender');
        Route::get('category', 'XrefsController@category');
        Route::get('investigator', 'XrefsController@investigator');
    });
});

Route::prefix('announcement')->group(function () {
    Route::get('getdatatable', 'AnnouncementController@getdatatable')->name('announcement.getdatatable');
});
Route::resource('announcement', 'AnnouncementController');
Route::any('backup/download', 'BackupController@Download')->name('backup.download');
Route::any('backup/delete', 'BackupController@Delete')->name('backup.delete');
Route::resource('backup', 'BackupController');

Route::prefix('public-integriti')->namespace('Integriti')->group(function () {
    Route::get('getdatatable', 'PublicIntegritiController@getdatatable');
    Route::get('attachment/{id}', 'PublicIntegritiController@attachment')->name('public-integriti.attachment');
    Route::get('preview/{id}', 'PublicIntegritiController@preview')->name('public-integriti.preview');
    Route::any('submit/{id}', 'PublicIntegritiController@submit')->name('public-integriti.submit');
    Route::get('success/{id}', 'PublicIntegritiController@success')->name('public-integriti.success');
    Route::get('printsuccess/{id}', 'PublicIntegritiController@printsuccess')->name('public-integriti.printsuccess');
});
Route::resource('public-integriti', 'Integriti\PublicIntegritiController');

Route::prefix('integritipublicdoc')->namespace('Integriti')->group(function () {
    Route::get('getdatatable/{id}', 'PublicIntegritiDocController@getdatatable')->name('integritipublicdoc.getdatatable');
    Route::get('create/{id}', 'PublicIntegritiDocController@create')->name('integritipublicdoc.create');
    Route::post('/{id}', 'PublicIntegritiDocController@store')->name('integritipublicdoc.store');
    Route::get('edit/{id}', 'PublicIntegritiDocController@edit')->name('integritipublicdoc.edit');
    Route::put('update/{id}', 'PublicIntegritiDocController@update')->name('integritipublicdoc.update');
    Route::any('delete/{id}', 'PublicIntegritiDocController@destroy')->name('integritipublicdoc.destroy');
});

Route::prefix('integritibase')->namespace('Integriti')->group(function () {
    Route::get('showsummary/{id}', 'IntegritiBaseController@showsummary')->name('integritibase.showsummary');
    Route::get('printsummary/{id}', 'IntegritiBaseController@printsummary')->name('integritibase.printsummary');
    Route::get('getbrnlist/{state_cd}', 'IntegritiBaseController@getbrnlist')->name('integritibase.getbrnlist');
});

Route::prefix('integritiadmin')->namespace('Integriti')->group(function () {
    Route::get('getdatatablecase', 'IntegritiAdminController@getdatatablecase')->name('integritiadmin.getdatatablecase');
    Route::get('attachment/{id}', 'IntegritiAdminController@attachment')->name('integritiadmin.attachment');
    Route::get('preview/{id}', 'IntegritiAdminController@preview')->name('integritiadmin.preview');
    Route::any('submit/{id}', 'IntegritiAdminController@submit')->name('integritiadmin.submit');
    Route::get('check/{id}', 'IntegritiAdminController@check')->name('integritiadmin.check');
    Route::get('getpublicusercomplaintlist/{docno}', 'IntegritiAdminController@getpublicusercomplaintlist');
});
Route::resource('integritiadmin', 'Integriti\IntegritiAdminController');

Route::prefix('integritiadmindoc')->namespace('Integriti')->group(function () {
    Route::get('create/{id}', 'IntegritiAdminDocController@create')->name('integritiadmindoc.create');
    Route::post('{id}', 'IntegritiAdminDocController@store')->name('integritiadmindoc.store');
    Route::get('getdatatable/{id}', 'IntegritiAdminDocController@getdatatable');
    // Route::post('ajaxvalidatestoreattachment', 'IntegritiAdminDocController@ajaxvalidatestoreattachment');
    Route::get('edit/{id}', 'IntegritiAdminDocController@edit')->name('integritiadmindoc.edit');
    Route::put('update/{id}', 'IntegritiAdminDocController@update')->name('integritiadmindoc.update');
    // Route::put('ajaxvalidateupdateattachment', 'IntegritiAdminDocController@ajaxvalidateupdatettachment');
    // Route::delete('{CASEID}/{DOCATTACHID}', 'IntegritiAdminDocController@destroy');
    // Route::get('delete/{id}', 'IntegritiAdminDocController@destroy')->name('integritiadmindoc.destroy');
    Route::any('delete/{id}', 'IntegritiAdminDocController@destroy')->name('integritiadmindoc.destroy');
});
//Route::resource('integritiadmindoc', 'Integriti\IntegritiAdminDocController');

Route::get('integriticarian', 'Integriti\IntegritiCarianController@index');
Route::get('integriticarian/getdatatable', 'Integriti\IntegritiCarianController@getdatatable');

Route::resource('backup', 'BackupController');

Route::group(['prefix' => 'integrititugas', 'namespace' => 'Integriti'], function () {
    Route::get('getdatatable', 'IntegritiTugasController@getdatatable');
    Route::get('getdatatableuser', 'IntegritiTugasController@getdatatableuser');
    Route::get('getmeetingdetail/{id}', 'IntegritiTugasController@getmeetingdetail');
});
Route::resource('integrititugas', 'Integriti\IntegritiTugasController');

Route::prefix('integrititugassemula')->namespace('Integriti')->group(function () {
    Route::get('getdatatable', 'IntegritiTugasSemulaController@getdatatable');
    Route::get('getdatatableuser', 'IntegritiTugasSemulaController@getdatatableuser');
});
Route::resource('integrititugassemula', 'Integriti\IntegritiTugasSemulaController');

Route::prefix('integritiaccess')->namespace('Integriti')->group(function () {
    Route::get('getdatatable', 'IntegritiAccessController@getdatatable');
});

Route::resource('integritiaccess', 'Integriti\IntegritiAccessController'
    , [
        'parameters' => ['integritiaccess' => 'id']
//        , 'only' => ['update', 'destroy']
    ]
);

Route::prefix('integritikemaskini')->namespace('Integriti')->group(function () {
    Route::get('getdatatable', 'IntegritiKemaskiniController@getdatatable');
    Route::get('attachment/{id}', 'IntegritiKemaskiniController@attachment')->name('integritikemaskini.attachment');
    Route::get('preview/{id}', 'IntegritiKemaskiniController@preview')->name('integritikemaskini.preview');
    Route::any('submit/{id}', 'IntegritiKemaskiniController@submit')->name('integritikemaskini.submit');
});
Route::resource('integritikemaskini', 'Integriti\IntegritiKemaskiniController', [
    'parameters' => ['integritikemaskini' => 'id']
    // , 'only' => ['update', 'destroy']
]);

Route::namespace('Integriti')->group(function () {
    Route::prefix('integritikemaskinidoc')->group(function () {
        Route::get('getdatatable/{id}', 'IntegritiKemaskiniDocController@getdatatable');
        Route::get('create/{id}', 'IntegritiKemaskiniDocController@create')->name('integritikemaskinidoc.create');
        Route::post('{id}', 'IntegritiKemaskiniDocController@store')->name('integritikemaskinidoc.store');
        Route::get('edit/{id}', 'IntegritiKemaskiniDocController@edit')->name('integritikemaskinidoc.edit');
        Route::put('update/{id}', 'IntegritiKemaskiniDocController@update')->name('integritikemaskinidoc.update');
        Route::any('delete/{id}', 'IntegritiKemaskiniDocController@destroy')->name('integritikemaskinidoc.destroy');
    });
    Route::prefix('integritikemaskinimaklumat')->group(function () {
        Route::get('getdatatable', 'IntegritiKemaskiniMaklumatController@getdatatable');
    });
    Route::resource('integritikemaskinimaklumat', 'IntegritiKemaskiniMaklumatController', [
        'parameters' => ['integritikemaskinimaklumat' => 'id']
        // , 'only' => ['update', 'destroy']
    ]);
    Route::prefix('integritimeeting')->group(function () {
        Route::get('getdatatable', 'IntegritiMeetingController@getdatatable');
        Route::get('getdatatabletugas', 'IntegritiMeetingController@getdatatabletugas');
    });
    Route::resource('integritimeeting', 'IntegritiMeetingController', [
        'parameters' => ['integritimeeting' => 'id']
        // , 'only' => ['update', 'destroy']
    ]);
});

Route::group(['namespace' => 'Integriti'], function () {
    Route::group(['prefix' => 'integritipublicuser'], function () {
        Route::get('attachment/{id}', 'IntegritiPublicUserController@attachment')->name('integritipublicuser.attachment');
        Route::get('preview/{id}', 'IntegritiPublicUserController@preview')->name('integritipublicuser.preview');
        Route::post('submit/{id}', 'IntegritiPublicUserController@submit')->name('integritipublicuser.submit');
        Route::get('success/{id}', 'IntegritiPublicUserController@success')->name('integritipublicuser.success');
        Route::get('printsuccess/{id}', 'IntegritiPublicUserController@printsuccess')->name('integritipublicuser.printsuccess');
        Route::get('getdistlist/}', 'IntegritiPublicUserController@getdistlist');
        Route::get('getdistlist/{state_cd}', 'IntegritiPublicUserController@getdistlist');
        Route::get('getpublicusercomplaintlist/{docno}', 'IntegritiPublicUserController@getpublicusercomplaintlist');
        Route::get('getbrnlist', 'IntegritiPublicUserController@getbrnlist')->name('integritibase.getbrnlist');
        Route::get('getbrnlist/{state_cd}', 'IntegritiPublicUserController@getbrnlist')->name('integritibase.getbrnlist');
    });
    Route::resource('integritipublicuser', 'IntegritiPublicUserController', [
        // 'only' => ['create', 'store', 'show', 'edit', 'update'],
        'parameters' => ['integritipublicuser' => 'id']
    ]);
    Route::group(['prefix' => 'integritipublicuserdoc'], function () {
        Route::get('getdatatable/{id}', 'IntegritiPublicUserDocController@getdatatable')->name('integritipublicuserdoc.getdatatable');
        Route::get('create/{id}', 'IntegritiPublicUserDocController@create')->name('integritipublicuserdoc.create');
        Route::post('ajaxvalidatestore', 'IntegritiPublicUserDocController@ajaxvalidatestore');
        Route::post('/{id}', 'IntegritiPublicUserDocController@store')->name('integritipublicuserdoc.store');
        Route::get('edit/{id}', 'IntegritiPublicUserDocController@edit')->name('integritipublicuserdoc.edit');
        Route::put('ajaxvalidateupdate', 'IntegritiPublicUserDocController@ajaxvalidateupdate');
        Route::put('update/{id}', 'IntegritiPublicUserDocController@update')->name('integritipublicuserdoc.update');
        Route::any('delete/{id}', 'IntegritiPublicUserDocController@destroy')->name('integritipublicuserdoc.destroy');
    });
});

//Route::get('feedback/whatsapp/{whatsapp}/reply', 'Feedback\WhatsappController@reply')->name('whatsapp.reply');
//
//Route::prefix('feedback')->group(function () {
//    Route::prefix('whatsapp')->group(function () {
//        Route::get('getdatatable', 'Feedback\WhatsappController@getdatatable')->name('whatsapp.getdatatable');
//        Route::get('dtnew', 'Feedback\WhatsappController@dtNew')->name('whatsapp.dtnew');
//        Route::get('dtmytask', 'Feedback\WhatsappController@dtMyTask')->name('whatsapp.dtmytask');
//        Route::post('{whatsapp}/createaduan', 'Feedback\WhatsappController@createAduan')->name('whatsapp.createaduan');
//    });
//    Route::resource('whatsapp', 'Feedback\WhatsappController', [
//        'only' => ['index', 'show', 'edit']
//    ]);
//});

// feedback module
Route::group(['namespace' => 'Feedback', 'prefix' => 'feedback'], function () {
    Route::group(['namespace' => 'Template', 'prefix' => ''], function () {
        Route::get('template/{template_id}/all', 'TemplateController@template')->name('feedback.template.all');
        Route::get('template/{template_id}/bystatus', 'TemplateController@templateByStatus')->name('feedback.template.bystatus');
        Route::get('template/dt', 'TemplateController@dt')->name('feedback.template.dt');
        Route::resource('template', 'TemplateController', ['as' => 'feedback']);
    });
    Route::get('', 'Controller@index')->name('feedback.index');

    Route::group(['namespace' => 'Whatsapp', 'prefix' => '', 'name' => 'whatsapp'], function () {
        Route::get('whatsapp/new/dt', 'NewController@dt')->name('whatsapp.new.dt');
        Route::resource('whatsapp/new', 'NewController', ['only' => ['index'], 'as' => 'whatsapp']);

        Route::get('whatsapp/all/dt', 'AllController@dt')->name('whatsapp.all.dt');
        Route::resource('whatsapp/all', 'AllController', ['only' => ['index'], 'as' => 'whatsapp']);

        Route::get('whatsapp/mytask/dt', 'MyTaskController@dt')->name('whatsapp.mytask.dt');
        Route::resource('whatsapp/mytask', 'MyTaskController', ['only' => ['index'], 'as' => 'whatsapp']);
        Route::post('whatsapp/{whatsapp}/mytask/add', 'MyTaskController@addToMyTask')->name('whatsapp.mytask.add');
        Route::post('whatsapp/{whatsapp}/mytask/release', 'MyTaskController@releaseFromTask')->name('whatsapp.mytask.release');

        Route::post('whatsapp/{whatsapp}/createaduan', 'CreateAduanController')->name('whatsapp.createaduan');

        Route::get('whatsapp/{whatsapp}/inactive', 'WhatsappController@inactive')->name('whatsapp.inactive');
        Route::get('whatsapp/{whatsapp}/reply', 'WhatsappController@reply')->name('whatsapp.reply');
        Route::get('whatsapp/dt', 'WhatsappController@dt')->name('whatsapp.dt');
        Route::post('whatsapp', 'WhatsappController@storeWeb')->name('whatsapp.storeWeb');
        Route::resource('whatsapp', 'WhatsappController', ['only' => ['index', 'show', 'edit', 'create'], 'as' => '']);
    });

    Route::group(['namespace' => 'Telegram', 'prefix' => '', 'name' => 'telegram'], function() {
//        Route::get('whatsapp/new/dt', 'NewController@dt')->name('whatsapp.new.dt');
//        Route::resource('whatsapp/new', 'NewController', ['only' => ['index'], 'as' => 'whatsapp']);
//
        Route::get('telegram/all/dt', 'AllController@dt')->name('telegram.all.dt');
        Route::resource('telegram/all', 'AllController', ['only' => ['index'], 'as' => 'telegram']);
//
        Route::get('telegram/mytask/dt', 'MyTaskController@dt')->name('telegram.mytask.dt');
        Route::resource('telegram/mytask', 'MyTaskController', ['only' => ['index'], 'as' => 'telegram']);
        Route::post('telegram/{telegram}/mytask/add', 'MyTaskController@addToMyTask')->name('telegram.mytask.add');
        Route::post('telegram/{telegram}/mytask/release', 'MyTaskController@releaseFromTask')->name('telegram.mytask.release');
//
        Route::post('telegram/{telegram}/createaduan', 'CreateAduanController')->name('telegram.createaduan');
//
        Route::get('telegram/{telegram}/inactive', 'TelegramInactiveController')->name('telegram.inactive');
        Route::get('telegram/{telegram}/reply', 'TelegramReplyController')->name('whatsapp.reply');
        Route::get('telegram/dt', 'TelegramController@dt')->name('telegram.dt');
//        Route::post('whatsapp', 'WhatsappController@storeWeb')->name('whatsapp.storeWeb');
        Route::resource('telegram', 'TelegramController', ['only' => ['index', 'show', 'edit', 'create'], 'as' => '']);
    });

//    Route::group(['namespace' => 'Instagram', 'prefix' => ''], function () {
//        Route::get('instagram/mytask', 'InstagramController@mytask')->name('twitter.mytask');
//        Route::get('instagram/dtnew', 'InstagramController@dtNew')->name('twitter.dtnew');
//        Route::get('instagram/dtmytask', 'InstagramController@dtMyTask')->name('twitter.dtmytask');
//        Route::get('instagram/login', 'AuthController@login')->name('twitter.login');
//        Route::get('instagram/callback', 'AuthController@callback')->name('twitter.callback');
//        Route::get('instagram/mention', 'ApiController@retrieveMentionTimeline')->name('twitter.mention');
//        Route::resource('instagram', 'InstagramController', ['only' => ['index', 'show', 'edit']]);
//    });

//    Route::group(['namespace' => 'Telegram', 'prefix' => ''], function () {
//        Route::get('telegram/mytask', 'TelegramController@mytask')->name('twitter.mytask');
//        Route::get('telegram/dtnew', 'TelegramController@dtNew')->name('twitter.dtnew');
//        Route::get('telegram/dtmytask', 'TelegramController@dtMyTask')->name('twitter.dtmytask');
//        Route::get('telegram/login', 'AuthController@login')->name('twitter.login');
//        Route::get('telegram/callback', 'AuthController@callback')->name('twitter.callback');
//        Route::get('telegram/mention', 'ApiController@retrieveMentionTimeline')->name('twitter.mention');
//        Route::resource('telegram', 'TelegramController', ['only' => ['index', 'show', 'edit']]);
//    });

    Route::group(['namespace' => 'Email', 'prefix' => '', 'name' => 'email'], function () {
        Route::get('email/all/dt', 'AllController@dt')->name('email.all.dt');
        Route::resource('email/all', 'AllController', ['only' => ['index'], 'as' => 'email']);

        Route::get('email/mytask/dt', 'MyTaskController@dt')->name('email.mytask.dt');
        Route::resource('email/mytask', 'MyTaskController', ['only' => ['index'], 'as' => 'email']);
        Route::post('email/{email}/mytask/add', 'MyTaskController@addToMyTask')->name('email.mytask.add');
        Route::post('email/{email}/mytask/release', 'MyTaskController@releaseFromTask')->name('email.mytask.release');

        Route::post('email/{email}/createaduan', 'CreateAduanController')->name('email.createaduan');

        Route::get('email/{email}/inactive', 'EmailController@inactive')->name('email.inactive');
        Route::get('email/{email}/reply', 'EmailController@reply')->name('email.reply');
        Route::get('email/dt', 'EmailController@dt')->name('email.dt');
        Route::resource('email', 'EmailController', ['only' => ['index', 'show', 'edit'], 'as' => '']);
    });
});
