<?php

namespace App\Http\Admin\Requests\SysUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class SysUserGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|integer|exists:admin_group,id',
            'name' => 'required|string',
            'pid' => 'required|integer',
        ];
    }
}
