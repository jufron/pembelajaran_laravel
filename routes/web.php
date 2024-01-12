<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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

Route::post('form/login', [FormController::class, 'login']);
Route::get('login', [FormController::class, 'form_login']);
Route::post('login', [FormController::class, 'login_post'])->name('login');
