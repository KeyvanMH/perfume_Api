<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class phoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //should start with 09
        if(!str_starts_with($value, '09')){
            $fail('شماره وارد شده معتبر نمی باشد!');
            //TODO check if it works and returns json
        }
    }
}
