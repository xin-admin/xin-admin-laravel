<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysRuleRepository;
use App\Services\SysUserRuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 管理员权限控制器
 */
#[RequestMapping('/sys-user/rule', 'sys-user.rule')]
#[Create, Update, Delete]
class SysUserRuleController extends BaseController
{
    public function __construct(SysRuleRepository $repository, SysUserRuleService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /** 获取权限列表 */
    #[GetMapping(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        return $this->service->getList();
    }

    /** 获取父级权限 */
    #[GetMapping('/parent', authorize: 'parentQuery')]
    public function getRulesParent(): JsonResponse
    {
        return $this->service->getRuleParent();
    }

    /** 设置显示状态 */
    #[PutMapping('/show/{id}', authorize: 'show')]
    public function show($id): JsonResponse
    {
        return $this->service->setShow($id);
    }

    /** 设置启用状态 */
    #[PutMapping('/status/{id}', authorize: 'status')]
    public function status($id): JsonResponse
    {
        return $this->service->setStatus($id);
    }
}
