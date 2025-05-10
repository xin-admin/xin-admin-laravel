<?php

namespace App\Generator\Validation;

use App\Generator\Enum\SqlColumnType;
use App\Generator\Generator;
use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class DbColumnsTypeValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (SqlColumnType::tryFrom($value) == null) {
            $fail('The :attribute is not sql column type.');
        }
    }
}