<?php

namespace App\Generator\Requests;

use App\Generator\Validation\DbColumnsTypeValidation;
use App\Generator\Validation\ModuleValidation;
use App\Generator\Validation\PathValidation;
use App\Generator\Validation\QuickSearchFieldValidation;
use Illuminate\Foundation\Http\FormRequest;

class GenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:30'],
            'module' => ['required', 'string', new ModuleValidation],
            'path' => ['required', 'string', new PathValidation],
            'routePrefix' => ['required', 'string', 'max:255'],
            'abilitiesPrefix' => ['required', 'string', 'max:255'],
            'pageRoute' => ['required', 'string', 'max:255'],
            'page_is_file' => ['required', 'boolean'],
            'crudRequest' => ['required', 'array'],
            'crudRequest.*' => ['required', 'string', 'in:create,update,delete,query,find'],
            'quickSearchField' => ['required', 'array', new QuickSearchFieldValidation],


            'dbColumns' => 'required|array|min:1',
            'dbColumns.*.name' => 'required|string|max:64',
            'dbColumns.*.type' => ['required', 'string', new DbColumnsTypeValidation],
            'dbColumns.*.nullable' => 'boolean',
            'dbColumns.*.default' => 'nullable',
            'dbColumns.*.length' => 'nullable|integer|min:1',
            'dbColumns.*.precision' => 'nullable|integer|min:1',
            'dbColumns.*.scale' => 'nullable|integer|min:0',
            'dbColumns.*.unsigned' => 'boolean',
            'dbColumns.*.autoIncrement' => 'boolean',
            'dbColumns.*.comment' => 'nullable|string|max:255',
            'dbColumns.*.primaryKey' => 'boolean',
        ];
    }
}