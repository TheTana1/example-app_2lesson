<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;


//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::prefix('users')->name('api.users.')->group(function () {
   Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('{user}', [UserController::class, 'show'])->name('show');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::patch('{user}', [UserController::class, 'update'])->name('update');
    Route::delete('{user}', [UserController::class, 'destroy'])->name('destroy');
});

Route::prefix('books')->name('api.books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('{book}', [BookController::class, 'show'])->name('show');
    Route::post('', [BookController::class, 'store'])->name('store');
    Route::patch('{book}', [BookController::class, 'update'])->name('.update');
    Route::delete('{book}', [BookController::class, 'destroy'])->name('destroy');
});
