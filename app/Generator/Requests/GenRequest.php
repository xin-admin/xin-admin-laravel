<?php

namespace App\Generator\Requests;

use App\Generator\Validation\ColumnsTypeValidation;
use App\Generator\Validation\ModuleValidation;
use Illuminate\Foundation\Http\FormRequest;

class GenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'modules' => ['required', 'string', new ModuleValidation],
            'controllerNamespace' => 'required',
            'modelName' => 'required',
            'modelPath' => 'required',
            'requestName' => 'required',
            'requestNamespace' => 'required',
            'controllerName' => 'required',
            'routePrefix' => 'required',
            'abilitiesPrefix' => 'required',
            'searchField' => 'required',
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