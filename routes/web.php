<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\BookController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();

Route::middleware(CheckAdmin::class)->group(function () {


    Route::post('track/listen-progress', [MusicController::class, 'trackListenProgress'])->name('track.listen_progress');
    Route::resource('users', UserController::class);
    Route::resource('books', BookController::class);
    Route::post('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');

    // MUSIC CONTROLLER
   Route::resource('music', MusicController::class);
        Route::post('save/favorite/{music}', [MusicController::class, 'saveFavorite'])->name('save.favorite');
        Route::post('track/listen-progress', [MusicController::class, 'trackListenProgress'])->name('track.listen_progress');



    //FAVORITES
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    //DASHBOARD CONTROLLER
    Route::get('',[DashboardController::class,'index'])->name('dashboard');

}
);

Route::middleware('auth')->group(function () {

    // MUSIC CONTROLLER
    Route::name('music.')->group(function () {
        Route::get('', [MusicController::class, 'index'])->name('index');
        Route::get('{music}', [MusicController::class, 'show'])->name('show');
        Route::post('save/favorite/{music}', [MusicController::class, 'saveFavorite'])->name('save.favorite');
        Route::post('track/listen-progress', [MusicController::class, 'trackListenProgress'])->name('track.listen_progress');
    });

    Route::resource('users', UserController::class)->only(['index', 'show']);
    Route::resource('books', BookController::class)->only(['index', 'show']);

    //COMMENTS CONTROLLER
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::post('/{type}/{id}', [CommentController::class, 'store'])->name('store');
        // Другие маршруты для комментариев
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');
    });

    //FAVORITE CONTROLLER
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    //DASHBOARD CONTROLLER
    Route::get('',[DashboardController::class,'index'])->name('dashboard');

}
);


//Route::get('/home', [HomeController::class, 'index'])
//    ->name('home');
