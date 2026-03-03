<?php

namespace App\Http\Requests;

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
        return [
            'title' => 'required|string',
            'artists' => 'required|string',
            'file_path' => 'required|string',
            'cover_path' => 'nullable|string',
            'duration' => 'required|integer',
            'release_date' => 'required|date',
            'genre' => 'required|string',
            'is_published' => 'nullable|boolean|default:false',

            //
        ];
    }
}
