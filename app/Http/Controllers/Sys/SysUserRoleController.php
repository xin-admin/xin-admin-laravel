<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysRoleRepository;
use App\Services\Sys\SysUserRoleService;
use App\Services\Sys\SysUserRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 角色管理控制器
 */
#[RequestMapping('/sys-user/role', 'sys-user.role')]
#[Query, Create, Update, Delete]
class SysUserRoleController extends BaseController
{

    protected function repository(): RepositoryInterface
    {
        return app(SysRoleRepository::class);
    }


    /** 获取角色用户列表 */
    #[GetMapping('/users/{id}', 'users')]
    public function users(int $id, SysUserRoleService $service): JsonResponse
    {
        return $this->success($service->users($id));
    }

    /** 设置启用状态 */
    #[PutMapping('/status/{id}', authorize: 'status')]
    public function status(int $id, SysUserRoleService $service): JsonResponse
    {
        return $service->setStatus($id);
    }

    /** 设置角色权限 */
    #[PostMapping('/rule', 'rule')]
    public function setRoleRule(Request $request, SysUserRoleService $service): JsonResponse
    {
        $service->setRule($request);
        return $this->success();
    }

    /** 获取权限选项 */
    #[GetMapping('/rule/list', 'rule.list')]
    public function ruleList(SysUserRuleService $service): JsonResponse
    {
        return $service->getRuleFields();
    }
}
