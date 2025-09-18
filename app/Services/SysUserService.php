<?php

namespace App\Services;

use App\Models\Sys\SysUserModel;
use App\Repositories\Sys\SysUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        if (Auth::attempt($credentials, true)) {
            $userID = auth()->id();
            $access = $this->repository->ruleKeys($userID);
            $data = $request->user()
                ->createToken($credentials['username'], $access)
                ->toArray();
            // 记录登录 IP 与 时间
            SysUserModel::query()->where('id', $userID)->update([
                'login_time' => now(),
                'login_ip' => $request->ip(),
            ]);
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
        $user->password = Hash::make($validated['newPassword']);
        $user->save();
        return $this->success('ok');
    }

    /**
     * 重置管理员密码
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'required|exists:sys_user,id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ], [
            'id.required' => '请选择管理员用户！',
            'id.exists' => '管理员用户不存在！',
            'password.required' => '请输入管理员密码！',
            'password.min' => '密码最短为6个字符！',
            'password.max' => '密码最长伟20个字符！',
            'rePassword.required' => '请重复输入密码！',
            'rePassword.same' => '两次输入的密码不同！',
        ]);
        $user = SysUserModel::find($data['id']);
        if (!$user) {
            return $this->error(__('user.user_not_exist'));
        }
        $user->password = Hash::make($data['password']);
        $user->save();
        return $this->success('ok');
    }

    /**
     * 修改管理员用户状态
     * @param $id
     * @return JsonResponse
     */
    public function resetStatus($id): JsonResponse
    {
        $user = SysUserModel::find($id);
        if (!$user) {
            return $this->error(__('user.user_not_exist'));
        }
        $user->status = $user->status == 0 ? 1 : 0;
        $user->save();
        return $this->success('ok');
    }

    /**
     * 更新用户信息
     * @param int $id 用户ID
     * @param Request $request 请求
     * @return JsonResponse
     */
    public function updateInfo(int $id, Request $request): JsonResponse
    {
        $data = $request->validate([
            'nickname' => 'required',
            'sex' => 'required|in:0,1',
            'mobile' => 'required',
            'email' => 'required|email|unique:sys_user,email',
        ], [
            'nickname.required' => '昵称不能为空',
            'sex.required' => '性别不能为空',
            'sex.in' => '性别格式错误',
            'mobile.required' => '手机号不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式错误'
        ]);
        $model = SysUserModel::find($id);
        if (empty($model)) {
            return $this->error(__('user.user_not_exist'));
        }
        return $this->success($model->update($data));
    }

}