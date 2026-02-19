<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;


//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::prefix('users')->group(function () {
   Route::get('/', [UserController::class, 'index'])->name('users.api.index');
    Route::get('{user}', [UserController::class, 'show'])->name('users.api.show');
    Route::post('/', [UserController::class, 'store']);
    Route::patch('{user}', [UserController::class, 'update'])->name('users.api.update');
    Route::delete('{user}', [UserController::class, 'destroy'])->name('users.api.destroy');
});

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('books.api.index');
    Route::get('{book}', [BookController::class, 'show'])->name('books.api.show');
    Route::post('', [BookController::class, 'store'])->name('books.api.store');
    Route::patch('{book}', [BookController::class, 'update'])->name('books.api.update');
    Route::delete('{book}', [BookController::class, 'destroy'])->name('books.api.destroy');
});
