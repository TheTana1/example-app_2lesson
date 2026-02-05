<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('users.index'); // Перенаправляем на список пользователей
//});
Route::get('/', function () {
    return redirect()->route('users.index');
});
Route::resource('users', UserController::class);

