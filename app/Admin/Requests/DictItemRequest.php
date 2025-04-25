<?php

namespace App\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DictItemRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->isMethod('put')) {
            return [
                'id' => 'required|integer|exists:dict_item,id',
                'dict_id' => 'required|integer|exists:dict,id',
                'label' => 'required',
                'value' => 'required',
                'status' => 'required',
                'switch' => 'required',
            ];
        }

        return [
            'dict_id' => 'required|integer|exists:dict,id',
            'label' => 'required',
            'value' => 'required',
            'status' => 'required',
            'switch' => 'required',
        ];
    }
}
