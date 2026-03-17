<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SysUserRuleService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Crud\Create;
use Xin\AnnoRoute\Crud\Delete;
use Xin\AnnoRoute\Crud\Update;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\GetRoute;
use Xin\AnnoRoute\Route\PutRoute;

/**
 * 管理员权限控制器
 */
#[RequestAttribute('/system/rule', 'system.rule')]
#[Create, Update, Delete]
class SysRuleController extends BaseController
{
    public function __construct(
        protected SysUserRuleService $service
    ) {}

    /** 获取权限列表 */
    #[GetRoute(authorize: 'query')]
    public function query(): JsonResponse
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
