<?php

namespace App\Http\Controllers;

use App\Filters\MusicFilters;
use App\Http\Requests\MusicRequest;
use App\Models\Music;
use App\MusicGenre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use App\Repository\MusicRepository;

class MusicController extends Controller
{
    public function __construct(private MusicRepository $musicRepository,
                                readonly MusicFilters   $musicFilters)
    {
        $this->musicRepository = $musicRepository;
    }

    private const PER_PAGE = 10;

    public function index(Request $request): View
    {
        $user = Auth::user();
        $page = $request->get('page', 1);
        $filtersString = implode(',',$request->all() ?? '').$user->id;

        //обновляем кеш новых этих как там.......избранных песен в индексе
        //т.к слишком будет нагружено делать по другому+ у пользователя
        //есть шанс передумать пока он на странице избранного
        Cache::put(
            "favorite_music_page_{$page}_{$filtersString}",
            $this->musicFilters
                ->apply($request, $user->musics()->getQuery())
            ->paginate(self::PER_PAGE)->withQueryString(),
            60
        );//очень тяжело думать о том как это всё рвботает
        $tracks = Cache::remember(
                "music_page_{$page}_{$filtersString}",
                60,
                fn()=>$this->musicFilters
                    ->apply($request, Music::query())
                    ->paginate(self::PER_PAGE),
            );


        return view('music.index', [
            'tracks' => $tracks,
            'pageTitle' => 'All Music',
            'genres' => MusicGenre::options(),

        ]);
    }

    public function create(): View
    {
        return view('music.create', [
            'genres' => MusicGenre::options(),
        ]);
    }


    public function saveFavorite(Music $music): RedirectResponse
    {
        $user = Auth::user();

        $user->musics()->toggle($music->id);

        return redirect()->back();
    }

    public function show(int $musicId): View
    {



        if (Cache::has('music_' . $musicId)) {

            return view('music.show', [
                'track' => Cache::get('music_' . $musicId)
            ]);
        }
        $music = Music::query()->findOrFail($musicId);
        Cache::put('music_' . $musicId, $music, now()->addMinutes(10));

        return view('music.show', [
            'track' => $music,
        ]);
    }

    public function edit(int $musicId)
    {
        if (Cache::has('music_' . $musicId)) {

            return view('music.edit', [
                'track' => Cache::get('music_' . $musicId)
            ]);
        }
        $music = Music::query()->findOrFail($musicId);
        Cache::put('music_' . $musicId, $music, now()->addMinutes(10));

        return view('music.edit', [
            'track' => $music,
        ]);
    }

    public function store(MusicRequest $request): RedirectResponse
    {
        $newMusic = $this->musicRepository->store($request);
        return redirect()->route('music.show', $newMusic->id);
    }

    public function destroy(int $musicId): RedirectResponse
    {
        $music = Music::query()->findOrFail($musicId);

        Cache::forget('music_' . $musicId);
        if ($this->musicRepository->destroy($music))
            return redirect()->route('music.index')
                ->with('success', 'Successfully deleted music!');
        return redirect()->route('music.index')
            ->with('error', 'Failed to delete music!');
    }


    public function trackListenProgress(Request $request): JsonResponse
    {

        //$user = $request->user();
        $track = Music::query()->findOrFail($request->track_id);
        $track->plays += 1;
        $track->save();
        return response()->json(['plays' => $track->plays,
            'status' => 'success',]);
    }

    public function update(MusicRequest $request, int $musicId): RedirectResponse
    {
        $music = Music::query()->findOrFail($musicId);

        $newTrack = $this->musicRepository->update($request, $music);
        Cache::put('music_'. $musicId, $newTrack, now()->addMinutes(10));
        return redirect()->route('music.show', $newTrack);
    }
}
