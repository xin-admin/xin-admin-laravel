<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Services\AnnoRoute\Crud\Create;
use App\Services\AnnoRoute\Crud\Delete;
use App\Services\AnnoRoute\Crud\Update;
use App\Services\AnnoRoute\RequestAttribute;
use App\Services\AnnoRoute\Route\GetRoute;
use App\Services\AnnoRoute\Route\PutRoute;
use App\Services\System\SysUserRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 管理员权限控制器
 */
#[RequestAttribute('/system/user/rule', 'sys-user.rule')]
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
