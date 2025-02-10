<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserRuleRequest;
use App\Http\BaseController;
use App\Models\AdminRuleModel;
use App\Service\impl\AdminUserRuleService;
use Illuminate\Http\JsonResponse;

/**
 * 管理员权限控制器
 */
#[AdminController]
#[RequestMapping('/admin/rule')]
class AdminUserRuleController extends BaseController
{
    public function __construct()
    {
        $this->model = new AdminRuleModel;
        $this->service = new AdminUserRuleService;
    }

    /** 新增管理员权限 */
    #[PostMapping] #[Authorize('admin.rule.add')]
    public function add(AdminUserRuleRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 管理员权限列表 */
    #[GetMapping] #[Authorize('admin.rule.list')]
    public function list(): JsonResponse
    {
        return $this->service->list();
    }

    /** 获取父级权限 */
    #[GetMapping('/parent')] #[Authorize('admin.rule.list')]
    public function getRulesParent(): JsonResponse
    {
        return $this->service->getRuleParent();
    }

    /** 编辑管理员权限 */
    #[PutMapping] #[Authorize('admin.rule.edit')]
    public function edit(AdminUserRuleRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 设置显示 */
    #[PutMapping('/show/{ruleID}')] #[Authorize('admin.rule.edit')]
    public function show($ruleID): JsonResponse
    {
        return $this->service->setShow($ruleID);
    }

    /** 设置状态 */
    #[PutMapping('/status/{ruleID}')] #[Authorize('admin.rule.edit')]
    public function status($ruleID): JsonResponse
    {
        return $this->service->setStatus($ruleID);
    }

    /** 删除权限 */
    #[DeleteMapping] #[Authorize('admin.rule.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }
}
