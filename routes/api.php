<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes.
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!.
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
    
Route::get('/provinsi','User\PanitiaController@getProvinsi');
Route::get('/kabupaten/{id_provinsi}','User\PanitiaController@getKabupaten');
Route::get('/kabupaten-data/{id_kabupaten}','User\PanitiaController@getDataKabupaten');
Route::get('event/search' , 'User\PesertaController@searchEvent');

Route::group(['middleware' => ['auth:api']], function() {

    Route::group(['middleware' => ['panitia','active_user'],'prefix' => 'panitia'], function($router) {
        Route::get('/event',  'User\PanitiaController@indexEventinDate');
        Route::get('/eventPast',  'User\PanitiaController@getEventinOutDate');
        Route::get('/event-sertifikat',  'User\PanitiaController@indexSertifikat');
        Route::get('/event-received-sertifikat/{id_event}','User\PanitiaController@indexWaitingSertifikat');
        Route::get('/event-upload-sertifikat','User\PanitiaController@indexUploadSertifikat');
        Route::get('/event-sertifikat/{id}',  'User\PanitiaController@showSertifikat');
        Route::get('/detail-sertifikat/{id}','User\PanitiaController@showFileSertifikat');
        Route::put('/edit-sertifikat-event/{id_sertifikat}','User\PanitiaController@updateSertifikat');
        Route::get('/event/{id}', 'User\PanitiaController@showEvent');
        Route::get('/pesertabyEvent/{id_event}','User\PanitiaController@showPesertainEvent');
        Route::get('/event/{id}/peserta','User\PanitiaController@showPesertabyEvent');
        Route::get('/event/{id}/peserta-absent','User\PanitiaController@showPesertabyEventAbsent');
        Route::get('/showpeserta/{id}', 'User\PanitiaController@detailPeserta');
        Route::get('/kategori', 'User\PanitiaController@indexKategori');
        Route::get('/regist-peserta/{id_event}', 'User\PanitiaController@indexRegisterPeserta');
        $router->pattern('pagePath', '(event|biodata-penandatangan|sertifikat)');
        Route::post('/create/{pagePath}', 'User\PanitiaController@getPage');
        Route::post('/create-event','User\PanitiaController@createEvent');
        Route::get('/profile','User\PanitiaController@showProfile');
        Route::get('/profile-edit','User\PanitiaController@showEditProfile');
        Route::put('/editprofile/{id_panitia}', 'User\PanitiaController@updateProfile');
        Route::get('/list-penandatangan', 'User\PanitiaController@showPenandatangan');
        Route::put('/editevent/{id}', 'User\PanitiaController@updateEvent');
        Route::get('/countRegister','User\PanitiaController@countRegister');
        Route::delete('/deleteevent/{id}', 'User\PanitiaController@delete');
      
        Route::put('/profile/edit', 'User\PanitiaController@updateProfile');
        Route::get('/download/template','User\PanitiaController@getDownload');
        Route::put('/approvepeserta/{id_peserta}', 'User\PanitiaController@approvePeserta');
        Route::put('/rejectpeserta/{id_peserta}', 'User\PanitiaController@rejectPeserta');
        Route::put ('/ubahAbsensi/{id_pesertaevent}', 'User\PanitiaController@statusAbsensi');

        Route::post('/create-sertifikat','User\PanitiaController@createSertifikat');
        Route::get('/count-waiting',  'User\PanitiaController@showTotalWaitingSertifikat');
        Route::get('/count-regis-byToday','User\PanitiaController@getRegisterPesertabyToday');

        Route::post('/change-password', 'User\PanitiaController@changePassword');
    });

    Route::group(['middleware' => ['peserta','active_user'],'prefix' => 'peserta'], function() {
        Route::get('/Allevent',  'User\PesertaController@AllEvent');
        Route::get('/Allevent/Kategori/{id_kategori}',  'User\PesertaController@showAllKategori');
        Route::get('/event',  'User\PesertaController@showAllEvent');
        Route::get('/event/{id}',  'User\PesertaController@showDetailEvent');
        Route::put('/profile/edit/{id_peserta}',  'User\PesertaController@updateProfile');
        Route::get('/eventbyWeek', 'User\PesertaController@showEventbyWeek');
        Route::get('/profile','User\PesertaController@showProfile');
        Route::get('/edit-profile', 'User\PesertaController@showEditProfile');
        Route::post('/pesertaevent', 'User\PesertaController@registerEvent');
        Route::get('/register-event','User\PesertaController@showEventRegister');
        Route::get('/registered-event','User\PesertaController@showEventRegistered');
        Route::get('/sertifikat/{ids}/download','User\PesertaController@indexSertifikatInPeserta');
        Route::get('/profile/event','User\PesertaController@showEventDone');
        Route::get('/kategori', 'User\PesertaController@indexKategori');
        Route::get('/event/kategori/{id_kategori}', 'User\PesertaController@showEventbyKategori');
        Route::delete('/profile/event/{id_event}/delete', 'User\PesertaController@cancelDaftar');

        Route::post('/change-password', 'User\PesertaController@changePassword');
    });

    Route::group(['middleware' => ['penandatangan','active_user'],'prefix' => 'penandatangan'], function() {
        Route::get('/count-waiting','User\PenandatanganController@countWaitingDashboard');
        Route::get('/profile',  'User\PenandatanganController@showProfile');
        Route::get('/profile-edit', 'User\PenandatanganController@showEditProfile');
        Route::put('/profile/edit/{id_penandatangan}', 'User\PenandatanganController@updateProfile');
        Route::get('/sertifikat-detail/{id}',  'User\PenandatanganController@showSertifikat');
        Route::get('/count-sertifikat-waiting', 'User\PenandatanganController@showTotalWaitingSertifikat');
        Route::get('/sertifikat/waiting/{id_event}',  'User\PenandatanganController@showWaitingSertifikat');
        Route::get('/detail-sertifikat/{id}','User\PenandatanganController@showFileSertifikat');
        Route::get('/sertifikat/signed',  'User\PenandatanganController@showSignedSertifikat');
        Route::get('/sertifikat/rejected',  'User\PenandatanganController@showRejectedSertifikat');
        Route::put('/sertifikat/assign/{id_penandatangan_sertifikat}', 'User\PenandatanganController@assignSertifikat');

        Route::post('/change-password', 'User\PenandatanganController@changePassword');
    });

    Route::group(['middleware' => 'admin','prefix' => 'admin'], function() {
        //biodata penandatangan
        Route::get('/showbiodatapenandatangan', 'User\AdminController@showBiodataPenandatangan');
        Route::post('/addpenandatangan',  'User\AdminController@accPenandatangan');
        Route::post('/reject-penandatangan','User\AdminController@rejectPenandatangan');
        //peserta
        Route::get('/showpeserta',  'User\AdminController@showPeserta');
        Route::get('/showpeserta/{id_users}', 'User\AdminController@detailPeserta');
        Route::get('/showpeserta-event/{id_peserta}','User\AdminController@showEventbyPeserta');
        //menampilkan data user yang di ban
        Route::get('/trash/peserta',  'User\AdminController@trashPeserta');
        Route::get('/trash/panitia',  'User\AdminController@trashPanitia');
        Route::get('/trash/penandatangan',  'User\AdminController@trashPenandatangan');
        // unban user
        Route::get('/unban/peserta/{id_peserta}',  'User\AdminController@unbanPeserta');
        Route::get('/unban/panitia/{id_panitia}',  'User\AdminController@unbanPanitia');
        Route::get('/unban/penandatangan/{id_penandatangan}',  'User\AdminController@unbanPenandatangan');
        //EVENT
        Route::get('/approve/event',  'User\AdminController@listApproveEvent');
        Route::get('/detail-event/admin/{id_event}', 'User\AdminController@detailEvent');
        Route::get('/showevent',  'User\AdminController@listEvent');
        //panitia
        Route::get('/showpanitia',  'User\AdminController@showPanitia');
        Route::get('/detail-panitia/admin/{id_users}', 'User\AdminController@detailPanitia');
        Route::get('/event-panitia/{id_panitia}', 'User\AdminController@showEventbyPanitia');
        //show sertifiikat
        Route::get('/sertifikat-waiting',  'User\AdminController@showWaitingSertifikat');
        //penandatangan
        Route::get('/showpenandatangan',  'User\AdminController@showPenandatangan');
        Route::get('/showeditpenandatangan/{id_users}','User\AdminController@detailPenandatangan');
        Route::get('/show-sertifikat-penandatanan/{id_penandatangan}','User\AdminController@showDetailSertifikatPenandatangan');
        Route::put('/penandatangan/edit/{id_penandatangan}',  'User\AdminController@editPenandatangan');
        //banned
        Route::delete('/ban/peserta/{id_peserta}',  'User\AdminController@banPeserta');
        Route::delete('/ban/panitia/{id_panitia}',  'User\AdminController@banPanitia');
        Route::delete('/ban/penandatangan/{id_penandatangan}',  'User\AdminController@banPenandatangan');
        //kategori
        Route::get('/kategori', 'User\AdminController@indexKategori');
        Route::post('/addkategori', 'User\AdminController@createKategori');
        Route::put('/editkategori/{id_kategori}', 'User\AdminController@updateKategori');
        Route::delete('/deletekategori/{id_kategori}', 'User\AdminController@deleteKategori');
        Route::put('/approvalevent/{id_event}/acc', 'User\AdminController@accEvent');
        Route::put('/approvalevent/{id_event}/reject', 'User\AdminController@rejectEvent');
        Route::get('/waiting-event-sertifikat/{id_event}','User\AdminController@showWaitinSErtifikatEvent');
        Route::put('/send-sertifikat/{id_penandatangan_sertifikat}','User\AdminController@sendSertifikat');
        Route::delete('/reject-sertifikat/{id_penandatangan_sertifikat}',  'User\AdminController@rejectSertifikat');
        //count dashboard
        Route::get('/count-event', 'User\AdminController@countEventbyMonth');
        Route::get('/count-user', 'User\AdminController@countRolebyUser');
        Route::get('/count-all-sertifikat', 'User\AdminController@allSertifikat');
        Route::get('/count-all-event', 'User\AdminController@allEvent');
        Route::get('/count-all-user', 'User\AdminController@allUser');
    });

});
Route::post('/test', 'User\AdminController@test');

Route::group([ 
        'prefix' => 'password'
    ], function () {    
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset/penandatangan/{token}', 'PasswordResetController@setPenandatangan');
        Route::post('reset/{token}', 'PasswordResetController@reset');
    });

    Route::get('coba/{id}','User\PanitiaController@showPesertabyEvent');
    Route::post('/addpenandatangan',  'PenandatanganController@addPenandatangan');

    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('login', 'Auth\AuthController@login');
        Route::post('register/peserta', 'Auth\AuthController@registerPeserta');
        Route::post('register/panitia', 'Auth\AuthController@registerPanitia');
        Route::group([
          'middleware' => 'auth:api'
        ], function() {
            Route::post('logout', 'Auth\AuthController@logout');
            Route::get('user', 'Auth\AuthController@user');
        });
    });