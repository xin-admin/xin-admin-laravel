<?php

namespace App\Http\Admin\Controllers;

use APP\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\SysUserRequest\SysUserRequest;
use App\Http\Admin\Requests\SysUserRequest\SysUserResetPasswordRequest;
use App\Http\BaseController;
use App\Models\AdminUserModel;
use App\Service\AdminUserListService;
use Illuminate\Http\JsonResponse;

/**
 * 管理员列表
 */
#[AdminController]
#[RequestMapping('/admin/list')]
class AdminUserListController extends BaseController
{
    #[Autowired]
    protected AdminUserModel $model;

    #[Autowired]
    protected AdminUserListService $adminListService;

    /**
     * 添加用户
     */
    #[PostMapping]
    #[Authorize('admin.list.add')]
    public function add(SysUserRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    /**
     * 获取用户列表
     */
    #[GetMapping]
    #[Authorize('admin.list.list')]
    public function list(): JsonResponse
    {
        $searchField = ['group_id' => '=', 'created_at' => 'date'];
        $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];

        return $this->listResponse($this->model, $searchField, $quickSearchField);
    }

    /**
     * 编辑用户信息
     */
    #[PutMapping]
    #[Authorize('admin.list.edit')]
    public function edit(SysUserRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }

    /**
     * 删除用户
     */
    #[DeleteMapping]
    #[Authorize('admin.list.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse($this->model);
    }

    /**
     * 重置用户密码
     */
    #[PostMapping('/resetPassword')]
    #[Authorize('admin.list.resetPassword')]
    public function resetPassword(SysUserResetPasswordRequest $request): JsonResponse
    {
        return $this->adminListService
            ->resetPassword($request->validated());
    }
}
