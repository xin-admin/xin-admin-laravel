<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Autowired;
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

#[AdminController]
#[RequestMapping('/admin')]
class AdminUserController extends BaseController
{
    /**
     * 权限验证白名单
     */
    protected array $noPermission = ['refreshToken', 'login'];

    #[Autowired]
    protected AdminUserService $adminUserService;

    #[PostMapping('/login')]
    public function login(AdminUserLoginRequest $request): JsonResponse
    {
        return $this->adminUserService->login($request);
    }

    #[PostMapping('/refreshToken')]
    public function refreshToken(): JsonResponse
    {
        return $this->adminUserService->refreshToken();
    }

    #[PostMapping('/logout')]
    public function logout(): JsonResponse
    {
        return $this->adminUserService->logout();
    }

    #[GetMapping('/info')]
    public function getAdminInfo(): JsonResponse
    {
        return $this->adminUserService->getAdminInfo();
    }

    #[PutMapping]
    public function updateAdmin(AdminUserUpdateInfoRequest $request): JsonResponse
    {
        return $this->adminUserService->updateAdmin($request->validated());
    }

    #[PostMapping('/updatePassword')]
    public function updatePassword(AdminUserUpdatePasswordRequest $request): JsonResponse
    {
        return $this->adminUserService->updatePassword($request->validated());
    }
}
