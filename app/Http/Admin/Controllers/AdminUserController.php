<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Enum\FileType;
use App\Http\BaseController;
use App\Service\AdminUserService;
use App\Service\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 管理员用户控制器
 */
#[RequestMapping('/admin')]
class AdminUserController extends BaseController
{
    protected array $noPermission = ['refreshToken', 'login'];

    public function __construct()
    {
        $this->service = new AdminUserService;
    }

    /** 用户登录 */
    #[PostMapping('/login', 'login_log')]
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ]);

        if (Auth::attempt($credentials)) {
            $access = auth()->user()['rules'];
            $data = $request->user()
                ->createToken($credentials['username'], $access)
                ->toArray();
            return $this->success($data, __('user.login_success'));
        }
        return $this->error(__('user.login_error'));
    }

    /** 退出登录 */
    #[PostMapping('/logout')]
    public function logout(Request $request): JsonResponse
    {
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
    public function updateAdmin(Request $request): JsonResponse
    {
        return $this->service->updateAdmin($request);
    }

    /** 修改密码 */
    #[PutMapping('/updatePassword')]
    public function updatePassword(Request $request): JsonResponse
    {
        return $this->service->updatePassword($request);
    }

    /** 上传头像 */
    #[PostMapping('/uploadAvatar')]
    public function uploadAvatar(): JsonResponse
    {
        $service = new FileService();
        $data = $service->upload(FileType::IMAGE, 1, 'public');
        return $this->success($data);
    }
}
