<?php

namespace App\Http\Admin\Controllers;

use APP\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\SysUserRequest\SysUserRequest;
use App\Http\Admin\Requests\SysUserRequest\SysUserResetPasswordRequest;
use App\Models\Admin\AdminModel;
use App\Service\SysAdminListService;
use App\Trait\BuilderTrait;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/list')]
class SysUserListController
{
    use BuilderTrait;

    #[Autowired]
    protected AdminModel $model;

    #[Autowired]
    protected SysAdminListService $adminListService;

    #[PostMapping]
    #[Authorize('admin.list.create')]
    public function create(SysUserRequest $request): JsonResponse {
        return $this->createResponse($this->model, $request);
    }

    #[GetMapping]
    #[Authorize('admin.list.list')]
    public function list(): JsonResponse {
        $searchField = [ 'group_id' => '=', 'created_at' => 'date'];
        $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];
        return $this->listResponse($this->model, $searchField, $quickSearchField);
    }

    #[PutMapping]
    #[Authorize('admin.list.edit')]
    public function edit(SysUserRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('admin.list.delete')]
    public function delete(): JsonResponse {
        return $this->deleteResponse($this->model);
    }

    #[PostMapping('/resetPassword')]
    #[Authorize('admin.list.resetPassword')]
    public function resetPassword(SysUserResetPasswordRequest $request): JsonResponse {
        return $this->adminListService->resetPassword($request->validated());
    }
}