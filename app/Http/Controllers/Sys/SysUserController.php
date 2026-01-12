<?php

namespace App\Http\Controllers\Sys;

use App\Providers\AnnoRoute\RequestAttribute;
use App\Providers\AnnoRoute\Route\GetRoute;
use App\Providers\AnnoRoute\Route\PostRoute;
use App\Providers\AnnoRoute\Route\PutRoute;
use App\Services\Sys\SysLoginRecordService;
use App\Services\Sys\SysUserService;
use App\Support\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 管理员用户控制器
 */
#[RequestAttribute('/admin')]
class SysUserController
{
    use RequestJson;

    /** 用户登录 */
    #[PostRoute('/login', middleware: 'login_log')]
    public function login(Request $request, SysUserService $service): JsonResponse
    {
        return $service->login($request);
    }

    /** 退出登录 */
    #[PostRoute('/logout')]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(__('user.logout_success'));
    }

    /** 获取管理员信息 */
    #[GetRoute('/info')]
    public function info(SysUserService $service): JsonResponse
    {
        $info = Auth::user();
        $id = Auth::id();
        $access = $service->ruleKeys($id);
        $menus = $service->getAdminMenus($id);
        return $this->success(compact('access', 'menus', 'info'));
    }

    /** 更新管理员信息 */
    #[PutRoute]
    public function updateInfo(Request $request, SysUserService $service): JsonResponse
    {
        return $service->updateInfo(Auth::id(), $request);
    }

    /** 修改密码 */
    #[PutRoute('/updatePassword')]
    public function updatePassword(Request $request, SysUserService $service): JsonResponse
    {
        return $service->updatePassword($request);
    }

    /** 上传头像 */
    #[PostRoute('/avatar')]
    public function uploadAvatar(SysUserService $service): JsonResponse
    {
        return $service->uploadAvatar();
    }

    /** 获取管理员登录日志 */
    #[GetRoute('/login/record')]
    public function get(SysLoginRecordService $service): JsonResponse
    {
        $id = Auth::id();
        $data = $service->getRecordByID($id);
        return $this->success($data);
    }
}
