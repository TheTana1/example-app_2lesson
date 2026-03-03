<?php


namespace App\Repository;

use App\Http\Requests\MusicRequest;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class MusicRepository
{
    final function store(MusicRequest $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validated();

            // Обработка загрузки обложки
            if ($request->hasFile('cover_path')) {
                $fileCoverPath = 'storage/' . $request->file('cover_path')->store('cover', 'public');
                $validatedData['cover_path'] = $fileCoverPath;

            }

            // Обработка загрузки аудио файла
            if ($request->hasFile('file_path')) {
                $fileAudioPath = 'storage/' . $request->file('file_path')->store('music', 'public');
                $validatedData['file_path'] = $fileAudioPath;
            }


            $validatedData['genre'] = $request->genre;
            $validatedData['artists'] = explode(',', $request->artists);
            $validatedData['plays'] = 0;

            $music = Music::query()->create($validatedData);
            DB::commit();

        } catch (\Exception $exception) {
            File::delete([
                $fileAudioPath,
                $fileCoverPath
            ]);

            DB::rollBack();
            Log::critical($exception->getMessage());
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return $music;

    }

    final function destroy(Music $track): bool
    {
        $filePath = Storage::disk('public')->path('music/' . basename($track->file_path));
        $coverPath = Storage::disk('public')->path('cover/' . basename($track->cover_path));

        if (file_exists($filePath)) {
            File::delete($filePath);
        }
        if (file_exists($coverPath)) {
            //dd(Music::where('cover_path', $track->cover_path)->count());
            if (Music::where('cover_path', $track->cover_path)->count() === 1) {
                File::delete($coverPath);
            }
        }
        if ($track->delete())
            return true;
        return false;

    }
}
