<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\User\UserGroupModel;
use App\Models\User\UserModel;
use App\Models\User\UserRuleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexController extends BaseController
{
    public function index(): JsonResponse
    {
        $group = UserGroupModel::query()->find(2)->toArray();
        $menus = UserRuleModel::query()
            ->whereIn('id', $group['rules'])
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        $menus = $this->getTreeData($menus);
        $web_setting = get_setting('web');

        return $this->success(compact('web_setting', 'menus'));
    }

    /**
     * 用户登录
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->post();
        $validator = Validator::make($data, [
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ], [
            'username.required' => '请填写用户名。',
            'username.min' => '账号至少需要 :min 个字符。',
            'password.required' => '密码不能为空。',
            'password.min' => '密码至少需要 :min 个字符。',
        ]);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        $model = new UserModel();
        $data = $model->login($data['username'], $data['password']);
        if ($data) {
            return $this->success($data);
        }

        return $this->error($model->getErrorMsg());
    }

    /**
     * 注册用户
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->post();
        $validator = Validator::make($data, [
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
            'rePassword' => 'required|min:4|same:password',
            'email' => 'required|email',
            'mobile' => 'required',
        ], [
            'username.required' => '请填写用户名。',
            'username.min' => '账号至少需要 :min 个字符。',
            'password.required' => '密码不能为空。',
            'password.min' => '密码至少需要 :min 个字符。',
            'rePassword.required' => '请重复输入密码',
            'email.required' => '请输入邮箱',
            'email.email' => '邮箱格式不正确',
            'mobile.required' => '请输入手机号'
        ]);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
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
