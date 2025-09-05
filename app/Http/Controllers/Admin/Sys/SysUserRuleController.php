<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Repositories\Sys\SysRuleRepository;
use App\Services\SysUserRuleService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 管理员权限控制器
 */
#[RequestMapping('/admin/rule', 'admin.rule')]
#[Create, Update, Delete]
class SysUserRuleController extends BaseController
{
    public function __construct(SysRuleRepository $repository, SysUserRuleService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /** 管理员权限列表 */
    #[GetMapping(authorize: 'query')]
    public function listData(): JsonResponse
    {
        return $this->service->list();
    }

    /** 获取父级权限 */
    #[GetMapping('/parent', authorize: 'query')]
    public function getRulesParent(): JsonResponse
    {
        return $this->service->getRuleParent();
    }

    /** 设置显示 */
    #[PutMapping('/show/{id}', authorize: 'update')]
    public function show($id): JsonResponse
    {
        return $this->service->setShow($id);
    }

    /** 设置状态 */
    #[PutMapping('/status/{id}', authorize: 'update')]
    public function status($id): JsonResponse
    {
        return $this->service->setStatus($id);
    }
}
