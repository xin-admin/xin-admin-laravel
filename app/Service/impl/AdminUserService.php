<?php

namespace App\Service\impl;

use App\Attribute\Monitor;
use App\Enum\TokenEnum;
use App\Exceptions\HttpResponseException;
use App\Models\AdminRuleModel;
use App\Models\AdminUserModel;
use Illuminate\Http\JsonResponse;

class AdminUserService extends BaseService
{
    /**
     * 刷新Token
     */
    public function refreshToken(): JsonResponse
    {
        $token = request()->header('x-token');
        $reToken = request()->header('x-refresh-token');
        if ($reToken) {
            token()->delete($token);
            $user_id = token()->get($reToken)['user_id'];
            $token = token()->set($user_id, TokenEnum::ADMIN);

            return $this->success(compact('token'));
        } else {
            return $this->error(__('user.not_login'));
        }
    }

    /**
     * 登录
     */
    public function login(array $data): JsonResponse
    {
        $username = $data['username'];
        $password = $data['password'];
        $adminUser = AdminUserModel::where('username', '=', $username)->first();
        if (! $adminUser) {
            return $this->error(__('user.user_not_exist'));
        }
        // 验证密码
        if (! password_verify($password, $adminUser->password)) {
            return $this->error(__('user.password_error'));
        }
        new Monitor(__('user.admin_login'), false, $adminUser->user_id);
        $data = [];
        $data['refresh_token'] = token()->set($adminUser->user_id, TokenEnum::REFRESH_ADMIN);
        $data['token'] = token()->set($adminUser->user_id, TokenEnum::ADMIN);
        $data['id'] = $adminUser->user_id;

        return $this->success($data);
    }

    /**
     * 退出登录
     */
    public function logout(): JsonResponse
    {
        $user_id = customAuth('admin')->id();
        new Monitor(__('user.admin_logout'), false, $user_id);
        $user = AdminUserModel::find($user_id);
        if (! $user) {
            throw new HttpResponseException(['success' => false, 'msg' => __('user.user_not_exist')], 401);
        }
        token()->clear('admin', $user['user_id']);
        token()->clear('admin-refresh', $user['user_id']);

        return $this->success(__('user.logout_success'));
    }

    /**
     * 获取管理员信息
     */
    public function getAdminInfo(): JsonResponse
    {
        $info = customAuth('admin')->userInfo();
        // 权限
        $access = customAuth('admin')->permission();
        // 菜单
        $menus = AdminRuleModel::where('show', '=', 1)
            ->where('status', '=', 1)
            ->whereIn('key', $access)
            ->whereIn('type', [0, 1])
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        $menus = $this->getTreeData($menus, 'rule_id');

        return $this->success(compact('menus', 'access', 'info'));
    }

    /**
     * 修改密码
     */
    public function updatePassword(array $data): JsonResponse
    {
        $user_id = customAuth('admin')->id();
        $model = AdminUserModel::where('user_id', $user_id)->first();
        if (! $model) {
            return $this->error();
        }
        if (! password_verify($data['oldPassword'], $model->password)) {
            return $this->error(__('user.old_password_error'));
        }
        AdminUserModel::where('id', '=', $user_id)->update([
            'password' => password_hash($data['newPassword'], PASSWORD_DEFAULT),
        ]);

        return $this->success('ok');
    }

    /**
     * 修改管理员信息
     */
    public function updateAdmin(array $data): JsonResponse
    {
        $user_id = customAuth('admin')->id();
        AdminUserModel::where('user_id', $user_id)->update($data);

        return $this->success();
    }
}
