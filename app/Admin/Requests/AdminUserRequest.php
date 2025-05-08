<?php

namespace App\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'username' => 'required',
                'nickname' => 'required',
                'sex' => 'required',
                'mobile' => 'required',
                'email' => 'required|email',
                'avatar_id' => 'required|exists:file,file_id',
                'role_id' => 'required|exists:admin_role,role_id',
                'dept_id' => 'required|exists:admin_dept,dept_id',
                'status' => 'required|in:1,0',
                'password' => 'required',
                'rePassword' => 'required|same:password',
            ];
        }else {
            return [
                'user_id' => 'required|exists:admin_user,user_id|integer',
                'username' => 'required',
                'mobile' => 'required',
                'email' => 'required|email',
                'avatar_id' => 'required|exists:file,file_id',
                'role_id' => 'required|exists:admin_role,role_id',
                'dept_id' => 'required|exists:admin_dept,dept_id',
                'nickname' => 'required',
                'sex' => 'required',
                'status' => 'required|in:1,0',
            ];
        }

    }
}
