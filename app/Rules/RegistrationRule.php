<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class RegistrationRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    private array $data;
    protected Validator $validator;

    public function setData(array $data)
    {
        $this->data = $data;
        return $data;
    }

    public function setValidator(Validator $validator) : RegistrationRule
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->data['username'] == $value) {
            $fail("$attribute must be diferent with username")->translate();
        }
    }
}
