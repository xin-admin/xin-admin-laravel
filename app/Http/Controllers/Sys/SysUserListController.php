<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Delete;
use App\Providers\AnnoRoute\Crud\Query;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Providers\AnnoRoute\Route\GetRoute;
use App\Providers\AnnoRoute\Route\PutRoute;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysUserRepository;
use App\Services\Sys\SysUserDeptService;
use App\Services\Sys\SysUserRoleService;
use App\Services\Sys\SysUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 系统用户列表控制器
 */
#[RequestAttribute('/sys-user/list', 'sys-user.list')]
#[Create, Update, Delete, Query]
class SysUserListController extends BaseController
{
    protected string $repository = SysUserRepository::class;


    /** 重置用户密码 */
    #[PutRoute('/reset/password', 'resetPassword')]
    public function resetPassword(Request $request, SysUserService $service): JsonResponse
    {
        return $service->resetPassword($request);
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
