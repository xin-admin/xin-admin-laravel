<?php

namespace App\Http\Admin\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class UserGroupRequest extends FormRequest
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
