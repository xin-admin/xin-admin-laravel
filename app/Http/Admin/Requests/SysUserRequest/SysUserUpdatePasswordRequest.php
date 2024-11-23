<?php

namespace App\Http\Admin\Requests\SysUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class SysUserUpdatePasswordRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'oldPassword' => 'required|string|min:6|max:20',
            'newPassword' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:newPassword',
        ];
    }

    public function messages(): array
    {
        return [
            'oldPassword.required' => '请输入旧密码',
            'newPassword.required' => '请输入新密码',
            'rePassword.required' => '请再次输入新密码',
            'rePassword.same' => '两次密码不一致',
        ];
    }

}