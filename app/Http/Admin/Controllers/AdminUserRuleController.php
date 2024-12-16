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

    /**
     * 新增管理员权限
     */
    #[PostMapping]
    #[Authorize('admin.rule.add')]
    public function add(AdminUserRuleRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    /**
     * 获取权限树状列表
     */
    #[GetMapping]
    #[Authorize('admin.rule.list')]
    public function list(): JsonResponse
    {
        $data = $this->model->getRuleTree();
        return $this->success(compact('data'));
    }

    /**
     * 编辑权限
     */
    #[PutMapping]
    #[Authorize('admin.rule.edit')]
    public function edit(AdminUserRuleRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
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

    /**
     * 获取权限pid
     */
    #[GetMapping('/getRulePid')]
    #[Authorize('admin.rule.list')]
    public function getRulePid(): JsonResponse
    {
        $data = $this->model->getRulePid();

        return $this->success(compact('data'));
    }
}
