<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\AdminRequest\AdminUserRequest;
use App\Http\BaseController;
use App\Models\AdminUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\Update;
use Xin\AnnoRoute\Attribute\Delete;

/**
 * 管理员列表控制器
 */
#[RequestMapping('/admin/list', 'admin.list')]
#[Create, Update, Delete, Query]
class AdminUserListController extends BaseController
{
    protected string $model = AdminUserModel::class;

    protected string $formRequest = AdminUserRequest::class;

    protected array $searchField = ['dept_id' => '='];

    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'user_id'];

    /** 重置管理员密码 */
    #[PutMapping('/resetPassword', 'resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'required|exists:App\Models\AdminUserModel,user_id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ]);
        $user = AdminUserModel::find($data['id']);
        $user->password = $data['password'];
        $user->save();
        return $this->success('ok');
    }
}
