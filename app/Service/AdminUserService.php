<?php

namespace App\Service;

use App\Attribute\Monitor;
use App\Exceptions\HttpResponseException;
use App\Models\AdminRuleModel;
use App\Models\AdminUserModel;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Random\RandomException;
use Xin\Token;

class AdminUserService
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
            $tokenServer = new Token;
            $tokenServer->delete($token);
            $user_id = $tokenServer->get($reToken)['user_id'];
            try {
                $token = md5(random_bytes(10));
            } catch (RandomException $e) {
                return $this->error('刷新Token失败：'.$e->getMessage());
            }
            $tokenServer->set($token, 'admin', $user_id);

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
        $adminUser = AdminUserModel::where('username', '=', $username)->first();
        if (! $adminUser) {
            return $this->error('用户不存在');
        }
        // 验证密码
        if (! password_verify($password, $adminUser->password)) {
            return $this->error('密码错误');
        }
        new Monitor('管理员登录', false, $adminUser->user_id);
        $token = $this->getToken($adminUser->user_id);

        return $this->success($token);
    }

    /**
     * 退出登录
     */
    public function logout(): JsonResponse
    {
        $user_id = static::getAdminUserId();
        new Monitor('管理员退出登录', false, $user_id);
        $user = AdminUserModel::find($user_id);
        if (! $user) {
            throw new HttpResponseException(['success' => false, 'msg' => '管理员用户不存在'], 401);
        }
        $token = new Token;
        $token->clear('admin', $user['id']);
        $token->clear('admin-refresh', $user['id']);

        return $this->success('退出登录成功');
    }

    /**
     * 获取管理员信息
     */
    public function getAdminInfo(): JsonResponse
    {
        $info = static::getAdminUserInfo();
        // 权限
        $access = $info['rules'];
        // 菜单
        $menus = AdminRuleModel::where('show', '=', 1)
            ->where('status', '=', 1)
            ->whereIn('key', $access)
            ->whereIn('type', [0, 1])
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        $menus = $this->getTreeData($menus);

        return $this->success(compact('menus', 'access', 'info'));
    }

    /**
     * 修改密码
     */
    public function updatePassword(array $data): JsonResponse
    {
        $user_id = static::getAdminUserId();
        $model = AdminUserModel::where('user_id', $user_id)->first();
        if (! $model) {
            return $this->error();
        }
        if (! password_verify($data['oldPassword'], $model->password)) {
            return $this->error('旧密码不正确');
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
        $user_id = static::getAdminUserId();
        AdminUserModel::where('user_id', $user_id)->update($data);

        return $this->success();
    }

    /**
     * 获取 Token
     */
    private function getToken(int $user_id): array|false
    {
        try {
            $token = new Token;
            $data = [];
            $data['refresh_token'] = md5(random_bytes(10));
            $data['token'] = md5(random_bytes(10));
            $data['id'] = $user_id;
            if (
                $token->set($data['token'], 'admin', $user_id, 600) &&
                $token->set($data['refresh_token'], 'admin-refresh', $user_id, 2592000)
            ) {
                return $data;
            } else {
                $this->throwError('token 生成失败');

                return false;
            }
        } catch (\Exception $e) {
            $this->throwError($e->getMessage());

            return false;
        }
    }

    /**
     * 获取用户ID
     */
    public static function getAdminUserId(): int
    {
        $token = request()->header('x-token');
        $tokenData = (new Token)->get($token);
        if (! $tokenData) {
            throw new HttpResponseException(['success' => false, 'msg' => '请先登录!'], 401);
        }
        if ($tokenData['type'] != 'admin' || ! isset($tokenData['user_id'])) {
            throw new HttpResponseException(['success' => false, 'msg' => '管理员用户不存在'], 401);
        }

        return (int) $tokenData['user_id'];
    }

    /**
     * 获取管理员信息
     */
    public static function getAdminUserInfo(): array
    {
        $user_id = static::getAdminUserId();
        $adminUser = AdminUserModel::find($user_id);
        if (! $adminUser) {
            throw new HttpResponseException(['success' => false, 'msg' => '管理员用户不存在'], 401);
        }

        return $adminUser->toArray();
    }

    private function getTreeData(&$list, int $parentId = 0): array
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->getTreeData($list, $item['rule_id']);
                ! empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }

        return $data;
    }
}
