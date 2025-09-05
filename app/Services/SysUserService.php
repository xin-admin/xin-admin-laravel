<?php

namespace App\Services;

use App\Models\Sys\SysUserModel;
use App\Repositories\Sys\SysRuleRepository;
use App\Repositories\Sys\SysUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SysUserService extends Service
{
    protected SysUserRepository $repository;
    protected SysUserModel $model;

    public function __construct(SysUserRepository $repository, SysUserModel $model) {
        $this->repository = $repository;
        $this->model = $model;
    }

    /**
     * 系统用户登录
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|min:3|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ]);

        if (Auth::attempt($credentials)) {
            $userID = auth()->id();
            $access = $this->repository->ruleKeys($userID);
            $data = $request->user()
                ->createToken($credentials['username'], $access)
                ->toArray();
            return $this->success($data, __('user.login_success'));
        }
        return $this->error(__('user.login_error'));
    }

    /**
     * 获取管理员信息
     * @return JsonResponse
     */
    public function getAdminInfo(): JsonResponse
    {
        $info = auth()->user();
        $userID = auth()->id();
        $access = $this->repository->ruleKeys($userID);
        $menus = $this->repository->rules($userID);
        $menus = $this->getTreeData($menus, 'id');
        return $this->success(compact('info', 'menus', 'access'));
    }

    /**
     * 修改密码
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'oldPassword' => 'required|string|min:6|max:20',
            'newPassword' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:newPassword',
        ]);
        $user_id = auth()->id();
        $user = $this->model->find($user_id);
        if (! password_verify($validated['oldPassword'], $user->password)) {
            return $this->error(__('user.old_password_error'));
        }
        $user->password = $validated['newPassword'];
        $user->save();
        return $this->success('ok');
    }

    /** 重置管理员密码 */
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'required|exists:App\Models\SysUserModel,id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ]);
        $user = SysUserModel::find($data['id']);
        $user->password = $data['password'];
        $user->save();
        return $this->success('ok');
    }

}