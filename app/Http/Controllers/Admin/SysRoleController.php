<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\RepositoryException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SysRoleFormRequest;
use App\Models\System\SysRoleModel;
use App\Models\System\SysRuleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\DeleteRoute;
use Xin\AnnoRoute\Route\GetRoute;
use Xin\AnnoRoute\Route\PostRoute;
use Xin\AnnoRoute\Route\PutRoute;

/**
 * 角色管理控制器
 */
#[RequestAttribute('/system/role', 'system.role')]
class SysRoleController extends BaseController
{
    protected array $quickSearchField = ['name', 'description'];
    protected array $searchField = [
        'status' => '=',
        'name' => 'like',
    ];

    /** 查询角色列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $pageSize = $params['pageSize'] ?? 10;
        $query = SysRoleModel::query();
        $data = $this->buildSearch($params, $query)
            ->paginate($pageSize)
            ->toArray();
        return $this->success($data);
    }

    /** 创建角色 */
    #[PostRoute(authorize: 'create')]
    public function create(SysRoleFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysRoleModel::create($validated);
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 编辑角色 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysRoleFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysRoleModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($validated);
        return $this->success();
    }

    /** 删除角色 */
    #[DeleteRoute(
        route: '/{id}',
        authorize: 'delete',
        where: ['id' => '[0-9]+']
    )]
    public function delete(int $id): JsonResponse
    {
        $model = SysRoleModel::find($id);
        if (empty($model)) {
            throw new RepositoryException('角色不存在');
        }
        if ($model->countUser > 0) {
            throw new RepositoryException('该角色下存在用户，无法删除');
        }
        $model->delete();
        return $this->success();
    }

    /** 获取角色用户列表 */
    #[GetRoute('/users/{id}', 'users')]
    public function users(int $id): JsonResponse
    {
        $model = SysRoleModel::query()->find($id);
        if (empty($model)) {
            throw new RepositoryException('角色不存在');
        }
        $pageSize = request()->input('pageSize', 10);
        $data = $model->users()
            ->paginate($pageSize, ['id', 'username', 'nickname', 'email', 'mobile', 'status'])
            ->toArray();
        return $this->success($data);
    }

    /** 设置启用状态 */
    #[PutRoute('/status/{id}', 'status')]
    public function status(int $id): JsonResponse
    {
        $model = SysRoleModel::find($id);
        if (!$model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->status = $model->status ? 0 : 1;
        $model->save();
        return $this->success();
    }

    /** 获取权限选项 */
    #[GetRoute('/ruleList', 'ruleList')]
    public function ruleList(): JsonResponse
    {
        $data = SysRuleModel::query()
            ->where("status", 1)
            ->get(['name as title', 'parent_id', 'id as key', 'id', 'local'])
            ->toArray();
        $data = getTreeData($data);
        return $this->success($data);
    }

    /** 设置角色权限 */
    #[PostRoute('/setRule', 'setRule')]
    public function setRule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:sys_role,id',
            'rule_ids' => 'required|array|exists:sys_rule,id',
        ]);
        if ($validated['role_id'] == 1) {
            throw new RepositoryException('超级管理员不能修改权限');
        }
        $model = SysRoleModel::findOrFail($validated['role_id']);
        $model->rules()->sync($validated['rule_ids']);
        return $this->success();
    }
}
