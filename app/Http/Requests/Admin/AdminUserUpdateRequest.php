<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nickname' => [
                'required',
                Rule::unique('sys_user', 'username')->ignore($id)
            ],
            'sex' => 'required|in:0,1',
            'bio' => 'sometimes|max:255',
            'mobile' => [
                'required',
                Rule::unique('sys_user', 'mobile')->ignore($id)
            ],
            'email' => [
                'required',
                Rule::unique('sys_user', 'email')->ignore($id)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nickname.required' => '昵称不能为空',
            'sex.required' => '性别不能为空',
            'sex.in' => '性别格式错误',
            'bio.max' => '个人简介最大不能超过255个字符',
            'mobile.required' => '手机号不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式错误'
        ];
    }
}
