<?php

namespace App\Http\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DictItemRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|integer|exists:dict_item,id',
            'label' => 'required',
            'value' => 'required',
            'status' => 'required',
            'switch' => 'required'
        ];
    }

}