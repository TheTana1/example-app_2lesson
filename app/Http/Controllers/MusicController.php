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
use Illuminate\View\View;
use App\Repository\MusicRepository;

class MusicController extends Controller
{
    public function __construct(private MusicRepository $musicRepository,
    readonly MusicFilters $musicFilters)
    {
        $this->musicRepository = $musicRepository;
    }

    private const PER_PAGE = 10;

    public function index(Request $request): View
    {


        $tracks = Music::query();

        //dd($tracks);
        return view('music.index', [
            'tracks' => $this->musicFilters
                ->apply($request,$tracks)->paginate(self::PER_PAGE),
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
            'track' =>  $music,
        ]);
    }

    public function edit(Music $music)
    {
        return view('music.edit', [
            'track' =>  $music,
        ]);
    }

    public function store(MusicRequest $request): RedirectResponse
    {
       // dd(trim(explode(',', $request->artists)[1]));
        $newMusic = $this->musicRepository->store($request);
        return redirect()->route('music.show', $newMusic->id);
    }

    public function destroy(Music $music): RedirectResponse
    {

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

    public function update(MusicRequest $request, Music $music): RedirectResponse
    {

        $newTrack = $this->musicRepository->update($request, $music);
        return redirect()->route('music.show',$newTrack);
    }
}
