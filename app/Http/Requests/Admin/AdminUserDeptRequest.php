<?php

namespace App\Http\Requests\Admin;

use App\Models\AdminDeptModel;
use Illuminate\Foundation\Http\FormRequest;

class AdminUserDeptRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'parent_id' => ['required', 'integer', 'different:rule_id', function ($attribute, $value, $fail) {
                    if ($value != 0 && ! AdminDeptModel::where('dept_id', $value)->exists()) {
                        $fail('The '.$attribute.' must exist in the database or be equal to 0.');
                    }
                }, ],
                'name' => ['required', 'string', 'max:50'],
                'sort' => ['required', 'integer'],
                'leader' => ['required', 'string', 'max:50'],
                'phone' => ['required', 'string', 'max:50'],
                'email' => ['required', 'email', 'max:50'],
                'status' => ['required', 'integer', 'between:0,1'],
            ];
        }

        return [
            'dept_id' => ['required', 'integer', 'exists:admin_dept,dept_id'],
            'parent_id' => ['required', 'integer', 'different:rule_id', function ($attribute, $value, $fail) {
                if ($value != 0 && ! AdminDeptModel::where('dept_id', $value)->exists()) {
                    $fail('The '.$attribute.' must exist in the database or be equal to 0.');
                }
            }, ],
            'name' => ['required', 'string', 'max:50'],
            'sort' => ['required', 'integer'],
            'leader' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50'],
            'status' => ['required', 'integer', 'between:0,1'],
        ];
    }
}
