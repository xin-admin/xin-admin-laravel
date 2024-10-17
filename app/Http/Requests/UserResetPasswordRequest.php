<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:user,id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => '请选择用户ID',
            'user_id.exists' => '用户不存在',
            'password.required' => '请输入密码',
            'password.min' => '密码最少为6位',
        ];
    }
}
