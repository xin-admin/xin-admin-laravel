<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysRoleModel;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysRoleRepository;
use App\Services\SysUserRoleService;
use App\Services\SysUserRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 角色管理控制器
 */
#[RequestMapping('/sys-user/role', 'sys-user.role')]
#[Query, Create, Update, Delete]
class SysUserRoleController extends BaseController
{
    public function __construct(SysRoleRepository $repository, SysRoleModel $model)
    {
        $this->repository = $repository;
        $this->model = $model;
    }

    /** 获取角色用户列表 */
    #[GetMapping('/users/{id}', 'users')]
    public function users($id): JsonResponse
    {
        return $this->success($this->repository->users($id));
    }

    /** 设置启用状态 */
    #[PutMapping('/status/{id}', authorize: 'status')]
    public function status(int $id): JsonResponse
    {
        return (new SysUserRoleService)->setStatus($id);
    }

    /** 设置角色权限 */
    #[PostMapping('/rule', 'rule')]
    public function setRoleRule(Request $request): JsonResponse
    {
        $this->repository->setRule($request);
        return $this->success();
    }

    /** 获取权限选项 */
    #[GetMapping('/rule/list', 'rule.list')]
    public function ruleList(SysUserRuleService $service): JsonResponse
    {
        return $service->getRuleFields();
    }
}
