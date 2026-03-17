<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SysUserDeptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Crud\Create;
use Xin\AnnoRoute\Crud\Update;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\DeleteRoute;
use Xin\AnnoRoute\Route\GetRoute;

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
