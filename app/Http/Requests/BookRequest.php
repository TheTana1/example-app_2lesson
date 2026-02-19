<?php

namespace App\Http\Requests;

use App\Rules\NameRule;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        return [
            'title' => 'required|string|max:255',
            'user_slug' => 'required|string|exists:users,slug',
            'author' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1800|max:2099',
        ];
    }

    public function messages(): array
    {
        return [
            'user_slug.exists' => 'Пользователь с таким slug не найден',
        ];
    }
}
