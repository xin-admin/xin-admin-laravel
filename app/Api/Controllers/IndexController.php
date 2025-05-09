<?php

namespace App\Api\Controllers;

use App\Admin\Mail\VerificationCodeMail;
use App\Api\Requests\UserRegisterRequest;
use App\BaseController;
use App\Common\Models\XinUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

#[RequestMapping('/api')]
class IndexController extends BaseController
{
    // 权限验证白名单
    protected array $noPermission = ['index', 'login', 'register', 'mail'];

    #[GetMapping('/mail')]
    public function mail(): JsonResponse
    {
        Mail::to('hello@example.com')->send(new VerificationCodeMail(1234));
        return $this->success();
    }


    /**
     * index
     */
    #[GetMapping('/index')]
    #[OA\Get(path: '/api/index', description: '首页', tags: ['首页'], responses: [new OA\Response(response: 200, description: 'successful operation')])]
    public function index(): JsonResponse
    {
        $web_setting = setting('web');

        return $this->success(compact('web_setting'));
    }

    /**
     * 用户登录
     */
    #[PostMapping('/login')]
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ]);
        if (Auth::guard('users')->attempt($credentials)) {
            $data = $request->user()
                ->createToken($credentials['username'])
                ->toArray();
            return $this->success($data, __('user.login_success'));
        }
        return $this->error(__('user.login_error'));
    }

    /**
     * 用户注册
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
