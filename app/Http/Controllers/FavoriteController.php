<?php

namespace App\Http\Controllers;

use App\Filters\MusicFilters;
use App\Models\Music;
use App\MusicGenre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function __construct(readonly MusicFilters $musicFilters)
    {
    }

    private const PER_PAGE = 10;

    public function index(Request $request): View
    {
        $user = Auth::user();
        $page = $request->get('page', 1);
        $filtersString = implode(',',$request->all() ?? '').$user->id;
//        Cache::put(
//            "favorite_music_page_{$page}_{$filtersString}",
//            $this->musicFilters
//                ->apply($request, $user->musics()->getQuery())
//                ->paginate(self::PER_PAGE),
//
//            60
//        );
        return view('music.index',
            [
                'tracks' => Cache::get(
                    "favorite_music_page_{$page}_{$filtersString}"),
                'pageTitle' => 'Favorite Music '. $user->name,
                'genres' => MusicGenre::options(),
            ]);

    }

//    public function trackListenProgress(Request $request):JsonResponse
//    {
////        $user = $request->user();
//        $track = Music::query()->findOrFail($request->track_id);
//        return $track->plays+=1;
//
//
//    }
}
