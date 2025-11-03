<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysRuleModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SysRuleRepository extends Repository
{
    protected array $validation = [];

    protected array $messages = [
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

    public function create(array $data): SysRuleModel
    {
        $this->setRuleValidation($data);
        $data = $this->validation($data);
        if(empty($data)) {
            throw new RepositoryException('Validation failed: empty data');
        }
        return $this->model()->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $this->setRuleValidation($data, true, $id);
        $data = $this->validation($data);
        $model = $this->model()->find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        return $model->update($data);
    }

    /**
     * 设置权限规则数据验证
     * @param array $data 验证数据
     * @param bool $isUpdate 是否是新增
     * @param int|null $id 修改行ID
     */
    public function setRuleValidation(array $data, bool $isUpdate = false, int $id = null): void
    {
        if (empty($data['type'])) {
            throw new RepositoryException('权限类型为必填项！');
        }
        if (!in_array($data['type'], ['menu', 'route', 'nested-route', 'rule'])) {
            throw new RepositoryException('权限类型错误！');
        }
        $this->validation = [
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
        if ($isUpdate) {
            $this->validation['key'] = [
                'required',
                Rule::unique('sys_rule', 'key')->ignore($id)
            ];
        }
        if ($data['type'] == 'menu') {
            $this->validation += [
                'type' => 'required|string|in:menu',
                'local' => 'nullable|string',
                'icon' => 'nullable|string',
            ];
        } else if ($data['type'] == 'route') {
            $this->validation += [
                'type' => 'required|string|in:route',
                'path' => 'required|string',
                'local' => 'nullable|string',
                'icon' => 'nullable|string',
                'link' => 'required|integer|numeric|in:0,1',
                'elementPath' => 'sometimes|required|string',
            ];
        } else if ($data['type'] == 'nested-route') {
            $this->validation += [
                'type' => 'required|string|in:nested-route',
                'path' => 'required|string',
                'elementPath' => 'required|string',
            ];
        } else {
            $this->validation += [
                'type' => 'required|string|in:rule',
            ];
        }
    }
}