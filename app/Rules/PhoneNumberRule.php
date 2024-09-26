<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //should only contain numbers
        $number = [0,1,2,3,4,5,6,7,8,9];
        foreach(str_split($value) as $key => $index){
            if(!in_array($index,$number)){
                $fail('شماره وارد شده معتبر نمی باشد!'.'char');
            }
        }
        $attribute = 'sample';
        //should start with 09
        if(!str_starts_with($value, '09')){
            //TODO other number prefix validation like 0955 doesnt exist
            $fail('شماره وارد شده معتبر نمی باشد!');
        }
    }
}
