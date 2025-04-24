<?php

namespace App\Admin\Request;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'title' => 'required',
            'key' => 'required|min:2|max:255|unique:setting,key,group_id',
            'group_id' => 'required|exists:setting_group,id',
            'type' => 'required',
            'describe' => 'sometimes|string',
            'options' => [
                'sometimes',
                'regex:/^(?:[^=\n]+=[^=\n]+)(?:\n[^=\n]+=[^=\n]+)*$/',
            ],
            'props' => [
                'sometimes',
                'regex:/^(?:[^=\n]+=[^=\n]+)(?:\n[^=\n]+=[^=\n]+)*$/',
            ],
            'sort' => 'sometimes|integer',
            'values' => 'sometimes|string',
        ];
        if ($this->isMethod('post')) {
            return $rules;
        }
        $rules['id'] = 'required|exists:setting,id';
        return $rules;
    }
}
