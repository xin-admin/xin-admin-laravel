<?php

namespace App\Http\Controllers\Api;

use App\Attribute\Auth;
use App\Enum\FileType;
use App\Http\Controllers\BaseController;
use App\Models\User\UserGroupModel;
use App\Models\User\UserModel;
use App\Models\User\UserMoneyLogModel;
use App\Models\User\UserRuleModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Xin\File;
use Xin\Token;

class UserController extends BaseController
{
    /**
     * 获取用户信息
     */
    #[Auth]
    public function getUserInfo(): JsonResponse
    {
        $info = Auth::getUserInfo();
        $group = UserGroupModel::query()->find($info['group_id'])->rules;
        // 权限
        $access = UserRuleModel::query()
            ->where('status', '=', 1)
            ->whereIn('id', $group)
            ->pluck('key')
            ->toArray();
        // 菜单
        $menus = UserRuleModel::query()
            ->where('show', '=', 1)
            ->whereIn('type', [0, 1])
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        $menus = $this->getTreeData($menus);

        return $this->success(compact('info', 'access', 'menus'));
    }

    /**
     * 刷新 Token
     *
     * @throws Exception
     */
    public function refreshToken(): JsonResponse
    {
        $token = request()->header('x-user-token');
        $reToken = request()->header('x-user-refresh-token');
        if (request()->method() == 'POST' && $reToken) {
            $Token = new Token;
            $Token->delete($token);
            $user_id = $Token->get($reToken)['user_id'];
            $token = md5(random_bytes(10));
            $Token->set($token, 'user', $user_id);

            return $this->success(compact('token'));
        } else {
            return $this->error('请先登录！');
        }
    }

    /**
     * 退出登录
     */
    #[Auth]
    public function logout(): JsonResponse
    {
        $user_id = Auth::getUserId();
        $model = new UserModel;
        if ($model->logout($user_id)) {
            return $this->success('退出登录成功');
        } else {
            return $this->error($model->getErrorMsg());
        }
    }

    /**
     * 头像上传接口
     *
     * @throws Exception
     */
    #[Auth]
    public function upAvatar(): JsonResponse
    {
        $storage = new File;
        $fileData = $storage->upload(
            FileType::IMAGE->value,
            'public',
            0,
            Auth::getUserId(),
            10
        );

        return $this->success(['fileInfo' => $fileData], '图片上传成功');
    }

    /**
     * 设置用户信息
     */
    #[Auth]
    public function setUserInfo(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4|max:20',
            'nickname' => 'required|min:4|max:20',
            'gender' => 'required',
            'email' => 'required|email',
            'avatar_id' => 'required|integer',
            'mobile' => 'required|regex:/^1[34578]\d{9}$/',
        ]);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        $user = UserModel::query()->find(Auth::getUserId());
        $user->nickname = $request->get('nickname');
        $user->gender = $request->get('gender');
        $user->email = $request->get('email');
        $user->mobile = $request->get('mobile');
        $user->username = $request->get('username');
        $user->avatar_id = $request->get('avatar_id');
        if ($user->save()) {
            return $this->success('更新成功');
        }

        return $this->error('更新失败');
    }

    /**
     * 设置密码
     */
    #[Auth]
    public function setPassword(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'oldPassword' => 'required|string|max:20',
            'newPassword' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:newPassword',
        ]);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        $user_id = Auth::getUserId();
        $user = UserModel::query()->find($user_id);
        if (! password_verify($data['oldPassword'], $user['password'])) {
            return $this->error('旧密码不正确！');
        }
        $user->password = password_hash($data['newPassword'], PASSWORD_DEFAULT);
        if ($user->save()) {
            return $this->success('更新成功');
        }
        return $this->error('更新失败');
    }

    /**
     * 获取用户余额记录
     */
    #[Auth]
    public function getMoneyLog(): JsonResponse
    {
        $user_id = Auth::getUserId();
        $params = request()->query();
        $paginate = [
            'list_rows' => $params['pageSize'] ?? 10,
            'page' => $params['current'] ?? 1,
        ];
        $list = UserMoneyLogModel::query()
            ->where('user_id', $user_id)
            ->paginate($paginate)
            ->toArray();

        return $this->success($list);
    }
}
