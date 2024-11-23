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
use App\Service\SysAdminUserService;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin')]
class SysUserController
{

    /**
     * 权限验证白名单
     * @var array|string[]
     */
    protected array $noPermission = ['refreshToken', 'login'];

    #[Autowired]
    protected SysAdminUserService $sysAdminUserService;

    /**
     * 用户登录
     */
    #[PostMapping('/login')]
    public function login(SysUserLoginRequest $request): JsonResponse {
        $data = $request->validated();
        return $this->sysAdminUserService->login($data['username'], $data['password']);
    }

    /**
     * 刷新Token
     */
    #[PostMapping('/refreshToken')]
    public function refreshToken(): JsonResponse {
        return $this->sysAdminUserService->refreshToken();
    }

    #[PostMapping('/logout')]
    public function logout(): JsonResponse {
        return $this->sysAdminUserService->logout();
    }

    #[GetMapping]
    public function getAdminInfo(): JsonResponse {
        return $this->sysAdminUserService->getAdminInfo();
    }

    #[PutMapping]
    public function updateAdmin(SysUserUpdateInfoRequest $request): JsonResponse {
        return $this->sysAdminUserService->updateAdmin($request->validated());
    }

    #[PostMapping('/updatePassword')]
    public function updatePassword(SysUserUpdatePasswordRequest $request): JsonResponse {
        return $this->sysAdminUserService->updatePassword($request->validated());
    }
}