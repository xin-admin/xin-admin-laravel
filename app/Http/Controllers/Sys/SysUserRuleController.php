<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Delete;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Providers\AnnoRoute\Route\GetRoute;
use App\Providers\AnnoRoute\Route\PutRoute;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysRuleRepository;
use App\Services\Sys\SysUserRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 管理员权限控制器
 */
#[RequestAttribute('/sys-user/rule', 'sys-user.rule')]
#[Create, Update, Delete]
class SysUserRuleController extends BaseController
{
    protected string $repository = SysRuleRepository::class;

    /** 获取权限列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        return (new SysUserRuleService)->getList();
    }

    /** 获取父级权限 */
    #[GetRoute('/parent', authorize: 'parentQuery')]
    public function getRulesParent(SysUserRuleService $service): JsonResponse
    {
        return $service->getRuleParent();
    }

    /** 设置显示状态 */
    #[PutRoute('/show/{id}', authorize: 'show')]
    public function show($id, SysUserRuleService $service): JsonResponse
    {
        return $service->setShow($id);
    }

    /** 设置启用状态 */
    #[PutRoute('/status/{id}', authorize: 'status')]
    public function status($id, SysUserRuleService $service): JsonResponse
    {
        return $service->setStatus($id);
    }
}
