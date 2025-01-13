<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Enum\FileType;
use App\Http\Admin\Requests\UserRechargeRequest;
use App\Http\Admin\Requests\UserRequest\UserRequest;
use App\Http\Admin\Requests\UserResetPasswordRequest;
use App\Http\BaseController;
use App\Models\XinUserModel;
use App\Service\impl\UpdateFileService;
use App\Service\impl\XinUserListService;
use Illuminate\Http\JsonResponse;

/**
 * 前台用户列表
 */
#[AdminController]
#[RequestMapping('/user/list')]
class UserListController extends BaseController
{
    public function __construct()
    {
        $this->model = new XinUserModel;
        $this->service = new XinUserListService;
        $this->quickSearchField = ['username', 'nickname', 'email', 'mobile', 'user_id'];
    }

    /** 获取用户列表 */
    #[GetMapping] #[Authorize('user.list.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 编辑用户信息 */
    #[PutMapping] #[Authorize('user.list.edit')]
    public function edit(UserRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 充值 */
    #[PostMapping('/recharge')] #[Authorize('user.list.recharge')]
    public function recharge(UserRechargeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->service->recharge($data['user_id'], $data['amount'], $data['mode'], $data['remark']);

        return $this->success('ok');
    }

    /** 重置密码 */
    #[PutMapping('/resetPassword')] #[Authorize('user.list.resetPassword')]
    public function resetPassword(UserResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->service->resetPassword($data['user_id'], $data['password']);

        return $this->success('ok');
    }

    /** 上传头像 */
    #[PostMapping('/avatar')]
    public function avatar(): JsonResponse
    {
        // TODO 上传头像需要完善权限和上传头像目录
        $service = new UpdateFileService;
        $service->setFileType(FileType::IMAGE);
        return $service->upload(0);
    }

}
