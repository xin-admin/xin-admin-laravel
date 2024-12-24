<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserGroupRequest;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserRuleRequest;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserSetGroupRuleRequest;
use App\Http\BaseController;
use App\Models\AdminRoleModel;
use Illuminate\Http\JsonResponse;

/**
 * 角色管理控制器
 */
#[AdminController]
#[RequestMapping('/admin/role')]
class AdminUserRoleController extends BaseController
{
    public function __construct()
    {
        $this->model = new AdminRoleModel;
    }

    /** 获取角色列表 */
    #[GetMapping] #[Authorize('admin.role.list')]
    public function list(): JsonResponse
    {
        $data = $this->model->get([
            'role_id',
            'name',
            'sort',
            'created_at',
            'updated_at',
        ])->toArray();

        return $this->success(compact('data'));
    }

    /** 添加角色 */
    #[PostMapping] #[Authorize('admin.role.add')]
    public function add(AdminUserGroupRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 编辑角色 */
    #[PutMapping] #[Authorize('admin.role.edit')]
    public function edit(AdminUserRuleRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除角色 */
    #[DeleteMapping] #[Authorize('admin.role.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }

    /** 设置角色权限 */
    #[PostMapping('/rule')] #[Authorize('admin.role.edit')]
    public function setRoleRule(AdminUserSetGroupRuleRequest $request): JsonResponse
    {
        $group = $this->model::query()->find($request->validated('id'));
        $group->rules = implode(',', $request->validated('rule_ids'));
        $group->save();

        return $this->success();
    }
}
