<?php

use App\Http\Controllers\CookieController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students', function () {
    return 'students url get';
});

Route::post('/students', function () {
    return 'students url post';
});


Route::view('siswa', 'siswa', ['nama' => 'jufron']);
Route::controller(HelloController::class)->group(function () {
    Route::get('hello', 'hello')->name('hello');
    Route::get('hello/service/{nama}', 'helloService')->name('hello_service');
    Route::get('bar', 'bar')->name('bar');
    Route::get('user-service/get/{table}', 'get')->name('userService.get');
    Route::get('user-service/log', 'log')->name('userService.log');

    Route::get('hello/request/input', 'sayHello')->name('sayHello.get');
    Route::post('hello/request/input', 'sayHello')->name('sayHello.post');
    Route::post('hello/request/nested', 'requestNested')->name('requestNested');

    Route::post('hello/request/merge', 'requestMarge');
});

Route::controller(FileUploadController::class)->group(function () {
    Route::post('upload/file', 'upload');
    Route::post('upload/file/private', 'uploadDoctPrivate');
    Route::post('upload/file/public', 'uploadDoctPublic');
});

Route::controller(ResponseController::class)->group(function() {
    Route::get('response/header', 'responseHeader');
    Route::post('response/header/request', 'responseHeader2');
    Route::get('response/view', 'responseView');
    Route::get('response/file', 'responseFile');
    Route::get('response/download', 'responseDownload');
});

Route::controller(CookieController::class)->group(function () {
    Route::get('cookie/create', 'createCookie');
    Route::get('cookie/get', 'getCookie');
});

Route::get('coba/middlware', function () {
    return 'success';
})->middleware('token');

Route::controller(SessionController::class)->group( function () {
    Route::get('session/create', 'sessionCreate');
    Route::get('session/get', 'sessionGet');
});

Route::get('error/simple', function () {
    throw new Exception('terjadi error');
});

Route::get('error/simple/2', function () {
    try {
        throw new Exception('pesan : terjadi error');
    } catch (\App\Exceptions\ContohException $e) {
        report($e);
        ddd($e);
    }
});
