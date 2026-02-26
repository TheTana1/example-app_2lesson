<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MusicController extends Controller
{
    private const PER_PAGE = 10;
    public function index(): View
    {
        $tracks = Music::query()->paginate(self::PER_PAGE);
        //dd($tracks);
        return view('music.index', ['tracks' => $tracks]);
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
}
