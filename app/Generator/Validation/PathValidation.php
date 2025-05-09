<?php

namespace App\Generator\Validation;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use Illuminate\Support\Facades\File;

class PathValidation implements ValidationRule, DataAwareRule
{

    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $value);
        if (! File::exists(module_path($this->data['module'], $path))) {
            $fail('The :attribute path is not exists.');
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }



}