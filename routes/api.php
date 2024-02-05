<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserCntroller;
use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('testing', function () {
    return json_encode('testing');
});

Route::controller(UserController::class)->group(function () {
    Route::post('users', 'register')->name('register');
    Route::post('users/login', 'login')->name('login');
});

Route::middleware('api_auth')->group(function () {
    Route::controller(UserController::class)->group( function () {
        Route::get('users/current', 'get')->name('get_user');
        Route::patch('users/current', 'update')->name('update.user');
        Route::delete('users/logout', 'logout')->name('logout.user');
    });

    Route::controller(ContactController::class)->group(function () {
        Route::post('contacts', 'create')->name('contact.create');
        Route::get('contacts', 'search')->name('contact.create');
        Route::get('contacts/{id}', 'getDataWhere')->name('contact.get');
        Route::put('contacts/{id}', 'update')->name('contact.update');
        Route::delete('contacts/{id}', 'delete')->name('contact.delete');
    });

    Route::controller(AddressController::class)->group(function () {
        Route::post('contacts/{id}/addreses', 'create')->name('address.create');
        Route::get('contacts/{id}/addreses', 'get')->name('address.get');
        Route::get('contacts/{id}/addreses/{idAddress}', 'find')->whereNumber('idAddress')->name('address.find');
        Route::put('contacts/{id}/addreses/{idAddress}', 'update')->whereNumber('idAddress')->name('address.update');
        Route::delete('contacts/{id}/addreses/{idAddress}', 'destroy')->whereNumber('idAddress')->name('address.delete');
    });
});
