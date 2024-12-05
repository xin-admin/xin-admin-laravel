<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\UserRechargeRequest;
use App\Http\Admin\Requests\UserRequest\UserRequest;
use App\Http\Admin\Requests\UserResetPasswordRequest;
use App\Http\BaseController;
use App\Models\User\UserModel;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;

/**
 * 前台用户列表
 */
#[AdminController]
#[RequestMapping('/admin/user')]
class UserListController extends BaseController
{
    #[Autowired]
    protected UserService $userService;

    #[Autowired]
    protected UserModel $model;

    // 查询字段
    protected array $searchField = ['group_id' => '=', 'created_at' => 'date'];

    // 快速搜索字段
    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];

    /**
     * 获取用户列表
     */
    #[GetMapping]
    #[Authorize('user.list.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse($this->model);
    }

    /**
     * 编辑用户信息
     */
    #[PutMapping]
    #[Authorize('user.list.edit')]
    public function edit(UserRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }

    /**
     * 充值
     */
    #[Authorize('admin.user.recharge')]
    #[PostMapping('/recharge')]
    public function recharge(UserRechargeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->userService->recharge($data['user_id'], $data['amount'], $data['mode'], $data['remark']);

        return $this->success('ok');
    }

    /**
     * 重置密码
     */
    #[Authorize('admin.user.resetPassword')]
    #[PostMapping('/resetPassword')]
    public function resetPassword(UserResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->userService->resetPassword($data['user_id'], $data['password']);

        return $this->success('ok');
    }
}
