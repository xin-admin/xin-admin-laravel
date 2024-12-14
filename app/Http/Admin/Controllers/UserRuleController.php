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
use App\Http\Admin\Requests\UserRequest\UserRuleRequest;
use App\Http\BaseController;
use App\Modelss\User\UserRuleModel;
use Illuminate\Http\JsonResponse;

/**
 * 前台用户权限
 */
#[AdminController]
#[RequestMapping('/user/rule')]
class UserRuleController extends BaseController
{
    #[Autowired]
    protected UserRuleModel $model;

    /**
     * 添加用户权限
     */
    #[PostMapping]
    #[Authorize('user.rule.add')]
    public function add(UserRuleRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    /**
     * 获取用户权限列表
     */
    #[GetMapping]
    #[Authorize('user.rule.list')]
    public function list(): JsonResponse
    {
        $data = $this->model->getRuleTree();

        return $this->success(compact('data'));
    }

    /**
     * 编辑用户权限
     */
    #[PutMapping]
    #[Authorize('user.rule.edit')]
    public function edit(UserRuleRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }

    /**
     * 删除用户权限
     */
    #[DeleteMapping]
    #[Authorize('user.rule.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse($this->model);
    }

    /**
     * 获取用户权限pid
     */
    #[GetMapping('/getRulePid')]
    #[Authorize('user.rule.list')]
    public function getRulePid(): JsonResponse
    {
        $data = $this->model->getRulePid();

        return $this->success(compact('data'));
    }
}
