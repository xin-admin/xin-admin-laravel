<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class SysUserFormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        if(!$this->isUpdate()) {
            return [
                'username' => 'required|unique:sys_user,username',
                'nickname' => 'required',
                'sex' => 'in:0,1',
                'mobile' => 'required',
                'email' => 'required|email|unique:sys_user,email',
                'dept_id' => 'required|exists:sys_dept,id',
                'role_id' => 'required|array|exists:sys_role,id',
                'status' => 'required|int|in:1,0',
                'password' => 'required|min:6',
                'rePassword' => 'required|same:password',
            ];
        } else {
            $id = $this->route('id');
            return [
                'username' => [
                    'required',
                    Rule::unique('sys_user', 'username')->ignore($id)
                ],
                'nickname' => 'required',
                'sex' => 'in:0,1',
                'mobile' => 'required',
                'email' => [
                    'required',
                    Rule::unique('sys_user', 'email')->ignore($id)
                ],
                'role_id' => 'required|array|exists:sys_role,id',
                'dept_id' => 'required|exists:sys_dept,id',
                'status' => 'required|int|in:1,0',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'username.required' => '用户名不能为空',
            'username.unique' => '用户名已存在',
            'nickname.required' => '昵称不能为空',
            'sex.in' => '性别格式错误',
            'mobile.required' => '手机号不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式错误',
            'email.unique' => '邮箱已存在',
            'dept_id.exists' => '部门不存在',
            'role_id.exists' => '角色不存在',
            'status.required' => '状态不能为空',
            'status.int' => '状态值错误',
            'status.in' => '状态格式错误',
            'password.required' => '密码不能为空',
            'password.min' => '密码至少6位',
            'rePassword.required' => '确认密码不能为空',
            'rePassword.same' => '两次密码不一致',
        ];
    }
}
