<?php

namespace App\Rules;

use App\Http\Const\DefaultConst;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductTypeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! in_array($value, DefaultConst::PRODUCT_TYPE)) {
            $fail($attribute.' نامعتبر است');
        }
    }
}
