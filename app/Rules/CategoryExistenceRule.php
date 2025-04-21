<?php

namespace App\Rules;

use App\Http\Const\DefaultConst;
use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryExistenceRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Category::where('slug', $value)->exists()) {
            $fail(DefaultConst::FAILED_CATEGORY_EXISTENCE);
        }
    }
}
