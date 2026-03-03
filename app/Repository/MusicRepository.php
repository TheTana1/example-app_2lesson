<?php


use App\Models\Music;
use Illuminate\Support\Facades\DB;

class MusicRepository
{
    final function store(Request $request): Response
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
        }
        return Music::query()->create($request->validated());
    }
}
