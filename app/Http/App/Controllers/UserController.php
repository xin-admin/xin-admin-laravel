<?php

namespace App\Http\App\Controllers;

use App\Attribute\ApiController;
use App\Attribute\Auth;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Enum\FileType;
use App\Http\App\Requests\UserSetPasswordRequest;
use App\Http\App\Requests\UserUpdateInfoRequest;
use App\Http\BaseController;
use App\Models\User\UserGroupModel;
use App\Models\User\UserModel;
use App\Models\User\UserMoneyLogModel;
use App\Models\User\UserRuleModel;
use Illuminate\Http\JsonResponse;
use Xin\File;
use Xin\Token;

#[RequestMapping('/api/user')]
#[ApiController]
class UserController extends BaseController
{
    protected array $noPermission = ['refreshToken'];

    #[GetMapping]
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
        $menus = getTreeData($menus);

        return $this->success(compact('info', 'access', 'menus'));
    }

    #[PostMapping('/refreshToken')]
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

    #[PostMapping('/logout')]
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

    #[PostMapping('/upAvatar')]
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

    #[PutMapping]
    public function setUserInfo(UserUpdateInfoRequest $request): JsonResponse
    {
        $data = $request->validated();
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

    #[PostMapping('/setPwd')]
    public function setPassword(UserSetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
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

    #[GetMapping('/getMoneyLog')]
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
