<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\AdminRequest\AdminUserRoleRequest;
use App\Http\BaseController;
use App\Models\AdminRoleModel;
use App\Models\AdminRuleModel;
use App\Models\AdminUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    /** 获取角色详情 */
    #[GetMapping('/{id}')] #[Authorize('admin.role.get')]
    public function get($id): JsonResponse
    {
        return $this->getResponse($id);
    }

    /** 获取角色列表 */
    #[GetMapping] #[Authorize('admin.role.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 添加角色 */
    #[PostMapping] #[Authorize('admin.role.add')]
    public function add(AdminUserRoleRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 编辑角色 */
    #[PutMapping] #[Authorize('admin.role.edit')]
    public function edit(AdminUserRoleRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除角色 */
    #[DeleteMapping] #[Authorize('admin.role.delete')]
    public function delete(Request $request): JsonResponse
    {
        $user = AdminUserModel::whereIn('role_id', $request->all('role_ids'))->first();
        if (! $user) {
            return $this->error('该角色下存在用户，无法删除');
        }

        return $this->deleteResponse();
    }

    /** 设置角色权限 */
    #[PostMapping('/rule')] #[Authorize('admin.role.edit')]
    public function setRoleRule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:admin_role,role_id',
            'rule_keys' => 'required|array|exists:admin_rule,key',
        ]);
        $rule_ids = AdminRuleModel::whereIn('key', $validated)->pluck('rule_id')->toArray();
        $this->model
            ->where('role_id', $validated('role_id'))
            ->update([
                'rules' => implode(',', $rule_ids),
            ]);

        return $this->success();
    }
}
