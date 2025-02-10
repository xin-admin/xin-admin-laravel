<?php

namespace App\Http\Admin\Controllers;

use APP\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserRequest;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserResetPasswordRequest;
use App\Http\BaseController;
use App\Models\AdminUserModel;
use App\Service\impl\AdminUserListService;
use Illuminate\Http\JsonResponse;

/**
 * 管理员列表控制器
 */
#[AdminController]
#[RequestMapping('/admin/list')]
class AdminUserListController extends BaseController
{
    public function __construct()
    {
        $this->model = new AdminUserModel;
        $this->service = new AdminUserListService;
        $this->searchField = ['dept_id' => '='];
        $this->quickSearchField = ['username', 'nickname', 'email', 'mobile', 'user_id'];
    }

    /** 新增管理员用户 */
    #[PostMapping] #[Authorize('admin.list.add')]
    public function add(AdminUserRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 获取管理员用户列表 */
    #[GetMapping] #[Authorize('admin.list.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 编辑管理员用户 */
    #[PutMapping] #[Authorize('admin.list.edit')]
    public function edit(AdminUserRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除管理员用户 */
    #[DeleteMapping] #[Authorize('admin.list.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }

    /** 重置管理员密码 */
    #[PostMapping('/resetPassword')] #[Authorize('admin.list.resetPassword')]
    public function resetPassword(AdminUserResetPasswordRequest $request): JsonResponse
    {
        return $this->service->resetPassword($request->validated());
    }
}
