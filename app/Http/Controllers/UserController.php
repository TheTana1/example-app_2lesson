<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name'     => 'required|max:255',
                'email'    => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ],
            [
                // Сообщения для поля name
                'name.required'    => 'Поле "Имя" обязательно для заполнения.',
                'name.max'         => 'Имя не может быть длиннее 255 символов.',

                // Сообщения для поля email
                'email.required'   => 'Поле "Email" обязательно для заполнения.',
                'email.email'      => 'Введите корректный адрес электронной почты.',
                'email.max'        => 'Email не может быть длиннее 255 символов.',
                'email.unique'     => 'Пользователь с таким email уже зарегистрирован.',

                // Сообщения для поля password
                'password.required'    => 'Поле "Пароль" обязательно для заполнения.',
                'password.min'         => 'Пароль должен содержать минимум 6 символов.',
                'password.confirmed'   => 'Пароли не совпадают.',
            ]
        );

        $newUser = new User;
        $newUser->name = $validatedData['name'];
        $newUser->email = $validatedData['email'];
        $newUser->password = Hash::make($validatedData['password']);
        $newUser->save();

        return redirect()->route('users.show', $newUser->id);
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate(
            [
                'name'     => 'required|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            ],
            [
                // Сообщения для поля name
                'name.required'    => 'Поле "Имя" обязательно для заполнения.',
                'name.max'         => 'Имя не может быть длиннее 255 символов.',

                // Сообщения для поля email
                'email.required'   => 'Поле "Email" обязательно для заполнения.',
                'email.email'      => 'Введите корректный адрес электронной почты.',
                'email.max'        => 'Email не может быть длиннее 255 символов.',
                'email.unique'     => 'Пользователь с таким email уже зарегистрирован.',

                // Сообщения для поля password
                'password.min'         => 'Пароль должен содержать минимум 6 символов.',
                'password.confirmed'   => 'Пароли не совпадают.',
            ]
        );
        $user['name'] = $validatedData['name'];
        $user-> email = $validatedData['email'];

        //проверка на заполнение поля password
        if ($request->filled('password')) {
            $validatedData['password'] = 'confirmed|min:6';
            $user['password'] = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('users.show', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
