<?php

namespace App\Models\Providers;

use App\Models\Music;
use App\MusicGenre;
use App\Observers\MusicObserver;
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
        Music::observe(MusicObserver::class);
    }
}
