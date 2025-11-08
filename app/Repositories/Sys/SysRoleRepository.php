<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysRoleModel;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SysRoleRepository extends Repository
{
    /** @var array|string[] 验证规则 */
    protected array $validation = [
        'name' => 'required|unique:sys_role,name',
        'sort' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'status' => 'required|integer|in:0,1'
    ];

    /** @var array 验证消息 */
    protected array $messages = [
        'name.required' => '角色名称不能为空',
        'name.unique' => '角色名称已存在',
        'sort.required' => '排序不能为空',
        'sort.integer' => '排序必须为整数',
        'status.required' => '状态不能为空',
        'status.in' => '状态格式错误'
    ];

    /** @var array|string[] 搜索字段 */
    protected array $searchField = [
        'status' => '=',
        'name' => 'like',
    ];

    /** @var array|string[] 快速搜索字段 */
    protected array $quickSearchField = ['name', 'description'];

    /**
     * @inheritDoc
     */
    protected function model(): Builder
    {
        return SysRoleModel::query();
    }

    public function update(int $id, array $data): bool
    {
        $validator = Validator::make($data, [
            'name' => [
                'required',
                'string',
                Rule::unique('sys_role', 'name')->ignore($id)
            ],
            'sort' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|integer|in:0,1'
        ], $this->messages)->stopOnFirstFailure();
        if ($validator->fails()) {
            throw new RepositoryException(
                'Validation failed: ' . $validator->errors()->first(),
            );
        }
        $validated = $validator->validated();
        $model = $this->model()->find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        return $model->update($validated);
    }

    public function delete(int $id): bool
    {
        $model = $this->model()->find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        $count = $model->users()->count();
        if ($count > 0) {
            throw new RepositoryException('该角色下存在用户，无法删除');
        }
        return $model->delete();
    }

    public function setRule(Request $request): void
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:sys_role,id',
            'rule_ids' => 'required|array|exists:sys_rule,id',
        ]);
        if($validated['role_id'] == 1) {
            throw new RepositoryException('超级管理员不嫩修改权限');
        }
        $model = $this->model()->findOrFail($validated['role_id']);
        $model->rules()->sync($validated['rule_ids']);
    }

    /** 获取角色选择项 */
    public function getRoleFields(): array
    {
        return $this->model()
            ->where('status', 0)
            ->get(['id as role_id', 'name'])
            ->toArray();
    }

    /** 获取用户列表 */
    public function users($id): array
    {
        $model = SysRoleModel::query()->find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        $pageSize = request()->input('pageSize') ?? 10;
        return $model->users()
            ->paginate(
                $pageSize,
                ['id', 'username', 'nickname', 'email', 'mobile', 'status']
            )
            ->toArray();
    }
}