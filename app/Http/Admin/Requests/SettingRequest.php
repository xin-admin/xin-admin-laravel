<?php

namespace App\Http\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|exists:setting,id',
            'group_id' => 'required',
            'key' => 'required',
            'title' => 'required',
            'type' => 'required',
        ];
    }

}