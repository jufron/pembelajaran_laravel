<?php

use App\Http\Controllers\HelloController;
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
