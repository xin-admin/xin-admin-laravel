<?php

namespace App\Admin\Request;

use App\Common\Models\AdminRuleModel;
use Illuminate\Foundation\Http\FormRequest;

class AdminUserRuleRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'parent_id' => ['required', 'integer', function ($attribute, $value, $fail) {
                    if ($value != 0 && ! AdminRuleModel::where('rule_id', $value)->exists()) {
                        $fail('The '.$attribute.' must exist in the database or be equal to 0.');
                    }
                }, ],
                'key' => ['required', 'string', 'max:50', 'unique:App\Common\Models\AdminRuleModel,key'],
                'name' => ['required', 'string', 'max:50'],
                'type' => ['required', 'integer', 'in:0,1,2'],
                'show' => ['required', 'integer', 'between:0,1'],
                'status' => ['required', 'integer', 'between:0,1'],
                'sort' => ['required', 'integer'],
                'path' => ['nullable', 'string', 'max:50'],
                'icon' => ['nullable', 'string', 'max:50'],
                'local' => ['nullable', 'string', 'max:50'],
            ];
        }

        return [
            'rule_id' => ['required', 'integer', 'exists:admin_rule,rule_id'],
            'parent_id' => ['required', 'integer', 'different:rule_id', function ($attribute, $value, $fail) {
                if ($value != 0 && ! AdminRuleModel::where('rule_id', $value)->exists()) {
                    $fail('The '.$attribute.' must exist in the database or be equal to 0.');
                }
            }, ],
            'key' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:50'],
            'type' => ['required', 'integer', 'in:0,1,2'],
            'show' => ['required', 'integer', 'between:0,1'],
            'status' => ['required', 'integer', 'between:0,1'],
            'sort' => ['required', 'integer'],
            'path' => ['nullable', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:50'],
            'local' => ['nullable', 'string', 'max:50'],
        ];
    }
}
