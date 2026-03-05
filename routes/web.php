<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\BookController;
use App\Http\Middleware\CheckAdmin;
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

Route::middleware(CheckAdmin::class)->group(function () {

//    // MUSIC CONTROLLER
//    Route::resource('music', MusicController::class);
//    Route::post('save/favorite/{music}', [MusicController::class, 'saveFavorite'])->name('save.favorite');
//    Route::post('track/listen-progress', [MusicController::class, 'trackListenProgress'])->name('track.listen_progress');
    Route::resource('users', UserController::class);
    Route::resource('books', BookController::class);
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    Route::name('music.')->group(function () {
        Route::get('', [MusicController::class, 'index'])->name('index');
        Route::get( 'create',[MusicController::class, 'create'])->name('create');
        Route::post('store',[MusicController::class, 'store'])->name('store');
        Route::get( '{id}',[MusicController::class, 'show'])->name('show');
        Route::get( '{music}/edit',[MusicController::class, 'edit'])->name('edit');
        Route::delete( '{music}',[MusicController::class, 'destroy'])->name('destroy');
        Route::patch( '{music}',[MusicController::class, 'update'])->name('update');
        Route::put( '{music}',[MusicController::class, 'update'])->name('update');

        Route::post('save/favorite/{music}', [MusicController::class, 'saveFavorite'])->name('save.favorite');
        Route::post('track/listen-progress', [MusicController::class, 'trackListenProgress'])->name('track.listen_progress');
    });


    Route::post('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');


    //FAVORITES
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


    //FAVORITE CONTROLLER
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
}
);



//Route::get('/home', [HomeController::class, 'index'])
//    ->name('home');
