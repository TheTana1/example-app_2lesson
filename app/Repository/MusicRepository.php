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
                $validatedData['artists'] = explode(',', trim($request->artists));

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
            dd(123);
            if (!$request->hasFile('cover_path')){
                $request->cover_path = $oldMusic->cover_path;
            }
            if ($request->hasFile('file_path')){
                dd(public_path($oldMusic->cover_path));
                $request->file_path = file_path;
            }
            $validatedData = $request->validated();

            // Обработка загрузки обложки
            if ($request->hasFile('cover_path')) {

                $extension = $request->file('cover_path')->getClientOriginalExtension();
                $hashedName = md5_file($request->file('cover_path')->getRealPath()) . '.' . $extension;
                dd(public_path($oldMusic->cover_path),$fileCoverPath = '/storage/cover/' . $hashedName);


                if (!Storage::disk('public')->exists('cover/' . $hashedName)) {
                    //удаление старой обложки
                    if (file_exists(public_path($oldMusic->cover_path))) {
                        File::delete(public_path($oldMusic->cover_path));
                    }

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
                    //удаление старой обложки
                    if (file_exists(public_path($oldMusic->file_path))) {
                        File::delete(public_path($oldMusic->file_path));
                    }


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
                $validatedData['artists'] = explode(',', trim($request->artists));

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
}
