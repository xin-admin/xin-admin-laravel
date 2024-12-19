<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
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
 * 角色管理
 */
#[AdminController]
#[RequestMapping('/admin/role')]
class AdminUserRoleController extends BaseController
{
    #[Autowired]
    protected AdminRoleModel $model;

    // 查询字段
    protected array $searchField = [
        'id' => '=',
        'name' => 'like',
        'pid' => '=',
        'create_time' => 'date',
        'update_time' => 'date',
    ];

    /**
     * 角色列表
     */
    #[GetMapping]
    #[Authorize('admin.group.list')]
    public function list(): JsonResponse
    {
        $data = $this->model->query()->get()->toArray();
        $data = getTreeData($data);
        return $this->success(compact('data'));
    }

    /**
     * 添加角色
     */
    #[PostMapping]
    #[Authorize('admin.group.add')]
    public function add(AdminUserGroupRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    /**
     * 编辑角色
     */
    #[PutMapping]
    #[Authorize('admin.group.edit')]
    public function edit(AdminUserRuleRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }

    /**
     * 删除角色
     */
    #[DeleteMapping]
    #[Authorize('admin.group.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse($this->model);
    }

    /**
     * 设置角色权限
     */
    #[PostMapping('/setGroupRule')]
    #[Authorize('admin.group.edit')]
    public function setGroupRule(AdminUserSetGroupRuleRequest $request): JsonResponse
    {
        $group = $this->model::query()->find($request->validated('id'));
        $group->rules = implode(',', $request->validated('rule_ids'));
        $group->save();

        return $this->success();
    }
}
