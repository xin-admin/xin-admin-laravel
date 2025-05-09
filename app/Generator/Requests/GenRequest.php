<?php

namespace App\Generator\Requests;

use App\Generator\Validation\ColumnsTypeValidation;
use App\Generator\Validation\ModuleValidation;
use App\Generator\Validation\PathValidation;
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

            'quickSearchField' => 'required|list',
            'find' => 'required|boolean',
            'create' => 'required|boolean',
            'update' => 'required|boolean',
            'delete' => 'required|boolean',
            'query' => 'required|boolean',

            'columns' => 'required|array|min:1',
            'columns.*.name' => 'required|string|max:64',
            'columns.*.type' => [
                'required',
                'string',
                new ColumnsTypeValidation,
            ],
            'columns.*.nullable' => 'boolean',
            'columns.*.default' => 'nullable',
            'columns.*.length' => 'nullable|integer|min:1',
            'columns.*.precision' => 'nullable|integer|min:1',
            'columns.*.scale' => 'nullable|integer|min:0',
            'columns.*.unsigned' => 'boolean',
            'columns.*.autoIncrement' => 'boolean',
            'columns.*.comment' => 'nullable|string|max:255',
            'columns.*.primaryKey' => 'boolean',
        ];
    }
}