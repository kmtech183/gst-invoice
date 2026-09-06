<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GstinRule implements ValidationRule
{
    /**
     * Indian GSTIN format: 2 digits state code + 10 char PAN + 1 entity code + 1 'Z' + 1 checksum char.
     * Total length = exactly 15 characters.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $pattern = '/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/';

        if (! preg_match($pattern, strtoupper($value))) {
            $fail('The :attribute must be a valid 15-digit Indian GSTIN (e.g., 27AABCU9603R1ZM).');
        }
    }
}
