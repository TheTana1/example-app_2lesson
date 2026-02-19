<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\BookController;
use App\Http\Middleware\AuthAlways;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome'); // Перенаправляем на список пользователей
//});
//
//Route::get('users/{slug}/edit', [UserController::class, 'edit'])->name('users.edit');
//Route::put('users/{slug}', [UserController::class, 'update'])->name('users.update');

//Route::get('/', function () {
//    return redirect()->route('users.index');
//});
Auth::routes();

Route::middleware([AuthAlways::class])->group(function () {
    // Ресурсы с полным набором методов (кроме delete, если нужно)
    Route::resource('users', UserController::class);
    Route::resource('books', BookController::class);

    // Дополнительные маршруты для users
    Route::post('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
});


Route::get('/home', [HomeController::class, 'index'])
    ->name('home');
