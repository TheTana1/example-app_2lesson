<?php

namespace App\Repository;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Avatar;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserRepository
{
    final public function store(UserStoreRequest $userStoreRequest): User
    {
        DB::beginTransaction();
        try {
            $filePath = 'storage/' . $userStoreRequest->
                file('avatar')->
                store('avatars', 'public');

            $user = User::query()->create($userStoreRequest->validated());

            Avatar::query()->create([
                'user_id' => $user->id,
                'path' => $filePath,
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            Log::critical($exception->getMessage());
            DB::rollBack();
            throw new BadRequestException($exception->getMessage());
        }
        return $user;
    }

    final public function update(UserUpdateRequest $userUpdateRequest, User $user): User
    {
        DB::beginTransaction();
        try {
            $validatedData = $userUpdateRequest->validated();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }
            if (!empty($validatedData['avatar'])) {
                $filePath = 'storage/' . $userUpdateRequest->
                    file('avatar')->
                    store('avatars', 'public');
            }
            $user->save();
            Avatar::query()->create([
                'user_id' => $user->id,
                'path' => $filePath,
            ]);
            DB::commit();
        } catch (\Exception $exception) {
            Log::critical($exception->getMessage());
            DB::rollBack();
            throw new BadRequestException($exception->getMessage());
        }

//        if (isset($validatedData['password'])) {
//            $validatedData['password'] = Hash::make($validatedData['password']);
//        } else {
//            unset($validatedData['password']);
//        }
//        if ($validatedData['email'] == $user->email) {
//            unset($validatedData['email']);
//        }
//        $user->update($validatedData);


        return $user->refresh();
    }

    final public function destroy(User $user): bool
    {
        $fileAvatar = '/avatars/' . basename($user->avatar->path);
        Storage::disk('public')->delete($fileAvatar);
        return $user->forceDelete();
    }
}
