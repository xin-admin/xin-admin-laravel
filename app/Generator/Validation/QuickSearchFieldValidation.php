<?php

namespace App\Generator\Validation;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\File;
use Closure;

class QuickSearchFieldValidation implements ValidationRule, DataAwareRule
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
        is_array($value) or $fail('The :attribute must be an array.');
        $column = collect($this->data['columns']);
        $fields = $column->pluck('name');
        collect($value)->map(function ($field) use ($fields, $fail) {
            if (! $fields->contains($field)) {
                $fail('The :attribute field is not exists of columns.');
            }
        });
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