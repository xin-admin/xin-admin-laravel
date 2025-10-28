<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysDeptModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SysDeptRepository extends Repository
{
    /** @var array 验证消息 */
    protected array $messages = [
        'name.required' => '部门名称不能为空',
        'name.unique' => '部门名称已存在',
        'code.required' => '部门编码不能为空',
        'code.unique' => '部门编码已存在',
        'type.required' => '部门类型不能为空',
        'type.integer' => '部门类型必须是整数',
        'type.in' => '部门类型错误',
        'parent_id.required' => '上级部门不能为空',
        'parent_id.integer' => '上级部门ID必须是整数',
        'parent_id.exists' => '选择的上级部门不存在',
        'sort.required' => '排序字段不能为空',
        'sort.integer' => '排序字段必须是整数',
        'email.email' => '请输入有效的邮箱地址',
        'status.required' => '状态不能为空',
        'status.in' => '状态类型错误',
    ];

    /** @var array|string[] 搜索字段 */
    protected array $searchField = [
        'parent_id' => '=',
        'status' => '='
    ];

    /** @var array|string[] 快速搜索字段 */
    protected array $quickSearchField = ['name', 'leader', 'phone'];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysDeptModel::query();
    }

    public function create(array $data): Model
    {
        $validator = Validator::make($data, [
            'name' => 'required|unique:sys_dept,name',
            'code' => 'required|unique:sys_dept,code',
            'type' => 'required|integer|in:0,1,2',
            'parent_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !DB::table('sys_dept')->where('id', $value)->exists()) {
                        $fail('选择的上级部门不存在。');
                    }
                },
            ],
            'sort' => 'required|integer',
            'phone' => 'nullable',
            'address' => 'nullable',
            'email' => 'nullable|email',
            'status' => 'required|in:0,1',
            'remark' => 'nullable',
        ], $this->messages)->stopOnFirstFailure();
        if ($validator->fails()) {
            throw new RepositoryException(
                'Validation failed: ' . $validator->errors()->first(),
            );
        }
        return $this->model()->create($validator->validated());
    }

    public function update(int $id, array $data): bool
    {
        $validator = Validator::make($data, [
            'name' => [
                'required',
                Rule::unique('sys_dept', 'name')->ignore($id)
            ],
            'code' => [
                'required',
                Rule::unique('sys_dept', 'code')->ignore($id)
            ],
            'type' => 'required|integer|in:0,1,2',
            'sort' => 'required|integer',
            'phone' => 'nullable',
            'address' => 'nullable',
            'email' => 'nullable|email',
            'status' => 'required|in:0,1',
            'remark' => 'nullable',
        ], $this->messages)->stopOnFirstFailure();
        if ($validator->fails()) {
            throw new RepositoryException(
                'Validation failed: ' . $validator->errors()->first(),
            );
        }
        $model = $this->model()->find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        return $model->update($validator->validated());
    }

    /** 获取部门选择项 */
    public function getDeptField(): array
    {
        $field = $this->model()
            ->where('status', 0)
            ->select(['id as dept_id', 'name', 'parent_id'])
            ->get()
            ->toArray();
        return $this->buildTree($field);
    }

    /** 构建树形结构 */
    private function buildTree(array $items, $parentId = 0): array
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildTree($items, $item['dept_id']);
                $node = [
                    'dept_id' => $item['dept_id'],
                    'name' => $item['name'],
                    'children' => $children
                ];
                $tree[] = $node;
            }
        }
        return $tree;
    }
}