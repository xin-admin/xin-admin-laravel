<?php

namespace App\App\Requests;

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
}
