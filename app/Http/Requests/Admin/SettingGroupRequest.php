<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|exists:setting_group,id',
            'key' => 'required',
            'title' => 'required',
            'remark' => 'sometimes|required',
        ];
    }
}
