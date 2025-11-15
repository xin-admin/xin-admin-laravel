<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysRuleModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SysRuleRepository extends BaseRepository
{

    protected array $searchField = [
        'type' => '=',
        'status' => '=',
        'show' => '=',
        'parent_id' => '='
    ];

    protected array $quickSearchField = ['name', 'key', 'path'];

    protected function model(): Builder
    {
        return SysRuleModel::query();
    }

    protected function rules(): array
    {
        $type = request()->input('type');
        if (empty($type)) {
            throw new RepositoryException('权限类型为必填项！');
        }
        if (!in_array($type, ['menu', 'route', 'nested-route', 'rule'])) {
            throw new RepositoryException('权限类型错误！');
        }
        $rules = [
            'parent_id' => [
                'required',
                'integer',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('sys_rule')->where('id', $value)->exists()) {
                        $fail('选择的上级部门不存在。');
                    }
                },
            ],
            'order' => 'required|integer',
            'name' => 'required',
            'key' => 'required|unique:sys_rule,key'
        ];
        if ($this->isUpdate()) {
            $rules['key'] = [
                'required',
                Rule::unique('sys_rule', 'key')->ignore(request()->route('id'))
            ];
        }
        if ($type == 'menu') {
            $rules += [
                'type' => 'required|string|in:menu',
                'local' => 'nullable|string',
                'icon' => 'nullable|string',
            ];
        } else if ($type == 'route') {
            $rules += [
                'type' => 'required|string|in:route',
                'path' => 'required|string',
                'local' => 'nullable|string',
                'icon' => 'nullable|string',
                'link' => 'required|integer|numeric|in:0,1',
                'elementPath' => 'sometimes|required|string',
            ];
        } else if ($type == 'nested-route') {
            $rules += [
                'type' => 'required|string|in:nested-route',
                'path' => 'required|string',
                'elementPath' => 'required|string',
            ];
        } else {
            $rules += [
                'type' => 'required|string|in:rule',
            ];
        }
        return $rules;
    }

    protected function messages(): array
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
            'status.required' => '状态不能为空',
            'status.in' => '状态格式错误',
            'show.required' => '显示状态不能为空',
            'show.in' => '显示状态格式错误',
            'parent_id.required' => '父级权限不能为空',
            'parent_id.integer' => '父级权限格式错误',
            'parent_id.exists' => '父级权限不存在'
        ];
    }
}