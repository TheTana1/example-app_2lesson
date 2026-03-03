<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use JetBrains\PhpStorm\NoReturn;

class MusicController extends Controller
{
    private const PER_PAGE = 10;
    public function index(): View
    {
        $tracks = Music::query()->paginate(self::PER_PAGE);
        //dd($tracks);
        return view('music.index', [
            'tracks' => $tracks,
            'pageTitle' => 'All Music',
        ]);
    }

    public function create(): View
    {
        return view('music.create');
    }
    public function saveFavorite(Music $music): RedirectResponse
    {
        $user = Auth::user();

        if ($user->musics()->where('id', $music->id)->exists()) {
            $user->musics()->detach($music->id);
        }else{
            $user->musics()->attach($music->id);
        }

        return redirect()->back();
    }


    public function trackListenProgress(Request $request): JsonResponse
    {

         //$user = $request->user();
         $track = Music::query()->findOrFail($request->track_id);
         $track -> plays += 1;
         $track -> save();
         return response()->json(['plays'=>$track->plays,
         'status' => 'success',]);
    }
}
