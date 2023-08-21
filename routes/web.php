<?php

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