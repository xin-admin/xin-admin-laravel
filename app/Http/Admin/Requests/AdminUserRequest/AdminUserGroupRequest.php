<?php

namespace App\Http\Admin\Requests\AdminUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserGroupRequest extends FormRequest
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
