<?php

namespace App\Http\Admin\Requests\SysUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class SysUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|exists:admin,id|integer',
            'username' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'avatar_id' => 'required|exists:file,file_id',
            'group_id' => 'required|exists:admin_group,id',
            'nickname' => 'required',
            'sex' => 'required',
            'status' => 'required|in:1,0',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => '用户名不能为空',
            'mobile.required' => '手机号不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'avatar_id.required' => '头像不能为空',
            'avatar_id.exists' => '头像不存在',
            'group_id.required' => '用户组不能为空',
            'group_id.exists' => '用户组不存在',
        ];
    }
}
