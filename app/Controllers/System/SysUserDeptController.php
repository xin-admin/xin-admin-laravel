<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Services\AnnoRoute\Crud\Create;
use App\Services\AnnoRoute\Crud\Update;
use App\Services\AnnoRoute\RequestAttribute;
use App\Services\AnnoRoute\Route\DeleteRoute;
use App\Services\AnnoRoute\Route\GetRoute;
use App\Services\System\SysSettingGroupService;
use App\Services\System\SysUserDeptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 部门管理控制器
 */
#[RequestAttribute('/system/user/dept', 'sys-user.dept')]
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
