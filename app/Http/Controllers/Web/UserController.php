<?php

namespace App\Http\Controllers\Web;

use App\Filters\UserFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;


class UserController extends Controller
{
//    private UserRepository $userRepository;
    private const PER_PAGE = 10;

    /**
     * Display a listing of the resource.
     */

    public function __construct(
        private UserRepository       $userRepository,
        private readonly UserFilters $userFilters
    )
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): View
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


        $query = User::query()->with('phones.phoneBrand');
        $queryTrashed = User::onlyTrashed()->with('phones.phoneBrand');
        return view('users.index', [
            'users' => $this->userFilters
                ->apply($request, $query)
                ->paginate(10)
                ->withQueryString(),
            'usersTrashed' => $this->userFilters
                ->apply($request, $queryTrashed)
                ->paginate(5)
                ->withQueryString(),
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
        $newUser = $this->userRepository->store($userStoreRequest);
        return redirect()->route('users.show', $newUser->slug)
            ->with('success', 'Пользователь удачно создан');


    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
//dd($user->load(
//    'phones',
//    'avatar',
//    'phones.phoneBrand',
//    'books',
//));
        $user->load(
            'phones',
            'avatar',
            'phones.phoneBrand',
            'books',
        );
        //compact создает массив из переменных по их ИМЕНАМ!! vvvvvvv
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load('phones.phoneBrand');
        return view('users.edit', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $userUpdateRequest, User $user)
    {
        $updatedUser = $this->userRepository->update($userUpdateRequest, $user);
        return redirect()
            ->route('users.show', $updatedUser->slug)
            ->with('success', 'Пользователь удачно обновлён');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $userRemoveResult = $this->userRepository->destroy($user);
        if ($userRemoveResult) {
            return redirect()->route('users.index')->
            with('success', 'Пользователь в корзине');
        }
        return redirect()->route('users.index')->
        withErrors('error', 'Пользователь не найден');

    }

    public function restore($slug)
    {

        $user = User::withTrashed()->where('slug', $slug)->firstOrFail();
        $restoredUser = $this->userRepository->restore($user);
        if ($restoredUser) {
            return redirect()->route('users.index')
                ->with('success', 'Пользователь восстановлен');
        }
        return redirect()->route('users.index')
            ->withErrors('error', 'Ошибка при восстновлении');
    }

    public function forceDelete($slug)
    {

        $user = User::withTrashed()->where('slug', $slug)->firstOrFail();
        $userRemoveResult = $this->userRepository->forceDelete($user);
        if ($userRemoveResult) {
            return redirect()->route('users.index')->
            with('success', 'Пользователь удалён');
        }
        return redirect()->route('users.index')->
        withErrors('error', 'Ошибка при удалении');

    }
}
