<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
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
#[RequestMapping('/sys-user/list', 'sys-user.list')]
#[Create, Update, Delete, Query]
class SysUserListController extends BaseController
{

    protected function repository(): RepositoryInterface
    {
        return app(SysUserRepository::class);
    }


    /** 重置用户密码 */
    #[PutMapping('/reset/password', 'resetPassword')]
    public function resetPassword(Request $request, SysUserService $service): JsonResponse
    {
        return $service->resetPassword($request);
    }

    /** 获取用户角色选项栏数据 */
    #[GetMapping('/role', 'getRole')]
    public function role(SysUserRoleService $service): JsonResponse
    {
        return $this->success($service->getRoleFields());
    }

    /** 获取用户部门选项栏数据 */
    #[GetMapping('/dept', 'getDept')]
    public function dept(SysUserDeptService $service): JsonResponse
    {
        return $this->success($service->getDeptField());
    }
}
