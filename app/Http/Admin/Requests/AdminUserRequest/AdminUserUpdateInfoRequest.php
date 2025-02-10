<?php

namespace App\Http\Admin\Requests\AdminUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserUpdateInfoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required',
            'username' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'avatar_id' => 'required|exists:file,file_id',
            'group_id' => 'required|exists:admin_group,id',
            'nickname' => 'required',
            'sex' => 'required',
            'status' => 'required|in:1,0',
        ];
    }
}
