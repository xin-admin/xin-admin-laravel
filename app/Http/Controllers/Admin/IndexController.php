<?php

namespace App\Http\Controllers\Admin;

use App\Attribute\Auth;
use App\Attribute\Monitor;
use App\Models\Admin\AdminGroupModel;
use App\Models\Admin\AdminModel;
use App\Models\Admin\AdminRuleModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Random\RandomException;
use Xin\Token;

class IndexController extends Controller
{
    public function index(): JsonResponse
    {
        $webSetting = get_setting('web');

        return $this->success(compact('webSetting'), '恭喜你已经成功安装 Xin Admin');
    }

    /**
     * 刷新 Token
     *
     * @throws RandomException
     */
    public function refreshToken(): JsonResponse
    {
        $token = request()->header('x-token');
        $reToken = request()->header('x-refresh-token');
        if ($reToken) {
            $Token = new Token;
            $Token->delete($token);
            $user_id = $Token->get($reToken)['user_id'];
            $token = md5(random_bytes(10));
            $Token->set($token, 'admin', $user_id);

            return $this->success(compact('token'));
        } else {
            return $this->error('请先登录！');
        }
    }

    /**
     * 登录
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->post();
        $validator = Validator::make($data, [
            'username' => 'required|min:4|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ], [
            'username.required' => '请填写您的姓名。',
            'username.min' => '账号至少需要 :min 个字符。',
            'password.required' => '密码不能为空。',
            'password.min' => '密码至少需要 :min 个字符。',
        ]);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        $model = new AdminModel;
        $data = $model->login($data['username'], $data['password']);
        if ($data) {
            new Monitor('管理员登录', false, $data['id']);

            return $this->success($data);
        }

        return $this->error($model->getErrorMsg());
    }

    /**
     * 退出登录
     */
    #[Auth]
    public function logout(): JsonResponse
    {
        $user_id = Auth::getAdminId();
        $admin = new AdminModel;
        if ($admin->logout($user_id)) {
            return $this->success('退出登录成功');
        } else {
            return $this->error($admin->getErrorMsg());
        }
    }

    /**
     * 获取管理员信息
     *
     * @throws Exception
     */
    #[Auth]
    public function getAdminInfo(): JsonResponse
    {
        $info = Auth::getAdminInfo();
        $group = AdminGroupModel::query()->find($info['group_id'])->rules;
        // 权限
        $access = AdminRuleModel::query()
            ->where('status', '=', 1)
            ->whereIn('id', $group)
            ->pluck('key')
            ->toArray();
        // 菜单
        $menus = AdminRuleModel::query()
            ->where('show', '=', 1)
            ->where('status', '=', 1)
            ->whereIn('type', [0, 1])
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        $menus = $this->getTreeData($menus);

        return $this->success(compact('menus', 'access', 'info'));
    }
}
