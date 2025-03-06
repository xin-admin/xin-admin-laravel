<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Enum\FileType;
use App\Http\Admin\Requests\UserRequest;
use App\Http\BaseController;
use App\Models\XinUserModel;
use App\Service\impl\UpdateFileService;
use App\Service\impl\XinUserListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

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

    /** 重置密码 */
    #[PutMapping('/resetPassword')] #[Authorize('user.list.resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:xin_user,user_id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ]);
        $this->service->resetPassword($data['user_id'], $data['password']);

        return $this->success('ok');
    }

    /** 上传头像 */
    #[PostMapping('/avatar')]
    public function avatar(): JsonResponse
    {
        // TODO 上传头像需要完善权限和上传头像目录 （待完成）
        $service = new UpdateFileService;
        $service->setFileType(FileType::IMAGE);

        return $service->upload(0);
    }
}
