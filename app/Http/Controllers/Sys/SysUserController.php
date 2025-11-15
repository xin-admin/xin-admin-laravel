<?php

namespace App\Http\Controllers\Sys;

use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Services\Sys\SysLoginRecordService;
use App\Services\Sys\SysUserService;
use App\Services\SysFileService;
use App\Support\Enum\FileType;
use App\Support\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 管理员用户控制器
 */
#[RequestMapping('/admin')]
class SysUserController
{
    use RequestJson;

    protected array $noPermission = ['refreshToken', 'login'];

    /** 用户登录 */
    #[PostMapping('/login', middleware: 'login_log')]
    public function login(Request $request, SysUserService $service): JsonResponse
    {
        return $service->login($request);
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
    public function info(SysUserService $service): JsonResponse
    {
        $info = auth()->user();
        $id = auth()->id();
        $access = $service->ruleKeys($id);
        $menus = $service->getAdminMenus($id);
        return $this->success(compact('access', 'menus', 'info'));
    }

    /** 更新管理员信息 */
    #[PutMapping]
    public function updateInfo(Request $request, SysUserService $service): JsonResponse
    {
        return $service->updateInfo(Auth::id(), $request);
    }

    /** 修改密码 */
    #[PutMapping('/updatePassword')]
    public function updatePassword(Request $request, SysUserService $service): JsonResponse
    {
        return $service->updatePassword($request);
    }

    /** 上传头像 */
    #[PostMapping('/avatar')]
    public function uploadAvatar(): JsonResponse
    {
        $service = new SysFileService();
        $data = $service->upload(FileType::IMAGE, 1, 'public');
        return $this->success($data);
    }

    /** 获取管理员登录日志 */
    #[GetMapping('/login/record')]
    public function get(SysLoginRecordService $service): JsonResponse
    {
        $id = auth()->id();
        $data = $service->getRecordByID($id);
        return $this->success($data);
    }
}
