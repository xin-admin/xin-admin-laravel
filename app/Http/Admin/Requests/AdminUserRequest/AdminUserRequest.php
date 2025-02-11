<?php

namespace App\Http\Admin\Requests\AdminUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|required|exists:admin_user,user_id|integer',
            'username' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'avatar_id' => 'required|exists:file,file_id',
            'role_id' => 'required|exists:App\Models\AdminRoleModel,role_id',
            'dept_id' => 'required|exists:App\Models\AdminDeptModel,dept_id',
            'nickname' => 'required',
            'sex' => 'required',
            'status' => 'required|in:1,0',
        ];
    }
}
