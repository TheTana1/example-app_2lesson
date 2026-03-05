<?php

namespace App\Http\Controllers;

use App\Http\Requests\MusicRequest;
use App\Models\Music;
use App\MusicGenre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Repository\MusicRepository;

class MusicController extends Controller
{
    public function __construct(private MusicRepository $musicRepository)
    {
        $this->musicRepository = $musicRepository;
    }

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
        return view('music.create', [
            'genres' => MusicGenre::options(),
        ]);
    }



    public function saveFavorite(Music $music): RedirectResponse
    {
        $user = Auth::user();

        if ($user->musics()->where('id', $music->id)->exists()) {
            $user->musics()->detach($music->id);
        } else {
            $user->musics()->attach($music->id);
        }

        return redirect()->back();
    }

    public function show(int $musicId): View
    {

        return view('music.show', [
            'track' => Music::query()->findOrFail($musicId),
        ]);
    }

    public function edit(int $musicId)
    {
        return view('music.edit', [
            'track' => Music::query()->findOrFail($musicId),
        ]);
    }

    public function store(MusicRequest $request): RedirectResponse
    {
        $newMusic = $this->musicRepository->store($request);
        return redirect()->route('music.show', $newMusic->id);
    }

    public function delete(int $musicId): RedirectResponse
    {
        $track = Music::findOrFail($musicId);
        if ($this->musicRepository->destroy($track))
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

    public function update(MusicRequest $request, Music $music): RedirectResponse
    {
        // Временная отладка - посмотрим, приходят ли данные
        dd([
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route_name' => $request->route() ? $request->route()->getName() : 'no route',
            'route_params' => $request->route() ? $request->route()->parameters() : [],
            'all_data' => $request->all(),
            'has_files' => $request->hasFile('cover_path') || $request->hasFile('file_path'),
            'files' => $request->allFiles(),
            'ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
        ]);
        $oldTrack = Music::query()->findOrFail($musicId);
        $newTrack = $this->musicRepository->update($request, $oldTrack);
        return redirect()->route('music.show',$newTrack);
    }
}
