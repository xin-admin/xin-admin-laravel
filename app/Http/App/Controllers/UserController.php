<?php

namespace App\Http\App\Controllers;

use App\Attribute\AppController;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Enum\FileType;
use App\Enum\TokenEnum;
use App\Http\App\Requests\UserSetPasswordRequest;
use App\Http\App\Requests\UserUpdateInfoRequest;
use App\Http\BaseController;
use App\Models\XinBalanceRecordModel;
use App\Models\XinUserModel;
use Illuminate\Http\JsonResponse;
use Xin\File;

#[RequestMapping('/api/user')]
#[AppController]
class UserController extends BaseController
{
    protected array $noPermission = ['refreshToken'];

    #[GetMapping]
    public function getUserInfo(): JsonResponse
    {
        $info = customAuth('app')->userInfo();

        return $this->success(compact('info'));
    }

    #[PostMapping('/refreshToken')]
    public function refreshToken(): JsonResponse
    {
        $token = request()->header('x-user-token');
        $reToken = request()->header('x-user-refresh-token');
        if (request()->method() == 'POST' && $reToken) {
            token()->delete($token);
            $user_id = token()->get($reToken)['user_id'];
            $token = token()->set($user_id, TokenEnum::USER);

            return $this->success(compact('token'));
        } else {
            return $this->error('请先登录！');
        }
    }

    #[PostMapping('/logout')]
    public function logout(): JsonResponse
    {
        $user_id = customAuth('app')->id();
        $model = new XinUserModel;
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
            customAuth('app')->id(),
            10
        );

        return $this->success(['fileInfo' => $fileData], '图片上传成功');
    }

    #[PutMapping]
    public function setUserInfo(UserUpdateInfoRequest $request): JsonResponse
    {
        XinUserModel::where('user_id', customAuth('app')->id())->update($request->validated());

        return $this->error('更新成功');
    }

    #[PostMapping('/setPwd')]
    public function setPassword(UserSetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user_id = customAuth('app')->id();
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

    #[GetMapping('/getMoneyLog')]
    public function getMoneyLog(): JsonResponse
    {
        $user_id = customAuth('app')->id();
        $params = request()->query();
        $paginate = [
            'list_rows' => $params['pageSize'] ?? 10,
            'page' => $params['current'] ?? 1,
        ];
        $list = XinBalanceRecordModel::query()
            ->where('user_id', $user_id)
            ->paginate($paginate)
            ->toArray();

        return $this->success($list);
    }
}
