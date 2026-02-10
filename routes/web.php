<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\RequestMiddleware;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('users.index'); // Перенаправляем на список пользователей
//});

Route::get('/', function () {
    return redirect()->route('users.index');
});
Route::resource('users', UserController::class);

Route::middleware(RequestMiddleware::class)->
    resource('users', UserController::class);


