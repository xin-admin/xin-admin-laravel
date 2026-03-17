<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SysLoginRecordService;
use App\Services\Admin\SysUserDeptService;
use App\Services\Admin\SysUserRoleService;
use App\Services\Admin\SysUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xin\AnnoRoute\Crud\Create;
use Xin\AnnoRoute\Crud\Delete;
use Xin\AnnoRoute\Crud\Query;
use Xin\AnnoRoute\Crud\Update;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\GetRoute;
use Xin\AnnoRoute\Route\PostRoute;
use Xin\AnnoRoute\Route\PutRoute;

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
