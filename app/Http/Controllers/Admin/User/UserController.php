<?php

namespace App\Http\Controllers\Admin\User;

use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\PostMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Http\Admin\Requests\UserRechargeRequest;
use App\Http\Admin\Requests\UserResetPasswordRequest;
use App\Models\User\UserModel;
use App\Service\IUserService;
use App\Trait\CreateTrait;
use App\Trait\DeleteTrait;
use App\Trait\BuilderTrait;
use App\Trait\UpdateTrait;
use Illuminate\Http\JsonResponse;

#[RequestMapping('/admin/user/user')]
class UserController extends BaseController
{
    use BuilderTrait, UpdateTrait, DeleteTrait, CreateTrait;
    const AUTH_NAME = 'admin.user';

    #[Autowired]
    protected IUserService $userService;

    #[Autowired]
    protected UserModel $modelObj;

    protected function initialize(): void
    {
        $this->searchField = [
            'group_id' => '=',
            'created_at' => 'date'
        ];
        $this->quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];
        $this->rule = [];
        $this->message = [];
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
