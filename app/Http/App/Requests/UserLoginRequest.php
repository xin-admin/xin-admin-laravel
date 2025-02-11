<?php

namespace App\Http\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ];
    }
}
