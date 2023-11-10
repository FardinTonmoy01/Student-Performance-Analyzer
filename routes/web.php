<?php

use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

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

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');

    return 'cache clear';
});

Route::get('/version', function () {
    return 'Laravel Version => '.app()->version();
});

//download file path from storage
Route::get('/download-attachment', [DownloadController::class, 'downloadFile']);


Route::get('/download-excel', function () {
    $filePath = public_path('sample-excel/student.xlsx');

    return Response::download($filePath, 'student.xlsx');
})->name('download.student.excel');



Route::group(['middleware' =>['guest']], function () {
    Route::group(['prefix'=>'/', 'namespace' => 'App\Http\Controllers\Frontend'], function() {  
        // Home
        Route::get('/', 'HomeController@index')->name('frontend.home');

        //password reset
        Route::get('/reset/password', 'PasswordResetController@password_reset')->name('password.reset');
        Route::post('/email-password/reset', 'PasswordResetController@resetPassword')->name('email.reset');
        Route::get('/reset/new-password/{verified_at}', 'PasswordResetController@resetForm');
        Route::post('/update/password', 'PasswordResetController@updatePassword')->name('password.update');

        // verify mail
        Route::get('/verify/{token}', 'PasswordResetController@verify')->name('mail.verify');
    });


    // unused
    Route::group(['prefix'=>'/admin', 'namespace' => 'App\Http\Controllers\Auth'], function() {  
        // Login
        Route::get('/login', 'LoginController@index')->name('auth.login');
        // Register
        Route::get('/register', 'RegisterController@index')->name('auth.register');
        // user-sign-in
        Route::post('/sign-in', 'LoginController@userSignIn')->name('auth.signin');
        // user sign-up
        Route::post('/sign-up', 'LoginController@userSignUp')->name('auth.signup');
    });

});
// end unused


Route::group(['middleware' => ['auth']], function () {

    // Route::get('/fileDownload/{file}', [ParentController::Class, 'downloadFile']);
    Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('auth.signout');

    include('admin.php');
});


