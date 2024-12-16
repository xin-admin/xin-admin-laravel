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
use App\Http\Admin\Requests\AdminUserRequest\AdminUserRequest;
use App\Http\Admin\Requests\AdminUserRequest\AdminUserResetPasswordRequest;
use App\Http\BaseController;
use App\Models\AdminUserModel;
use App\Service\AdminUserListService;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/list')]
class AdminUserListController extends BaseController
{
    #[Autowired]
    protected AdminUserModel $model;

    #[Autowired]
    protected AdminUserListService $adminListService;

    protected array $searchField = ['group_id' => '=', 'created_at' => 'date'];

    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];

    #[PostMapping]
    #[Authorize('admin.list.add')]
    public function add(AdminUserRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    #[GetMapping]
    #[Authorize('admin.list.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse($this->model);
    }

    #[PutMapping]
    #[Authorize('admin.list.edit')]
    public function edit(AdminUserRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }

    #[DeleteMapping]
    #[Authorize('admin.list.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse($this->model);
    }

    #[PostMapping('/resetPassword')]
    #[Authorize('admin.list.resetPassword')]
    public function resetPassword(AdminUserResetPasswordRequest $request): JsonResponse
    {
        return $this->adminListService
            ->resetPassword($request->validated());
    }
}
