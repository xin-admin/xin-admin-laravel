<?php

namespace App\Admin\Controllers;

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
use App\Common\Services\AnnoRoute\Route\PutRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 系统用户列表控制器
 */
#[RequestAttribute('/system/user/list', 'system.user.list')]
#[Create, Update, Delete, Query]
class SysUserListController extends BaseController
{
    public function __construct(
        protected SysUserService $service
    ) {}

    /** 重置用户密码 */
    #[PutRoute('/reset/password', 'resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        return $this->service->resetPassword($request);
    }

    /** 获取用户角色选项栏数据 */
    #[GetRoute('/role', 'getRole')]
    public function role(SysUserRoleService $service): JsonResponse
    {
        return $this->success($service->getRoleFields());
    }

    /** 获取用户部门选项栏数据 */
    #[GetRoute('/dept', 'getDept')]
    public function dept(SysUserDeptService $service): JsonResponse
    {
        return $this->success($service->getDeptField());
    }
}
