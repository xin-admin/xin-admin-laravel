<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserLoginRequest;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserUpdateInfoRequest;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserUpdatePasswordRequest;
use App\Http\BaseController;
use App\Service\AdminUserService;
use Illuminate\Http\JsonResponse;

/**
 * 管理员用户控制器
 */
#[AdminController]
#[RequestMapping('/admin')]
class AdminUserController extends BaseController
{
    public function __construct()
    {
        $this->service = new AdminUserService;
        $this->noPermission = ['refreshToken', 'login'];
    }

    /** 会员登录 */
    #[PostMapping('/login')]
    public function login(AdminUserLoginRequest $request): JsonResponse
    {
        return $this->service->login($request);
    }

    /** 刷新 Token */
    #[PostMapping('/refreshToken')]
    public function refreshToken(): JsonResponse
    {
        return $this->service->refreshToken();
    }

    /** 退出登录 */
    #[PostMapping('/logout')]
    public function logout(): JsonResponse
    {
        return $this->service->logout();
    }

    /** 获取管理员信息 */
    #[GetMapping('/info')]
    public function getAdminInfo(): JsonResponse
    {
        return $this->service->getAdminInfo();
    }

    /** 更新管理员信息 */
    #[PutMapping]
    public function updateAdmin(AdminUserUpdateInfoRequest $request): JsonResponse
    {
        return $this->service->updateAdmin($request->validated());
    }

    /** 修改密码 */
    #[PostMapping('/updatePassword')]
    public function updatePassword(AdminUserUpdatePasswordRequest $request): JsonResponse
    {
        return $this->service->updatePassword($request->validated());
    }
}
