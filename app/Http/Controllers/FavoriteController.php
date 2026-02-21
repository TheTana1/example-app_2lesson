<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    private const PER_PAGE = 10;

    public function index(): View
    {
        $user = Auth::user();
        $tracks = $user->musics()->paginate(10, self::PER_PAGE);


        return view('music.index',
            [
                'tracks' => $tracks,
                'pageTitle' => 'Favorites Musics',
            ]);

    }

    public function trackListenProgress(Request $request):JsonResponse
    {
//        $user = $request->user();
        $track = Music::query()->findOrFail($request->track_id);
        $track->plays+=1;


    }
}
