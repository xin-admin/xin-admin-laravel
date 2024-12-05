<?php

namespace App\Http\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateInfoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|min:4|max:20',
            'nickname' => 'required|min:4|max:20',
            'gender' => 'required',
            'email' => 'required|email',
            'avatar_id' => 'required|integer',
            'mobile' => 'required|regex:/^1[34578]\d{9}$/',
        ];
    }
}
