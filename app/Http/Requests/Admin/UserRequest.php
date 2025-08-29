<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:xin_user,user_id',
            'avatar_id' => 'required|integer|exists:file,file_id',
            'mobile' => 'required',
            'username' => 'required',
            'email' => 'required',
            'nickname' => 'required',
            'gender' => 'required',
            'motto' => 'required',
            'status' => 'required',
        ];
    }
}
