<?php

namespace App\Http\Controllers\Admin\User;

use App\Attribute\Auth;
use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\UserRechargeRequest;
use App\Http\Requests\UserResetPasswordRequest;
use App\Service\User\UserResetPasswordService;
use App\Models\User\UserModel;
use App\Service\User\UserRechargeService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected string $authName = 'user.list';

    protected string $model = UserModel::class;

    protected array $searchField = [
        'group_id' => '=',
        'created_at' => 'date'
    ];

    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];


    /**
     * 用户充值
     */
    #[Auth('recharge')]
    public function recharge(UserRechargeRequest $request): JsonResponse
    {
        $data = $request->validated();
        UserRechargeService::recharge($data['user_id'], $data['amount'], $data['mode'], $data['remark']);
        return $this->success('ok');
    }

    /**
     * 重置用户密码
     */
    #[Auth('resetPassword')]
    public function resetPassword(UserResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        UserResetPasswordService::reset($data['user_id'], $data['password']);
        return $this->success('ok');
    }
}
