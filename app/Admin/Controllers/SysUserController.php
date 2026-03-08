<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysLoginRecordService;
use App\Admin\Services\SysUserDeptService;
use App\Admin\Services\SysUserRoleService;
use App\Admin\Services\SysUserService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Delete;
use App\Common\Services\AnnoRoute\Crud\Query;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
use App\Common\Services\AnnoRoute\Route\PostRoute;
use App\Common\Services\AnnoRoute\Route\PutRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 管理员用户控制器
 */
#[RequestAttribute('/system/user', 'system.user')]
#[Create, Update, Delete, Query]
class SysUserController extends BaseController
{
    public function __construct(
        protected SysUserService $service,
        protected SysUserRoleService $roleService,
        protected SysUserDeptService $deptService,
        protected SysLoginRecordService $loginRecordService,
    ) {}

    /** 重置用户密码 */
    #[PutRoute('/resetPassword', 'resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        return $this->service->resetPassword($request);
    }

    /** 获取用户角色选项栏数据 */
    #[GetRoute('/role', 'role')]
    public function role(): JsonResponse
    {
        return $this->success($this->roleService->getRoleFields());
    }

    /** 获取用户部门选项栏数据 */
    #[GetRoute('/dept', 'dept')]
    public function dept(): JsonResponse
    {
        return $this->success($this->deptService->getDeptField());
    }

    /** 用户登录 */
    #[PostRoute('/login', false, 'login_log')]
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
    public function info(): JsonResponse
    {
        $info = Auth::user();
        $id = Auth::id();
        $access = $this->service->ruleKeys($id);
        return $this->success(compact('access','info'));
    }

    /** 获取菜单信息 */
    #[GetRoute('/menu')]
    public function menu(): JsonResponse
    {
        $id = Auth::id();
        $menus = $this->service->getAdminMenus($id);
        return $this->success(compact('menus'));
    }

    /** 更新管理员信息 */
    #[PutRoute('/updateInfo')]
    public function updateInfo(Request $request): JsonResponse
    {
        return $this->service->updateInfo(Auth::id(), $request);
    }

    /** 修改密码 */
    #[PutRoute('/updatePassword')]
    public function updatePassword(Request $request): JsonResponse
    {
        return $this->service->updatePassword($request);
    }

    /** 上传头像 */
    #[PostRoute('/uploadAvatar')]
    public function uploadAvatar(): JsonResponse
    {
        return $this->service->uploadAvatar();
    }

    /** 获取管理员登录日志 */
    #[GetRoute('/loginRecord')]
    public function loginRecord(): JsonResponse
    {
        $id = Auth::id();
        $data = $this->loginRecordService->getRecordByID($id);
        return $this->success($data);
    }
}
