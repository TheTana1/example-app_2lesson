<?php


namespace App\Repository;

use App\Http\Requests\MusicRequest;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MusicRepository
{
    final function store(MusicRequest $request): Music
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validated();

            // Обработка загрузки обложки
            if ($request->hasFile('cover_path')) {
                $path = $request->file('cover_path')->store('covers', 'public');
                $validatedData['cover_path'] = 'storage/' . $path;
            }

            // Обработка загрузки аудио файла
            if ($request->hasFile('file_path')) {
                $path = $request->file('file_path')->store('music', 'public');
                $validatedData['file_path'] = 'storage/' . $path;
            }

            // Создание записи
            $music = Music::query()->create($validatedData);

            DB::commit();

            return $music;

        }
        catch (\Exception $e) {
            DB::rollBack();

            // Логируем ошибку
            \Log::error('Ошибка при создании трека: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return $music;
        }
    }
}
