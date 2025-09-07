<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Repositories\Sys\SysUserRepository;
use App\Services\SysFileService;
use App\Services\SysUserService;
use App\Support\Enum\FileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 管理员用户控制器
 */
#[RequestMapping('/admin')]
class SysUserController extends BaseController
{
    protected array $noPermission = ['refreshToken', 'login'];

    public function __construct(SysUserService $service, SysUserRepository $repository) {
        $this->service = $service;
        $this->repository = $repository;
    }

    /** 用户登录 */
    #[PostMapping('/login', middleware: 'login_log')]
    public function login(Request $request): JsonResponse
    {
        return $this->service->login($request);
    }

    /** 退出登录 */
    #[PostMapping('/logout')]
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('sys_users')->logout();
        $request->user()->currentAccessToken()->delete();
        return $this->success(__('user.logout_success'));
    }

    /** 获取管理员信息 */
    #[GetMapping('/info')]
    public function info(): JsonResponse
    {
        return $this->service->getAdminInfo();
    }

    /** 更新管理员信息 */
    #[PutMapping]
    public function updateInfo(Request $request): JsonResponse
    {
        return $this->service->updateInfo(Auth::id(), $request);
    }

    /** 修改密码 */
    #[PutMapping('/updatePassword')]
    public function updatePassword(Request $request): JsonResponse
    {
        return $this->service->updatePassword($request);
    }

    /** 上传头像 */
    #[PostMapping('/avatar')]
    public function uploadAvatar(): JsonResponse
    {
        $service = new SysFileService();
        $data = $service->upload(FileType::IMAGE, 1, 'public');
        return $this->success($data);
    }
}
