<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysSettingGroupService;
use App\Admin\Services\SysUserDeptService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\DeleteRoute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 部门管理控制器
 */
#[RequestAttribute('/system/user/dept', 'system.dept')]
#[Create, Update]
class SysUserDeptController extends BaseController
{

    public function __construct(
        protected SysSettingGroupService $service
    ) {}


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
        return $service->batchDelete($request);
    }

    /** 获取部门用户列表 */
    #[GetRoute('/users/{id}', authorize: 'users')]
    public function deptUsers(int $id, SysUserDeptService $service): JsonResponse
    {
        return $service->users($id);
    }

}
