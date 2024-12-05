<?php

namespace App\Http\Admin\Requests\SysUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class SysUserRuleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|integer|exists:admin_rule,id',
            'pid' => 'required|integer',
            'name' => 'required|string|max:50',
            'type' => 'required|integer|in:0,1,2',
            'key' => 'required|string|max:50',
            'show' => 'required|integer|between:0,1',
            'status' => 'required|integer|between:0,1',
            'sort' => 'required|integer',
            'path' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:255',
            'locale' => 'nullable|string|max:255',
        ];
    }
}
