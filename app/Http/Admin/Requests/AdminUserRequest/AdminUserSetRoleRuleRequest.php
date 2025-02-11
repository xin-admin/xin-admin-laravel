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
}
