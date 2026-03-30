<?php

namespace App\Http\Requests\Admin;

use App\Exceptions\RepositoryException;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SysRuleFormRequest extends BaseFormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        $type = $this->input('type');
        if (empty($type)) {
            throw new RepositoryException('权限类型为必填项！');
        }
        if (!in_array($type, ['menu', 'route', 'rule'])) {
            throw new RepositoryException('权限类型错误！');
        }

        $rules = [
            'parent_id' => [
                'required',
                'integer',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('sys_rule')->where('id', $value)->exists()) {
                        $fail('选择的上级权限不存在。');
                    }
                },
            ],
            'order' => 'required|integer',
            'name' => 'required',
        ];

        if (!$this->isUpdate()) {
            $rules['key'] = 'required|unique:sys_rule,key';
        } else {
            $rules['key'] = [
                'required',
                Rule::unique('sys_rule', 'key')->ignore($this->route('id')),
            ];
        }

        if ($type == 'menu') {
            $rules += [
                'type' => 'required|string|in:menu',
                'local' => 'nullable|string',
                'icon' => 'nullable|string',
            ];
        } elseif ($type == 'route') {
            $rules += [
                'type' => 'required|string|in:route',
                'path' => 'required|string',
                'local' => 'nullable|string',
                'icon' => 'nullable|string',
                'link' => 'required|integer|numeric|in:0,1',
            ];
        } else {
            $rules += [
                'type' => 'required|string|in:rule',
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => '权限名称不能为空',
            'type.required' => '类型不能为空',
            'type.in' => '类型格式错误',
            'order.required' => '排序不能为空',
            'order.integer' => '排序必须为整数',
            'key.required' => '唯一标识不能为空',
            'key.unique' => '唯一标识已存在',
            'path.required' => '路径不能为空',
            'parent_id.required' => '父级权限不能为空',
            'parent_id.integer' => '父级权限格式错误',
        ];
    }
}
