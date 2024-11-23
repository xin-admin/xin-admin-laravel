<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\UserRechargeRequest;
use App\Http\Admin\Requests\UserRequest\UserRequest;
use App\Http\Admin\Requests\UserResetPasswordRequest;
use App\Http\BaseController;
use App\Models\User\UserModel;
use App\Service\UserService;
use App\Trait\BuilderTrait;
use Illuminate\Http\JsonResponse;

#[AdminController]
#[RequestMapping('/admin/user')]
class UserListController extends BaseController
{
    use BuilderTrait;

    #[Autowired]
    protected UserService $userService;

    #[Autowired]
    protected UserModel $model;

    #[GetMapping]
    #[Authorize('user.list.list')]
    public function list(): JsonResponse
    {
        $searchField = ['group_id' => '=', 'created_at' => 'date'];
        $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];
        return $this->listResponse($this->model, $searchField, $quickSearchField);
    }

    #[PutMapping]
    #[Authorize('user.list.edit')]
    public function edit(UserRequest $request): JsonResponse {
        return $this->updateResponse($this->model, $request);
    }

    #[Authorize('admin.user.recharge')]
    #[PostMapping('/recharge')]
    public function recharge(UserRechargeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->userService->recharge($data['user_id'], $data['amount'], $data['mode'], $data['remark']);
        return $this->success('ok');
    }

    #[Authorize('admin.user.resetPassword')]
    #[PostMapping('/resetPassword')]
    public function resetPassword(UserResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->userService->resetPassword($data['user_id'], $data['password']);
        return $this->success('ok');
    }
}