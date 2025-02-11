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
}
