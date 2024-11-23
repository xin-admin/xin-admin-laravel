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
use App\Models\User\UserRuleModel;
use App\Trait\BuilderTrait;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/user/rule')]
class UserRuleController
{
    use BuilderTrait, RequestJson;

    #[Autowired]
    protected UserRuleModel $model;

    #[PostMapping]
    #[Authorize('user.rule.add')]
    public function create(UserRuleRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[GetMapping]
    #[Authorize('user.rule.list')]
    public function list(): JsonResponse {
        $rootNode = $this->model->getRuleTree();
        return $this->success(compact('rootNode'));
    }

    #[PutMapping]
    #[Authorize('user.rule.edit')]
    public function edit(UserRuleRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('user.rule.delete')]
    public function delete(): JsonResponse {
        return $this->deleteResponse($this->model);
    }

    #[GetMapping('/getRulePid')]
    #[Authorize('user.rule.list')]
    public function getRulePid(): JsonResponse {
        $data = $this->model->getRulePid();
        return $this->success(compact('data'));
    }

}