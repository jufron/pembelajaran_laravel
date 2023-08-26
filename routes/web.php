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


Route::get('/about', fn () => 'hello about');
Route::get('/contact', fn () => 'hello contact');

Route::get('/seting', fn() =>  redirect()->route('seting.dev'));
Route::get('/seting/dev', fn() => 'hello seting dev')->name('seting.dev');


Route::fallback( fn() => '404 by JR' );

Route::get('/profile', fn() => view('hello', ['nama' => 'james']));

// Route::get('hello/{nama}/{id}', function (string $nama, string $id) {
//     return "hallo $nama id saya $id";
// });

Route::get('siswa/{id}', function (string $id) {
    return "hallo $id";
});

// * regular expresion constraints
Route::get('mapel/{id}', fn (string $id) => "mata pelajaran id $id")->where('id', '[0-9]+');

// * optional parameter
Route::get('/user/{nama?}', fn ($nama = 'not found') => "hallo user $nama");

Route::controller(HelloController::class)->group( function () {
    Route::get('hello/{nama}', 'index');
    Route::get('profile', 'show');
    ROute::get('request', 'request');
});
