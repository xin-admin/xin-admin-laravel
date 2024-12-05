<?php

namespace App\Service;

use App\Attribute\Auth;
use App\Attribute\Monitor;
use App\Models\Admin\AdminGroupModel;
use App\Models\Admin\AdminModel;
use App\Models\Admin\AdminRuleModel;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Random\RandomException;
use Xin\Token;

class SysAdminUserService
{
    use RequestJson;

    /**
     * 刷新Token
     */
    public function refreshToken(): JsonResponse
    {
        $token = request()->header('x-token');
        $reToken = request()->header('x-refresh-token');
        if ($reToken) {
            $Token = new Token;
            $Token->delete($token);
            $user_id = $Token->get($reToken)['user_id'];
            try {
                $token = md5(random_bytes(10));
            } catch (RandomException $e) {
                return $this->error('刷新Token失败：'.$e->getMessage());
            }
            $Token->set($token, 'admin', $user_id);

            return $this->success(compact('token'));
        } else {
            return $this->error('请先登录！');
        }
    }

    /**
     * 登录
     */
    public function login(string $username, string $password): JsonResponse
    {
        $model = new AdminModel;
        $data = $model->login($username, $password);
        if ($data) {
            new Monitor('管理员登录', false, $data['id']);

            return $this->success($data);
        }

        return $this->error($model->getErrorMsg());
    }

    /**
     * 退出登录
     */
    public function logout(): JsonResponse
    {
        $user_id = Auth::getAdminId();
        $admin = new AdminModel;
        if ($admin->logout($user_id)) {
            return $this->success('退出登录成功');
        } else {
            return $this->error($admin->getErrorMsg());
        }
    }

    /**
     * 获取管理员信息
     */
    public function getAdminInfo(): JsonResponse
    {
        $info = Auth::getAdminInfo();
        $group = AdminGroupModel::query()->find($info['group_id'])->rules;
        // 权限
        $access = AdminRuleModel::query()
            ->where('status', '=', 1)
            ->whereIn('id', $group)
            ->pluck('key')
            ->toArray();
        // 菜单
        $menus = AdminRuleModel::query()
            ->where('show', '=', 1)
            ->where('status', '=', 1)
            ->whereIn('type', [0, 1])
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        $menus = getTreeData($menus);

        return $this->success(compact('menus', 'access', 'info'));
    }

    /**
     * 修改密码
     */
    public function updatePassword(array $data): JsonResponse
    {
        $user_id = Auth::getAdminId();
        $model = AdminModel::query()->find($user_id);
        if (! password_verify($data['oldPassword'], $model->password)) {
            return $this->error('旧密码不正确');
        }
        $model->password = password_hash($data['newPassword'], PASSWORD_DEFAULT);
        $model->save();

        return $this->success('ok');
    }

    /**
     * 修改管理员信息
     */
    public function updateAdmin(array $data): JsonResponse
    {
        $user_id = Auth::getAdminId();
        AdminModel::query()->find($user_id)->update($data);

        return $this->success();
    }
}
