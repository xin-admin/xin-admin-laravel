<?php

namespace App\Services;

use App\Repositories\SysUserRuleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SysUserService extends BaseService
{
    protected SysUserRuleRepository $repository;

    /**
     * 系统用户登录
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ]);

        if (Auth::attempt($credentials)) {
            $access = auth()->user()['rules'];
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
        $access = $info['rules'];
        $menus = $this->repository->getRuleByKeys($access);
        $menus = $this->getTreeData($menus, 'rule_id');
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
        $user = $this->repository->find($user_id);
        if (! password_verify($validated['oldPassword'], $user->password)) {
            return $this->error(__('user.old_password_error'));
        }
        $user->password = $validated['newPassword'];
        $user->save();
        return $this->success('ok');
    }
}