<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysUserRuleService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Delete;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
use App\Common\Services\AnnoRoute\Route\PutRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 管理员权限控制器
 */
#[RequestAttribute('/system/user/rule', 'system.user.rule')]
#[Create, Update, Delete]
class SysUserRuleController extends BaseController
{
    public function __construct(
        protected SysUserRuleService $service
    ) {}

    /** 获取权限列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        return $this->service->getList();
    }

    /** 获取父级权限 */
    #[GetRoute('/parent', authorize: 'parentQuery')]
    public function getRulesParent(): JsonResponse
    {
        return $this->service->getRuleParent();
    }

    /** 设置显示状态 */
    #[PutRoute('/show/{id}', authorize: 'show')]
    public function show($id): JsonResponse
    {
        return $this->service->setShow($id);
    }

    /** 设置启用状态 */
    #[PutRoute('/status/{id}', authorize: 'status')]
    public function status($id): JsonResponse
    {
        return $this->service->setStatus($id);
    }
}
