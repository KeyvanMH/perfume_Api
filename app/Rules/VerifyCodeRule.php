<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $number = [0,1,2,3,4,5,6,7,8,9];
        foreach (str_split($value) as $index){
            if(!in_array($index,$number)){
                $fail('ورودی نامعتبر!');
            }
        }
    }
}
