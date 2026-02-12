<?php

namespace App\Http\Requests;

use App\Rules\NameRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
                return [

                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|min:6|confirmed',
                    'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',

                ];
            case 'PUT':
                $user= $this->route()->parameter('user');
                return [
                    'name'     => [
                        'required|max:255',
                        new NameRule(),
                        ] ,
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
        return [];
    }
}
