<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Providers\AnnoRoute\Route\DeleteRoute;
use App\Providers\AnnoRoute\Route\GetRoute;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysDeptRepository;
use App\Services\Sys\SysUserDeptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 部门管理控制器
 */
#[RequestAttribute('/sys-user/dept', 'sys-user.dept')]
#[Create, Update]
class SysUserDeptController extends BaseController
{

    protected string $repository = SysDeptRepository::class;


    /** 部门列表 */
    #[GetRoute(authorize: 'query')]
    public function listDept(SysUserDeptService $service): JsonResponse
    {
        return $service->list();
    }

    /** 删除部门 */
    #[DeleteRoute(authorize: 'delete')]
    public function deleteDept(Request $request, SysUserDeptService $service): JsonResponse
    {
        return $service->delete($request);
    }

    /** 获取部门用户列表 */
    #[GetRoute('/users/{id}', authorize: 'users')]
    public function deptUsers(int $id, SysUserDeptService $service): JsonResponse
    {
        return $service->users($id);
    }

}
