<?php

namespace App\Models\Providers;

use App\MusicGenre;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        Paginator::useBootstrap();
        View::composer(['music.create', ''], function ($view) {
            $view->with('genres', MusicGenre::options());
        });
    }
}
