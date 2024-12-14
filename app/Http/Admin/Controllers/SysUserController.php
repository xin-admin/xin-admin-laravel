<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\SysUserRequest\SysUserLoginRequest;
use App\Http\Admin\Requests\SysUserRequest\SysUserUpdateInfoRequest;
use App\Http\Admin\Requests\SysUserRequest\SysUserUpdatePasswordRequest;
use App\Http\BaseController;
use App\Service\AdminUserService;
use Illuminate\Http\JsonResponse;

/**
 * 管理员用户控制器
 */
#[AdminController]
#[RequestMapping('/admin')]
class SysUserController extends BaseController
{
    /**
     * 权限验证白名单
     * @var array|string[]
     */
    protected array $noPermission = ['refreshToken', 'login'];

    #[Autowired]
    protected AdminUserService $sysAdminUserService;

    /**
     * 用户登录
     */
    #[PostMapping('/login')]
    public function login(SysUserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        return $this->sysAdminUserService->login($data['username'], $data['password']);
    }

    /**
     * 刷新Token
     */
    #[PostMapping('/refreshToken')]
    public function refreshToken(): JsonResponse
    {
        return $this->sysAdminUserService->refreshToken();
    }

    /**
     * 退出登录
     */
    #[PostMapping('/logout')]
    public function logout(): JsonResponse
    {
        return $this->sysAdminUserService->logout();
    }

    /**
     * 获取用户信息
     */
    #[GetMapping('/info')]
    public function getAdminInfo(): JsonResponse
    {
        return $this->sysAdminUserService->getAdminInfo();
    }

    /**
     * 更新用户信息
     */
    #[PutMapping]
    public function updateAdmin(SysUserUpdateInfoRequest $request): JsonResponse
    {
        return $this->sysAdminUserService->updateAdmin($request->validated());
    }

    /**
     * 更新用户密码
     */
    #[PostMapping('/updatePassword')]
    public function updatePassword(SysUserUpdatePasswordRequest $request): JsonResponse
    {
        return $this->sysAdminUserService->updatePassword($request->validated());
    }
}
