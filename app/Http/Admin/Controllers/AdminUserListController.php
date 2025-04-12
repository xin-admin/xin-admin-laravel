<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\AdminRequest\AdminUserRequest;
use App\Http\BaseController;
use App\Models\AdminUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 管理员列表控制器
 */
#[RequestMapping('/admin/list')]
class AdminUserListController extends BaseController
{
    public function __construct()
    {
        $this->model = new AdminUserModel;
        $this->searchField = ['dept_id' => '='];
        $this->quickSearchField = ['username', 'nickname', 'email', 'mobile', 'user_id'];
    }

    /** 新增管理员用户 */
    #[PostMapping] #[Authorize('admin.list.add')]
    public function add(AdminUserRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 获取管理员用户列表 */
    #[GetMapping] #[Authorize('admin.list.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 编辑管理员用户 */
    #[PutMapping] #[Authorize('admin.list.edit')]
    public function edit(AdminUserRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除管理员用户 */
    #[DeleteMapping] #[Authorize('admin.list.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }

    /** 重置管理员密码 */
    #[PutMapping('/resetPassword')] #[Authorize('admin.list.resetPassword')]
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
