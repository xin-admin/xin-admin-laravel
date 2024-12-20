<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserRuleRequest;
use App\Http\BaseController;
use App\Models\AdminRuleModel;
use App\Service\AdminUserRuleService;
use Illuminate\Http\JsonResponse;

/**
 * 管理员权限
 */
#[AdminController]
#[RequestMapping('/admin/rule')]
class AdminUserRuleController extends BaseController
{
    #[Autowired]
    protected AdminRuleModel $model;

    #[Autowired]
    protected AdminUserRuleService $adminUserRuleService;

    #[PostMapping]
    #[Authorize('admin.rule.add')]
    public function add(AdminUserRuleRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    #[GetMapping]
    #[Authorize('admin.rule.list')]
    public function list(): JsonResponse
    {
        return $this->adminUserRuleService->getDataTree();
    }

    #[GetMapping('/parent')]
    #[Authorize('admin.rule.list')]
    public function getRulesParent(): JsonResponse
    {
        return $this->adminUserRuleService->getRuleParent();
    }

    #[PutMapping]
    #[Authorize('admin.rule.edit')]
    public function edit(AdminUserRuleRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }

    #[PutMapping('/show/{ruleID}')]
    #[Authorize('admin.rule.edit')]
    public function show($ruleID): JsonResponse
    {
        return $this->adminUserRuleService->setShow($ruleID);
    }

    #[PutMapping('/status/{ruleID}')]
    #[Authorize('admin.rule.edit')]
    public function status($ruleID): JsonResponse
    {
        return $this->adminUserRuleService->setStatus($ruleID);
    }

    /**
     * 删除权限
     */
    #[DeleteMapping]
    #[Authorize('admin.rule.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse($this->model);
    }
}
