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
use JetBrains\PhpStorm\NoReturn;
use App\Repository\MusicRepository;

class MusicController extends Controller
{
    public function __construct(private MusicRepository $musicRepository)
    {
        $this->musicRepository= $musicRepository;
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
        return view('music.create',[
            'genres' => MusicGenre::options(),
        ]);
    }
    public function store(MusicRequest $request)
    {
        return redirect(route('music.index'))->with([$this->musicRepository->store($request)]);
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
