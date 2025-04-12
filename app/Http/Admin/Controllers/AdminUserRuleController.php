<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\AdminRequest\AdminUserRuleRequest;
use App\Http\BaseController;
use App\Models\AdminRuleModel;
use App\Service\AdminUserRuleService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 管理员权限控制器
 */
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
