<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HsnCodeRule implements ValidationRule
{
    /**
     * Indian HSN / SAC Code is 4, 6, or 8 digits.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        if (! preg_match('/^[0-9]{4,8}$/', $value)) {
            $fail('The :attribute must be a valid 4 to 8 digit HSN/SAC code.');
        }
    }
}
