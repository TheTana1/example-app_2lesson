<?php

namespace App\Services;

use App\Models\Music;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MusicService
{
    public function saveFavorite(Request $request, Music $music): RedirectResponse
    {
        $user = Auth::user();
        $page = $request->get('page', 1);
        $filtersString = implode(',', $request->all() ?? '') . $user->id;

        $user->musics()->toggle($music->id);
        Cache::forget(
            "favorite_music_page_{$page}_{$filtersString}");

        return redirect()->back();
    }

    public function trackListenProgress(Request $request): Music
    {


        $music = Music::query()->findOrFail($request->track_id);
        $music->plays += 1;
        $music->save();
        return $music;
    }
}
