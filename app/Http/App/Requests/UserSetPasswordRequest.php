<?php

namespace App\Http\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSetPasswordRequest extends FormRequest
{
    public function rule(): array
    {
        return [
            'oldPassword' => 'required|string|max:20',
            'newPassword' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:newPassword',
        ];
    }
}
