<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysDeptRepository;
use App\Repositories\Sys\SysRoleRepository;
use App\Repositories\Sys\SysUserRepository;
use App\Services\SysUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 系统用户列表控制器
 */
#[RequestMapping('/sys-user/list', 'sys-user.list')]
#[Create, Update, Delete, Query]
class SysUserListController extends BaseController
{
    public function __construct(SysUserRepository $repository, SysUserService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /** 重置用户密码 */
    #[PutMapping('/reset/password', 'resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        return $this->service->resetPassword($request);
    }

    /** 修改用户状态 */
    #[PostMapping('/status/{id}', 'resetStatus')]
    public function resetStatus($id): JsonResponse
    {
        return $this->service->resetStatus($id);
    }

    /** 获取用户角色选项栏数据 */
    #[GetMapping('/role', 'getRole')]
    public function role(SysRoleRepository $repository): JsonResponse
    {
        return $this->success($repository->getRoleField());
    }

    /** 获取用户部门选项栏数据 */
    #[GetMapping('/dept', 'getDept')]
    public function dept(SysDeptRepository $repository): JsonResponse
    {
        return $this->success($repository->getDeptField());
    }
}
