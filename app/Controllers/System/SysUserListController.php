<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Services\AnnoRoute\Crud\Create;
use App\Services\AnnoRoute\Crud\Delete;
use App\Services\AnnoRoute\Crud\Query;
use App\Services\AnnoRoute\Crud\Update;
use App\Services\AnnoRoute\RequestAttribute;
use App\Services\AnnoRoute\Route\GetRoute;
use App\Services\AnnoRoute\Route\PutRoute;
use App\Services\System\SysUserDeptService;
use App\Services\System\SysUserRoleService;
use App\Services\System\SysUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 系统用户列表控制器
 */
#[RequestAttribute('/sys-user/list', 'sys-user.list')]
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
