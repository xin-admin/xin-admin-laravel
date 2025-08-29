<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DictRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|integer|exists:dict,id',
            'name' => 'required',
            'code' => 'required',
            'type' => 'required',
            'describe' => 'required',
        ];
    }
}
