<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\SysUserRequest\SysUserGroupRequest;
use App\Http\Admin\Requests\SysUserRequest\SysUserRuleRequest;
use App\Http\Admin\Requests\SysUserRequest\SysUserSetGroupRuleRequest;
use App\Models\Admin\AdminGroupModel;
use App\Trait\BuilderTrait;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/group')]
class SysUserGroupController
{
    use BuilderTrait, RequestJson;

    private AdminGroupModel $model;

    #[GetMapping]
    #[Authorize('admin.group.list')]
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
    #[Authorize('admin.group.add')]
    public function create(SysUserGroupRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[PutMapping]
    #[Authorize('admin.group.edit')]
    public function edit(SysUserRuleRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('admin.group.delete')]
    public function delete(): JsonResponse {
        return $this->deleteResponse($this->model);
    }

    #[PostMapping('/setGroupRule')]
    #[Authorize('admin.group.edit')]
    public function setGroupRule(SysUserSetGroupRuleRequest $request): JsonResponse
    {
        $group = $this->model::query()->find($request->validated('id'));
        $group->rules = implode(',', $request->validated('rule_ids'));
        $group->save();
        return $this->success();
    }
}