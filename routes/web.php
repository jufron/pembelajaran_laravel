<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TodolistController;
use App\Http\Controllers\UserController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
});

Route::controller(UserController::class)->middleware('only-guest')
->group( function () {
    Route::get('login', 'login')->name('todolist.login');
    Route::post('login', 'todoLogin')->name('todolist.login.store');
    Route::post('logout', 'logout')->name('todolist.logout');
});

// user are login
Route::controller(TodolistController::class)->middleware('only-member')
->group( function () {
    Route::get('todolist', 'todolist')->name('todolist');
    Route::get('todolist/create', 'todolistCreate')->name('todolist.create');
    Route::post('todolist', 'addTodo')->name('todolist.store');
    Route::get('todolist/edit/{id}', 'edit')->name('todolist.edit');
    Route::patch('todolist/{id}', 'update')->name('todolist.update');
    Route::delete('todolist/{id}', 'removeTodo')->name('todolist.remove');
});
