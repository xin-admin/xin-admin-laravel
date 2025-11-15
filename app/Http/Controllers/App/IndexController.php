<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\BaseController;
use App\Http\Requests\App\UserRegisterRequest;
use App\Models\UserModel;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Support\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

#[RequestMapping('/api', authGuard: 'users')]
class IndexController
{
    use RequestJson;
    // 权限验证白名单
    protected array $noPermission = ['index', 'login', 'register', 'mail'];

    /** 获取首页信息 */
    #[GetMapping('/index')]
    public function index(): JsonResponse
    {
        $web_setting = setting('web');

        return $this->success(compact('web_setting'));
    }

    /** 用户登录 */
    #[PostMapping('/login')]
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ]);
        if (Auth::guard('users')->attempt($credentials, true)) {
            $data = $request->user('users')
                ->createToken($credentials['username'])
                ->toArray();
            return $this->success($data, __('user.login_success'));
        }
        return $this->error(__('user.login_error'));
    }

    /** 用户注册 */
    #[PostMapping('/register')]
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $model = new UserModel;
        $model->username = $data['username'];
        $model->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $model->email = $data['email'];
        if ($model->save()) {
            return $this->success();
        }

        return $this->error('创建用户失败');
    }
}
