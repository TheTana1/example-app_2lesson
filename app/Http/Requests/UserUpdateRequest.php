<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
//    private User $user;
//    public function __construct(User $user)
//    {
//        $this->user = $user;
//    }
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user= $this->route()->parameter('user');
        return [
            'name'     => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),

            ],
            'password' => [
                'nullable',      // Можно не указывать при обновлении
                'string',        // Должен быть строкой
                'min:6',         // Минимум 6 символов
                'confirmed',     // Должен совпадать с password_confirmation
            ],
            'avatar' => ['nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'],
        ];
    }
    public function messages(): array
    {
        return [
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
        ];
    }
}
