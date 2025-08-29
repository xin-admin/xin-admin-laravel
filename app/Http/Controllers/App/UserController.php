<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\BaseController;
use App\Http\Requests\App\UserUpdateInfoRequest;
use App\Models\XinUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

#[RequestMapping('/api/user')]
class UserController extends BaseController
{
    protected array $noPermission = ['refreshToken'];

    #[GetMapping]
    public function getUserInfo(): JsonResponse
    {
        $info = auth('user')->userInfo();

        return $this->success(compact('info'));
    }

    #[PostMapping('/logout')]
    public function logout(): JsonResponse
    {
        $user_id = auth('user')->id();
        $model = new XinUserModel;
        if ($model->logout($user_id)) {
            return $this->success('退出登录成功');
        } else {
            return $this->error($model->getErrorMsg());
        }
    }

    #[PutMapping]
    public function setUserInfo(UserUpdateInfoRequest $request): JsonResponse
    {
        XinUserModel::where('user_id', auth('user')->id())->update($request->validated());

        return $this->error('更新成功');
    }

    #[PostMapping('/setPwd')]
    public function setPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'oldPassword' => 'required|string|max:20',
            'newPassword' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:newPassword',
        ]);
        $user_id = auth('user')->id();
        $user = XinUserModel::query()->find($user_id);
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
