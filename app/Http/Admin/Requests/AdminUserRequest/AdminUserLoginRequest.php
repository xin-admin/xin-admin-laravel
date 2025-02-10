<?php
namespace App\Http\Admin\Requests\AdminUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserLoginRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => '请填写您的姓名。',
            'username.min' => '账号至少需要 :min 个字符。',
            'password.required' => '密码不能为空。',
            'password.min' => '密码至少需要 :min 个字符。',
        ];
    }
}
