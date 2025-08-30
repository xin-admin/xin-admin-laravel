<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\SysUserRuleRepository;
use App\Services\FileService;
use App\Services\SysUserService;
use App\Support\Enum\FileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 系统用户控制器
 */
#[RequestMapping('/sys/user')]
class SysUserController extends BaseController
{
    protected array $noPermission = ['refreshToken', 'login'];

    protected SysUserService $service;
    protected SysUserRuleRepository $repository;

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
        Auth::guard('admin')->logout();
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
    public function update(Request $request): JsonResponse
    {
        $this->repository->update(Auth::id(), $request->toArray());
        return $this->success();
    }

    /** 修改密码 */
    #[PutMapping('/updatePassword')]
    public function updatePassword(Request $request): JsonResponse
    {
        return $this->service->updatePassword($request);
    }

    /** 上传头像 */
    #[PostMapping('/uploadAvatar')]
    public function avatar(): JsonResponse
    {
        $service = new FileService();
        $data = $service->upload(FileType::IMAGE, 1, 'public');
        return $this->success($data);
    }
}