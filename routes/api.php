<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;


//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::prefix('users')->group(function () {
   Route::get('/', [UserController::class, 'index']);
    Route::get('{user}', [UserController::class, 'show']);
    Route::post('', [UserController::class, 'store']);
    Route::patch('{user}', [UserController::class, 'update']);
    Route::delete('{user}', [UserController::class, 'destroy']);
});
