<?php

use App\Http\Controllers\DashboardController;
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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/template', fn () => view('template'));

Route::middleware('just_guest')->controller(UserController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'actionLogin')->name('login');
});

ROute::middleware('just_auth')->group(function () {
    ROute::post('logout', [UserController::class, 'logout'])->name('logout');
    ROute::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
    });

    Route::controller(TodolistController::class)->group( function () {
        Route::get('todolist', 'todolist')->name('todolist');
        Route::post('todolist', 'addTodolist')->name('todolist.post');
        Route::delete('todolist/{id}', 'removeTodolist')->name('todolist.remove');
    });
});
