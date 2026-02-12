<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $percent70 = mb_strlen($value) * 0.7;
        $rusLetters= preg_match_all('/[а-яё]/ui', $value, $matches);

        //dd($value ." ". mb_strlen($value), $rusLetters, $percent70);
        if( count($matches[0]) < $percent70){
            $fail('Имя должно содерать русские буквы');
        }
    }
}
