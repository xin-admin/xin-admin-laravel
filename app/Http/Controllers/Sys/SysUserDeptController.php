<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\DeleteMapping;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysDeptRepository;
use App\Services\Sys\SysUserDeptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 部门管理控制器
 */
#[RequestMapping('/sys-user/dept', 'sys-user.dept')]
#[Create, Update]
class SysUserDeptController extends BaseController
{

    protected function repository(): RepositoryInterface
    {
        return app(SysDeptRepository::class);
    }


    /** 部门列表 */
    #[GetMapping(authorize: 'query')]
    public function listDept(SysUserDeptService $service): JsonResponse
    {
        return $service->list();
    }

    /** 删除部门 */
    #[DeleteMapping(authorize: 'delete')]
    public function deleteDept(Request $request, SysUserDeptService $service): JsonResponse
    {
        return $service->delete($request);
    }

    /** 获取部门用户列表 */
    #[GetMapping('/users/{id}', authorize: 'users')]
    public function deptUsers(int $id, SysUserDeptService $service): JsonResponse
    {
        return $service->users($id);
    }

}
