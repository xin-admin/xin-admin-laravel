<?php

namespace App\Services\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysRoleModel;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SysUserRoleService extends BaseService
{

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