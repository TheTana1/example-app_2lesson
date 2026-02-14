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

Route::resource('users', UserController::class)->except(['edit','delete']);
//Route::get('users/{slug}/edit', [UserController::class, 'edit'])->name('users.edit');

Route::middleware(\App\Http\Middleware\AuthAlways::class)->group(function () {})->
    resource('users', UserController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
