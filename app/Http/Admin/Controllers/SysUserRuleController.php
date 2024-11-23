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
use App\Http\Admin\Requests\SysUserRequest\SysUserRuleRequest;
use App\Http\BaseController;
use App\Models\Admin\AdminRuleModel;
use App\Trait\BuilderTrait;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/rule')]
class SysUserRuleController extends BaseController
{

    use BuilderTrait;

    #[Autowired]
    protected AdminRuleModel $model;

    #[PostMapping]
    #[Authorize('admin.rule.add')]
    public function create(SysUserRuleRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[GetMapping]
    #[Authorize('admin.rule.list')]
    public function list(): JsonResponse {
        $rootNode = $this->model->getRuleTree();
        return $this->success(compact('rootNode'));
    }

    #[PutMapping]
    #[Authorize('admin.rule.edit')]
    public function edit(SysUserRuleRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('admin.rule.delete')]
    public function delete(): JsonResponse {
        return $this->deleteResponse($this->model);
    }

    #[GetMapping('/getRulePid')]
    #[Authorize('admin.rule.list')]
    public function getRulePid(): JsonResponse {
        $data = $this->model->getRulePid();
        return $this->success(compact('data'));
    }
}