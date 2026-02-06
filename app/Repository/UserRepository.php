<?php

namespace App\Repository;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository{
    final public function store(UserStoreRequest $userStoreRequest) : User
    {
        return User::query()->create($userStoreRequest->validated());
    }
    final public function update(UserUpdateRequest $userUpdateRequest, User $user) : bool
    {
        $validatedData = $userUpdateRequest->validated();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        if ($validatedData['email'] == $user->email) {
            unset($validatedData['email']);
        }
        return $user->update($validatedData);
    }
    final public function destroy(User $user) :bool
    {
        return $user->delete();
    }
}
