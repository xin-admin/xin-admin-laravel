<?php

namespace App\Http\App\Controllers;

use App\Attribute\ApiController;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\RequestMapping;
use App\Http\App\Requests\UserLoginRequest;
use App\Http\App\Requests\UserRegisterRequest;
use App\Http\BaseController;
use App\Models\XinUserModel;
use Illuminate\Http\JsonResponse;

#[ApiController]
#[RequestMapping('/api')]
class IndexController extends BaseController
{
    // 权限验证白名单
    protected array $noPermission = ['index', 'login', 'register'];

    /**
     * index
     * @return JsonResponse
     */
    #[GetMapping('/index')]
    public function index(): JsonResponse
    {
        $web_setting = get_setting('web');

        return $this->success(compact('web_setting'));
    }

    /**
     * 用户登录
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    #[PostMapping('/login')]
    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $model = new XinUserModel();
        $data = $model->login($data['username'], $data['password']);
        if ($data) {
            return $this->success($data);
        }

        return $this->error($model->getErrorMsg());
    }

    /**
     * 用户注册
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    #[PostMapping('/register')]
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $model = new XinUserModel;
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
