<?php

namespace App\Http\Requests;

use App\MusicGenre;
use Illuminate\Foundation\Http\FormRequest;

class MusicRequest extends FormRequest
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
//        * @property string $title
//    * @property string $artists
//    * @property string $file_path
//    * @property string $cover_path
//    * @property integer $duration
//    * @property Carbon $release_date
//    * @property boolean $is_published
//    * @property integer $plays
//    * @property integer $genre
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|string|max:255',
                    'artists' => 'required|string|max:255',
                    'file_path' => 'required|file|mimes:mp3,wav',
                    'cover_path' => 'nullable|image|mimes:jpg,jpeg,png',
                    'duration' => 'required|integer|min:1',
                    'release_date' => 'required|date',
                    'genre' => ['required', 'string', 'in:' . implode(',', MusicGenre::values())],
                    'is_published' => 'nullable|boolean',

                    //
                ];
            case 'PATCH':
                return [
                    'title' => 'nullable|string|max:255',
                    'artists' => 'nullable|string|max:255',
                    'file_path' => 'nullable|file|mimes:mp3,wav',
                    'cover_path' => 'nullable|image|mimes:jpg,jpeg,png',
                ];
        }
        return [];
    }
}
