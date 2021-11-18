<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\QrCodeController;
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

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/documents/my',[DocumentController::class,'userDocs'])->name('user.docs');
    Route::get('/documents/register',[DocumentController::class,'registerDocument'])->name('docs.register');
    Route::post('/documents/register',[DocumentController::class,'storeDocument'])->name('docs.store');
    Route::get('/documents/user',[DocumentController::class,'listUserDocs'])->name('user.docs.list');
    Route::get('/documents/{document}/manage',[DocumentController::class,'manageDocument'])->name('user.manage.document');
    Route::get('/documents/{document}/display',[DocumentController::class,'displayDocument'])->name('document.display');

    // Create QR Code
    // Route::get('/qr/code/sign/retrieve',[QrCodeController::class,'retrieve'])->name('qr.code.retrieve');

});


