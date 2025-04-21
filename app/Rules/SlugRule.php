<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SlugRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // trim and delete whitespace
        $hasWhitespace = preg_match('/\s/', $value);
        if ($hasWhitespace) {
            $fail('اسلاگ نامعتبر');
        }
    }
}
