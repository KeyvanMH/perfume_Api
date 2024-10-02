<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valueArray = str_split($value);
        $number = [0,1,2,3,4,5,6,7,8,9];
        $firstIndex = [0,1,2,3,4,5,6,7,8,9,"-","+"];
        if (!in_array($valueArray[0],$firstIndex)){
            $fail("ورودی نامعتبر");
        }
        if(($valueArray[0] == "+" or $valueArray[0] == "-") and count($valueArray) <2){
            $fail("ورودی نامعتبر");
        }
        unset($valueArray[0]);
        if (count($valueArray) > 0) {
            foreach ($valueArray as $item) {
                if (!in_array($item, $number)) {
                    $fail("ورودی نامعتبر");
                }
            }
        }
    }
}
