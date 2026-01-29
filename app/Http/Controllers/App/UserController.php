<?php

namespace App\Http\Controllers\App;

use App\Http\Requests\App\UserUpdateInfoRequest;
use App\Models\UserModel;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Providers\AnnoRoute\Route\GetRoute;
use App\Providers\AnnoRoute\Route\PostRoute;
use App\Providers\AnnoRoute\Route\PutRoute;
use App\Support\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[RequestAttribute('/api/user', authGuard: 'users')]
class UserController
{
    use RequestJson;
    protected array $noPermission = ['refreshToken'];

    #[GetRoute]
    public function getUserInfo(): JsonResponse
    {
        $info = auth()->user();
        return $this->success(compact('info'));
    }

    #[PostRoute('/logout')]
    public function logout(): JsonResponse
    {
        $user_id = auth('users')->id();
        $model = new UserModel;
        if ($model->logout($user_id)) {
            return $this->success('退出登录成功');
        } else {
            return $this->error($model->getErrorMsg());
        }
    }

    #[PutRoute]
    public function setUserInfo(UserUpdateInfoRequest $request): JsonResponse
    {
        UserModel::where('user_id', auth('user')->id())->update($request->validated());

        return $this->error('更新成功');
    }

    #[PostRoute('/setPwd')]
    public function setPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'oldPassword' => 'required|string|max:20',
            'newPassword' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:newPassword',
        ]);
        $user_id = auth('user')->id();
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
}
