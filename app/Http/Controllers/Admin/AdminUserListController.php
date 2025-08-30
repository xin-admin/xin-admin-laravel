<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Models\AdminUserModel;
use App\Repositories\SysUserRepository;
use App\Services\AdminUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 管理员列表控制器
 */
#[RequestMapping('/admin/list', 'admin.list')]
#[Create, Update, Delete]
class AdminUserListController extends BaseController
{
    protected SysUserRepository $repository;
    protected AdminUserService $userService;

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

    /** 列表响应 */
    #[GetMapping]
    public function query(Request $request): JsonResponse
    {
        $repository = new SysUserRepository();
        return $this->success($repository->list($request->toArray()));
    }


}
