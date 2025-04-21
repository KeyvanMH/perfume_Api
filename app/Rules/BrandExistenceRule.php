<?php

namespace App\Rules;

use App\Http\Const\DefaultConst;
use App\Models\Brand;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BrandExistenceRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Brand::where('slug', $value)->exists()) {
            $fail(DefaultConst::FAILED_BRAND_EXISTENCE);
        }
    }
}
