<?php

namespace App\Http\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
            'rePassword' => 'required|min:4|same:password',
            'email' => 'required|email',
            'mobile' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => '请填写用户名。',
            'username.min' => '账号至少需要 :min 个字符。',
            'password.required' => '密码不能为空。',
            'password.min' => '密码至少需要 :min 个字符。',
            'rePassword.required' => '请重复输入密码',
            'email.required' => '请输入邮箱',
            'email.email' => '邮箱格式不正确',
            'mobile.required' => '请输入手机号'
        ];
    }

}