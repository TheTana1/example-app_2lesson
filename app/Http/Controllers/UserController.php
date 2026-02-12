<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    private UserRepository $userRepository;

    /**
     * Display a listing of the resource.
     */

    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        //        dd(User::withTrashed()->get()); // с мягким удалением
//        $user =Auth::user();
//        $page = 3;
//        $perPage = 10;
//        $usersIds = [
//            33, 66, 99
//        ];
//
//
//        dd(
//            DB::table('users')->
//            join('phones', 'users.id', '=', 'phones.user_id')->
//            get(),
//
//            DB::table('users')->select(['id', 'name', 'email', 'avatar'])->
//            take(10)->orderByDesc('id')->get(),
//            User::query()->select(['id', 'name', 'email', 'avatar'])->
//            take(10)->orderByDesc('id')->get(),
//            User::query()->skip($page * $perPage - $perPage)->
//            take($perPage)->get(),
//            User::query()->select('id', 'name')->
//            whereIn('id', $usersIds)->get()->toArray(),
//            User::query()->select('id', 'name')->
//            whereIn('id', $usersIds)->sum('id'),
//            User::query()->select('id', 'name')->
//            whereIn('id', $usersIds)->count(),
//            User::query()->select('id', 'name')->
//            has('phones')->get(),
//            User::query()->select('id', 'name')->
//            doesntHave('phones')->get(),
//            User::query()->with('phones')->whereHas('phones', function ($filter){
//                $filter->where('number', '+1-341-439-2098');
//            })
        //);
        $users = User::query()->paginate(10);

        return view('users.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $userStoreRequest)
    {
        //dd($userStoreRequest->input());
        //$user = User::query()->create($userStoreRequest->validated());

        return redirect()->route('users.show',
            $this->userRepository->store($userStoreRequest));

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $userUpdateRequest, User $user)
    {
        return redirect()->
        route('users.show',
            $this->userRepository->
            update($userUpdateRequest, $user))->
        with('success', 'Пользователь удачно обновлён');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $userRemoveResult = $this->userRepository->destroy($user);
        if ($userRemoveResult) {
            return redirect()->route('users.index')->
            with('success', 'Пользователь удалён');
        }
        return redirect()->route('users.index')->
        withErrors('error', 'Пользователь не найден');

    }
}
