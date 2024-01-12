<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Upercase implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // upercase
        if (strtoupper($value) !== $value) {
            // $fail('the :attribute must be uppercase');
            $fail('validation.uppercase')->translate([
                'attribute'     => $attribute,
                'value'         => $value
            ]);
        }
    }
}
