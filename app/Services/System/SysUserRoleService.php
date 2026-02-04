<?php

namespace App\Services\System;

use App\Exceptions\RepositoryException;
use App\Models\System\SysRoleModel;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SysUserRoleService extends BaseService
{
    protected SysRoleModel $model;
    protected array $quickSearchField = ['name', 'description'];
    protected array $searchField = [
        'status' => '=',
        'name' => 'like',
    ];

    protected function rules(): array
    {
        if(! $this->isUpdate()) {
            return [
                'name' => 'required|unique:sys_role,name',
                'sort' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'status' => 'required|integer|in:0,1'
            ];
        } else {
            $id = request()->route('id');
            return [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('sys_role', 'name')->ignore($id)
                ],
                'sort' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'status' => 'required|integer|in:0,1'
            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => '角色名称不能为空',
            'name.unique' => '角色名称已存在',
            'sort.required' => '排序不能为空',
            'sort.integer' => '排序必须为整数',
            'status.required' => '状态不能为空',
            'status.in' => '状态格式错误'
        ];
    }

    public function delete(int $id): bool
    {
        $model = SysRoleModel::find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        if ($model->countUser > 0) {
            throw new RepositoryException('该角色下存在用户，无法删除');
        }
        return $model->delete();
    }


    /**
     * 设置状态
     */
    public function setStatus($id): JsonResponse
    {
        $model = SysRoleModel::find($id);
        if (! $model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->status = $model->status ? 0 : 1;
        $model->save();
        return $this->success();
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
        $model = SysRoleModel::findOrFail($validated['role_id']);
        $model->rules()->sync($validated['rule_ids']);
    }

    /** 获取角色选择项 */
    public function getRoleFields(): array
    {
        return SysRoleModel::where('status', 1)
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