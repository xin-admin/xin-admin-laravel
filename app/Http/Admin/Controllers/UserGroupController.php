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
use App\Http\Admin\Requests\SysUserRequest\SysUserSetGroupRuleRequest;
use App\Http\Admin\Requests\UserRequest\UserGroupRequest;
use App\Models\User\UserModel;
use App\Trait\BuilderTrait;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/user/group')]
class UserGroupController
{
    use BuilderTrait, RequestJson;

    #[Autowired]
    protected UserModel $model;

    #[GetMapping]
    #[Authorize('user.group.list')]
    public function list(): JsonResponse {
        $searchField = [
            'id' => '=',
            'name' => 'like',
            'pid' => '=',
            'create_time' => 'date',
            'update_time' => 'date',
        ];
        return $this->listResponse($this->model, $searchField);
    }

    #[PostMapping]
    #[Authorize('user.group.add')]
    public function create(UserGroupRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[PutMapping]
    #[Authorize('user.group.edit')]
    public function edit(UserGroupRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('user.group.delete')]
    public function delete(): JsonResponse {
        return $this->deleteResponse($this->model);
    }

    #[PostMapping('/setGroupRule')]
    #[Authorize('user.group.edit')]
    public function setGroupRule(SysUserSetGroupRuleRequest $request): JsonResponse
    {
        $group = $this->model::query()->find($request->validated('id'));
        $group->rules = implode(',', $request->validated('rule_ids'));
        $group->save();
        return $this->success();
    }
}