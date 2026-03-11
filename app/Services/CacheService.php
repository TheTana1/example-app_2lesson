<?php

namespace App\Services;

use App\Filters\MusicFilters;
use App\Models\Music;
use App\MusicGenre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    private const PER_PAGE = 10;
    private const CACHE_TTL = 10;

    public function __construct(readonly MusicFilters $filters)
    {
    }

    public function userCache()
    {
    }
    public function paginateCache(
        $request, string $key, Model $model): mixed
    {
        return Cache::remember($key,
            now()->addMinutes(10),
            fn() => // Получаем треки с пользователями (для проверки избранного)
            $this->filters
                ->apply($request, $model->query())
//                ->with('users')
//                ->where('user_id', Auth::id())
                ->paginate(self::PER_PAGE)
                ->withQueryString());


    }

    public function singleCache(string $key, Model $model): mixed
    {
        return Cache::remember($key,
            now()->addMinutes(10),
            fn()=>$model);
    }

    public function forgetCache(string $key): bool
    {
        return Cache::forget($key);
    }


}
