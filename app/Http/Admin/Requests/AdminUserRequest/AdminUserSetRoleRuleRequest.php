<?php

namespace App\Http\Admin\Requests\AdminUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserSetRoleRuleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role_id' => 'required|integer|exists:admin_role,role_id',
            'rule_keys' => 'required|array|exists:admin_rule,key',
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.required' => '请选择管理分组',
            'role_id.exists' => '管理员分组不存在',
            'rule_keys.required' => '请选择权限规则',
            'rule_keys.exists' => '权限规则不存在',
        ];
    }
}
