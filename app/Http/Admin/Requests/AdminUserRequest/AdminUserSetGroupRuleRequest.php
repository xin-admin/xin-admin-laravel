<?php

namespace App\Http\Admin\Requests\AdminUserRequest;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserSetGroupRuleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:admin_group,id',
            'rules' => 'required|array|exists:admin_rule,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => '请选择管理分组',
            'id.exists' => '管理员分组不存在',
            'rules.required' => '请选择权限规则',
            'rules.exists' => '权限规则不存在',
        ];
    }
}
