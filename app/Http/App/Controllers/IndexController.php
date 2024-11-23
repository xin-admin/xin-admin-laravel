<?php

namespace App\Http\App\Controllers;

use App\Attribute\ApiController;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\RequestMapping;
use App\Http\App\Requests\UserLoginRequest;
use App\Http\App\Requests\UserRegisterRequest;
use App\Http\BaseController;
use App\Models\User\UserGroupModel;
use App\Models\User\UserModel;
use App\Models\User\UserRuleModel;
use Illuminate\Http\JsonResponse;

#[ApiController]
#[RequestMapping('/api')]
class IndexController extends BaseController
{

    protected array $noPermission = ['index', 'login', 'register'];

    #[GetMapping('/index')]
    public function index(): JsonResponse
    {
        $group = UserGroupModel::query()->find(2)->toArray();
        $menus = UserRuleModel::query()
            ->whereIn('id', $group['rules'])
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        $menus = getTreeData($menus);
        $web_setting = get_setting('web');
        return $this->success(compact('web_setting', 'menus'));
    }

    #[PostMapping('/login')]
    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $model = new UserModel();
        $data = $model->login($data['username'], $data['password']);
        if ($data) {
            return $this->success($data);
        }
        return $this->error($model->getErrorMsg());
    }

    #[PostMapping('/register')]
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $model = new UserModel();
        $model->username = $data['username'];
        $model->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $model->email = $data['email'];
        $model->mobile = $data['mobile'];
        if ($model->save()) {
            return $this->success();
        }
        return $this->error('创建用户失败');
    }
}
