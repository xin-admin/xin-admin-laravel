<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysUserRoleService;
use App\Admin\Services\SysUserRuleService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Delete;
use App\Common\Services\AnnoRoute\Crud\Query;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
use App\Common\Services\AnnoRoute\Route\PostRoute;
use App\Common\Services\AnnoRoute\Route\PutRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
