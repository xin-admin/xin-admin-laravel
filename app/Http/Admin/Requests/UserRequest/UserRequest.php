<?php

namespace App\Http\Admin\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'sometimes|required|integer|exists:users,id',
            'username' => 'sometimes|required|string|max:30'
        ];
    }
}