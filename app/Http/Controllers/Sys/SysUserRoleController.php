<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Delete;
use App\Providers\AnnoRoute\Crud\Query;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Providers\AnnoRoute\Route\GetRoute;
use App\Providers\AnnoRoute\Route\PostRoute;
use App\Providers\AnnoRoute\Route\PutRoute;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysRoleRepository;
use App\Services\Sys\SysUserRoleService;
use App\Services\Sys\SysUserRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 角色管理控制器
 */
#[RequestAttribute('/sys-user/role', 'sys-user.role')]
#[Query, Create, Update, Delete]
class SysUserRoleController extends BaseController
{
    protected string $repository = SysRoleRepository::class;

    /** 获取角色用户列表 */
    #[GetRoute('/users/{id}', 'users')]
    public function users(int $id, SysUserRoleService $service): JsonResponse
    {
        return $this->success($service->users($id));
    }

    /** 设置启用状态 */
    #[PutRoute('/status/{id}', authorize: 'status')]
    public function status(int $id, SysUserRoleService $service): JsonResponse
    {
        return $service->setStatus($id);
    }

    /** 设置角色权限 */
    #[PostRoute('/rule', 'rule')]
    public function setRoleRule(Request $request, SysUserRoleService $service): JsonResponse
    {
        $service->setRule($request);
        return $this->success();
    }

    /** 获取权限选项 */
    #[GetRoute('/rule/list', 'rule.list')]
    public function ruleList(SysUserRuleService $service): JsonResponse
    {
        return $service->getRuleFields();
    }
}
