<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SysUserRoleService;
use App\Services\Admin\SysUserRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Crud\Create;
use Xin\AnnoRoute\Crud\Delete;
use Xin\AnnoRoute\Crud\Query;
use Xin\AnnoRoute\Crud\Update;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\GetRoute;
use Xin\AnnoRoute\Route\PostRoute;
use Xin\AnnoRoute\Route\PutRoute;

/**
 * 角色管理控制器
 */
#[RequestAttribute('/system/role', 'system.role')]
#[Query, Create, Update, Delete]
class SysRoleController extends BaseController
{

    public function __construct(
        protected SysUserRoleService $service,
        protected SysUserRuleService  $ruleService,
    ) {}

    /** 获取角色用户列表 */
    #[GetRoute('/users/{id}', 'users')]
    public function users(int $id): JsonResponse
    {
        return $this->success($this->service->users($id));
    }

    /** 设置启用状态 */
    #[PutRoute('/status/{id}', 'status')]
    public function status(int $id): JsonResponse
    {
        return $this->service->setStatus($id);
    }

    /** 获取权限选项 */
    #[GetRoute('/ruleList', 'ruleList')]
    public function ruleList(): JsonResponse
    {
        return $this->ruleService->getRuleFields();
    }

    /** 设置角色权限 */
    #[PostRoute('/setRule', 'setRule')]
    public function setRule(Request $request): JsonResponse
    {
        $this->service->setRule($request);
        return $this->success();
    }
}
