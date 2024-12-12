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
use App\Http\Admin\Requests\SysUserRequest\SysUserSetGroupRuleRequest;
use App\Http\Admin\Requests\UserRequest\UserGroupRequest;
use App\Http\BaseController;
use App\Models\User\UserGroupModel;
use Illuminate\Http\JsonResponse;

/**
 * 前台用户
 */
#[AdminController]
#[RequestMapping('/user/group')]
class UserGroupController extends BaseController
{
    #[Autowired]
    protected UserGroupModel $model;

    /**
     * 获取用户权限列表
     */
    #[GetMapping]
    #[Authorize('user.group.list')]
    public function list(): JsonResponse
    {
        $data = $this->model->query()->get()->toArray();
        $data = getTreeData($data);

        return $this->success(compact('data'));
    }

    /**
     * 添加用户权限
     */
    #[PostMapping]
    #[Authorize('user.group.add')]
    public function add(UserGroupRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    /**
     * 编辑用户权限
     */
    #[PutMapping]
    #[Authorize('user.group.edit')]
    public function edit(UserGroupRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }

    /**
     * 删除用户权限
     */
    #[DeleteMapping]
    #[Authorize('user.group.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse($this->model);
    }

    /**
     * 设置分组权限
     */
    #[PostMapping('/setGroupRule')]
    #[Authorize('user.group.edit')]
    public function setGroupRule(SysUserSetGroupRuleRequest $request): JsonResponse
    {
        $group = $this->model::query()->find($request->validated('id'));
        $group->rules = implode(',', $request->validated('rule_ids'));
        $group->save();

        return $this->success();
    }
}
