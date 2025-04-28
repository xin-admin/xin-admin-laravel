<?php

namespace App\Generator\Requests;

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
        ];
    }
}