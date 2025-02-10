<?php

namespace App\Http\Admin\Requests\AdminUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|exists:admin,id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ];
    }
}
