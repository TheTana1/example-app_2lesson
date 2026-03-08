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
        $filtersString = implode(',',$request->all() ?? '')."_{$page}_".$user->id;

        $data = Cache::remember($filtersString, now()->addMinutes(10), function() use ($request, $user) {
            // Получаем треки с пользователями (для проверки избранного)
            $tracks = $this->musicFilters
                ->apply($request, Music::query())
                ->with(['users' => function($query) use ($user) {
                    $query->where('user_id', $user->id);
                }])
                ->paginate(self::PER_PAGE)
                ->withQueryString();

            // Получаем ID избранных треков для быстрой проверки
            $favoriteIds = $user->musics()->pluck('music_id')->toArray();

            return [
                'tracks' => $tracks,
                'favoriteIds' => $favoriteIds,
                'user' => $user,
                'genres' => MusicGenre::options(),
                'pageTitle' => 'All Music',
            ];
        });

        return view('music.index', $data);
    }

    public function create(): View
    {
        return view('music.create', [
            'genres' => MusicGenre::options(),
        ]);
    }


    public function saveFavorite(Request $request, Music $music): RedirectResponse
    {
        $user = Auth::user();
        $page = $request->get('page', 1);
        $filtersString = implode(',',$request->all() ?? '').$user->id;

        $user->musics()->toggle($music->id);
        Cache::forget(
            "favorite_music_page_{$page}_{$filtersString}");

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


        if ($this->musicRepository->destroy($music))
            return redirect()->route('music.index')
                ->with('success', 'Successfully deleted music!');
        return redirect()->route('music.index')
            ->with('error', 'Failed to delete music!');
    }


    public function trackListenProgress(Request $request): JsonResponse
    {

        //$user = $request->user();
        $music = Music::query()->findOrFail($request->track_id);
        $music->plays += 1;
        $music->save();
        Cache::put('music_'. $music->id, $music, 120);
        return response()->json(['plays' => $music->plays,
            'status' => 'success',]);
    }

    public function update(MusicRequest $request, int $musicId): RedirectResponse
    {
        $music = Music::query()->findOrFail($musicId);

        $newTrack = $this->musicRepository->update($request, $music);
        return redirect()->route('music.show', $newTrack);
    }
}
