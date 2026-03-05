<?php


namespace App\Repository;

use App\Http\Requests\MusicRequest;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use function Symfony\Component\VarDumper\Dumper\esc;


class MusicRepository
{
    final function store(MusicRequest $request)
    {
        DB::beginTransaction();

        $fileAudioPath = null;
        $fileCoverPath = null;

        try {
            $validatedData = $request->validated();

            // Обработка загрузки обложки
            if ($request->hasFile('cover_path')) {
                $extension = $request->file('cover_path')->getClientOriginalExtension();
                $hashedName = md5_file($request->file('cover_path')->getRealPath()) . '.' . $extension;

                if (!Storage::disk('public')->exists('cover/' . $hashedName)) {
                    $fileCoverPath = '/storage/' . $request->file('cover_path')->storeAs('cover', $hashedName, 'public');
                } else {
                    $fileCoverPath = '/storage/cover/' . $hashedName;
                }

                $validatedData['cover_path'] = $fileCoverPath;
            }

            // Обработка загрузки аудио файла
            if ($request->hasFile('file_path')) {
                $extension = $request->file('file_path')->getClientOriginalExtension();
                $hashedName = md5_file($request->file('file_path')->getRealPath()) . '.' . $extension;

                if (!Storage::disk('public')->exists('music/' . $hashedName)) {
                    $fileAudioPath = '/storage/' . $request->file('file_path')->storeAs('music', $hashedName, 'public');
                } else {
                    $fileAudioPath = '/storage/music/' . $hashedName;
                }

                $validatedData['file_path'] = $fileAudioPath;
            } else {
                throw new \Exception('Аудио файл не загружен');
            }

            // Обработка artists
            if (isset($request->artists)) {
                $validatedData['artists'] = explode(',', $request->artists);
                for ($i = 0; $i < count($validatedData['artists']); $i++) {
                    $validatedData['artists'][$i] = trim($validatedData['artists'][$i]);
                }
            }

            $validatedData['plays'] = 0;
            $validatedData['genre'] = $request->genre;

            $music = Music::query()->create($validatedData);

            DB::commit();

            return $music;

        } catch (\Exception $exception) {
            DB::rollBack();

            // Удаляем файлы только если они были созданы в этой транзакции
            if (isset($fileAudioPath) && !str_contains($fileAudioPath, '/storage/music/')) {
                $audioPathForDelete = str_replace('/storage/', '', $fileAudioPath);
                if (Storage::disk('public')->exists($audioPathForDelete)) {
                    Storage::disk('public')->delete($audioPathForDelete);
                }
            }

            if (isset($fileCoverPath) && !str_contains($fileCoverPath, '/storage/cover/')) {
                $coverPathForDelete = str_replace('/storage/', '', $fileCoverPath);
                if (Storage::disk('public')->exists($coverPathForDelete)) {
                    Storage::disk('public')->delete($coverPathForDelete);
                }
            }

            Log::critical('Ошибка при создании трека: ' . $exception->getMessage());
            throw $exception;
        }
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

    public function update(MusicRequest $request, Music $oldMusic): Music
    {


        DB::beginTransaction();
        $fileAudioPath = null;
        $fileCoverPath = null;

        try {
            $validatedData = $request->validated();


//            if (empty($validatedData['cover_path'])) {
//                $validatedData['cover_path'] = $oldMusic->cover_path;
//            }
//            if (empty($validatedData['file_path'])) {
//                $validatedData['file_path'] = $oldMusic->file_path;
//            }

            // Обработка загрузки обложки
            if ($request->hasFile('cover_path')) {
                $extension = $request->file('cover_path')->getClientOriginalExtension();
                $hashedName = md5_file($request->file('cover_path')->getRealPath()) . '.' . $extension;
                File::delete(public_path($oldMusic->cover_path));
                $fileCoverPath = '/storage/' . $request->file('cover_path')->storeAs('cover', $hashedName, 'public');
                $validatedData['cover_path'] = $fileCoverPath;
            }
            else{
                $validatedData['cover_path'] = $oldMusic->cover_path;
            }
            // Обработка загрузки аудио файла
            if ($request->hasFile('file_path')) {
                $extension = $request->file('file_path')->getClientOriginalExtension();
                $hashedName = md5_file($request->file('file_path')->getRealPath()) . '.' . $extension;
                File::delete(public_path($oldMusic->file_path));
                $fileAudioPath = '/storage/' . $request->file('file_path')->storeAs('music', $hashedName, 'public');
                $validatedData['file_path'] = $fileAudioPath;
            }
            else{
                $validatedData['file_path'] = $oldMusic->file_path;
            }
            //dd($request->all());
            // Обработка artists
            if (isset($request->artists_string)) {
                $validatedData['artists'] = explode(',', $request->artists_string);
                for ($i = 0; $i < count($validatedData['artists']); $i++) {
                    $validatedData['artists'][$i] = trim($validatedData['artists'][$i]);
                }

            }
            else {
                $validatedData['artists'] = $oldMusic->artists;

            }

            $validatedData['plays'] = $oldMusic->plays;
            $validatedData['genre'] = $oldMusic->genre;

            $oldMusic->update($validatedData);
            $oldMusic->refresh();

            DB::commit();

            return $oldMusic;

        } catch (\Exception $exception) {
            DB::rollBack();

            // Удаляем файлы только если они были созданы в этой транзакции
            if (isset($fileAudioPath) && !str_contains($fileAudioPath, '/storage/music/')) {
                $audioPathForDelete = str_replace('/storage/', '', $fileAudioPath);
                if (Storage::disk('public')->exists($audioPathForDelete)) {
                    Storage::disk('public')->delete($audioPathForDelete);
                }
            }

            if (isset($fileCoverPath) && !str_contains($fileCoverPath, '/storage/cover/')) {
                $coverPathForDelete = str_replace('/storage/', '', $fileCoverPath);
                if (Storage::disk('public')->exists($coverPathForDelete)) {
                    Storage::disk('public')->delete($coverPathForDelete);
                }
            }

            Log::critical('Ошибка при создании трека: ' . $exception->getMessage());
            throw $exception;
        }
    }
}
