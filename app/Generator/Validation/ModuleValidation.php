<?php

namespace App\Generator\Validation;

use App\Generator\Generator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ModuleValidation implements ValidationRule
{

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $modules = Generator::moules();
        if (!in_array($value, $modules)) {
            $fail('The :attribute modules not exists.');
        }
    }

}