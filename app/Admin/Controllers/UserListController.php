<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\UserRequest;
use App\Admin\Service\XinUserListService;
use App\BaseController;
use App\Common\Models\XinUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 前台用户列表
 */
#[RequestMapping('/user/list', 'user.list')]
#[Query, Update]
class UserListController extends BaseController
{
    protected string $model = XinUserModel::class;
    protected string $formRequest = UserRequest::class;
    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'user_id'];

    /** 重置密码 */
    #[PutMapping('/resetPassword', 'resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:xin_user,user_id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ]);
        $service = new XinUserListService;
        $service->resetPassword($data['user_id'], $data['password']);

        return $this->success('ok');
    }
}
