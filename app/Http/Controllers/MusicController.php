<?php

namespace App\Http\Controllers;

use App\Filters\MusicFilters;
use App\Http\Requests\MusicRequest;
use App\Models\Comment;
use App\Models\Music;
use App\MusicGenre;
use App\Services\CacheService;
use App\Services\MusicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Repository\MusicRepository;

class MusicController extends Controller
{
    public function __construct(
        private MusicRepository $musicRepository,
        readonly MusicService $musicService,
        readonly CacheService $cacheService)
    {
        $this->musicRepository = $musicRepository;
    }




    public function index(Request $request): View
    {

        //        $this->cacheService->userCache();
        $page = $request->get('page', 1);

        $filtersString =implode(',', $request->all() ?? '') . "_{$page}_";

        return view('music.index', [
            'tracks' => $this->cacheService
                ->paginateCache(
                    $request,
                    $filtersString,
                    new Music()
                ),
            'user' => Auth::user(),
            'genres' => MusicGenre::options(),
            'pageTitle' => 'All Musics',
        ]);
    }

    public function create(): View
    {
        return view('music.create', [
            'genres' => MusicGenre::options(),
        ]);
    }


    public function saveFavorite(Request $request, Music $music): RedirectResponse
    {
        $page = $request->get('page', 1);
        $filtersString = implode(',', $request->all() ?? '') . "_{$page}_";

        Auth::user()->musics()->toggle($music->id);
        $this->cacheService->forgetCache("favorite_music_page_{$filtersString}");
        $this->cacheService->forgetCache($filtersString);


        return redirect()->back();
    }

    public function show(Music $music): View
    {

        $comments = $music->comments()->orderByDesc('id')->with(['user.avatar'])->paginate(10);
        return view('music.show', [
            'track' => $this->cacheService
                ->singleCache('music_'.$music->id, $music),
            'comments' => $comments,
        ]);
    }

    public function edit(Music $music)
    {
            return view('music.edit', [
                'track' => $this->cacheService
                    ->singleCache('music_'.$music->id, $music),
            ]);

    }

    public function store(MusicRequest $request): RedirectResponse
    {
        $newMusic = $this->musicRepository->store($request);
        return redirect()->route('music.show',
            $this->cacheService->singleCache('music_'.$newMusic->id, $newMusic));
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
        $page = $request->get('page', 1);
        $filtersString = implode(',', $request->all() ?? '') . "_{$page}_";
        $this->cacheService->forgetCache($filtersString);

        return response()->json([
            'plays' => $this->musicService->trackListenProgress($request)->plays,
            'status' => 'success',]);
    }

    public function update(MusicRequest $request, Music $music): RedirectResponse
    {

        $newTrack = $this->musicRepository->update($request, $music);
        return redirect()->route('music.show', $newTrack);
    }
}
