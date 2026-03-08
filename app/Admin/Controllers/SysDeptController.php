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
#[RequestAttribute('/system/dept', 'system.dept')]
#[Create, Update]
class SysDeptController extends BaseController
{

    public function __construct(
        protected SysUserDeptService $service
    ) {}

    /** 部门列表 */
    #[GetRoute(authorize: 'query')]
    public function query(): JsonResponse
    {
        return $this->service->list();
    }

    /** 删除部门 */
    #[DeleteRoute(authorize: 'delete')]
    public function delete(Request $request): JsonResponse
    {
        return $this->service->batchDelete($request);
    }

    /** 获取部门用户列表 */
    #[GetRoute('/users/{id}', 'users')]
    public function users(int $id): JsonResponse
    {
        return $this->service->users($id);
    }

}
