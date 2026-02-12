<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome'); // Перенаправляем на список пользователей
//});
//
Route::get('/', function () {
    return redirect()->route('users.index');
});
Route::resource('users', UserController::class);

Route::middleware('auth')->group(function () {})->
    resource('users', UserController::class);



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
