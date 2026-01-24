<?php

namespace App\Services\Sys;

use App\Models\Sys\SysRuleModel;
use App\Models\Sys\SysUserModel;
use App\Services\BaseService;
use App\Support\Enum\FileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SysUserService extends BaseService
{

    /**
     * 系统用户登录
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|min:3|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ]);

        if (Auth::guard('sys_users')->attempt($credentials)) {
            $request->session()->regenerate();
            $userID = auth()->id();
            $access = $this->ruleKeys($userID);
            if($request->get('remember', false)) {
                $expiration = null;
            } else {
                $expiration = now()->addDays(3);
            }
            $data = $request->user()
                ->createToken($credentials['username'], $access, $expiration)
                ->toArray();
            if(empty($data['plainTextToken'])) {
                return $this->error(__('user.login_error'));
            }
            $response = [
                'token' => $data['plainTextToken']
            ];

            // 记录登录 IP 与 时间
            SysUserModel::query()->where('id', $userID)->update([
                'login_time' => now(),
                'login_ip' => $request->ip(),
            ]);
            return $this->success($response, __('user.login_success'));
        }
        return $this->error(__('user.login_error'));
    }

    /**
     * 获取管理员信息
     * @param int $id
     * @return array
     */
    public function getAdminMenus(int $id): array
    {
        if($id == 1) {
            $menus = SysRuleModel::query()
                ->where('status', 1)
                ->whereIn('type', ['menu','route','nested-route'])
                ->get()
                ->toArray();
        } else {
            $roles = SysUserModel::with(['roles.rules' => function ($query) {
                $query->where('status', 1)->whereIn('type', ['menu','route','nested-route']);
            }])->find($id)->roles->toArray();

            $menus = collect($roles)
                ->map(fn ($item) => $item['rules'])
                ->collapse()
                ->map(fn ($item) => collect($item)->forget(['pivot', 'updated_at', 'created_at', 'status']) )
                ->unique('id')
                ->toArray();
        }
        return $this->getTreeData($menus);
    }

    /**
     * 修改密码
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'oldPassword' => 'required|string|min:6|max:20',
            'newPassword' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:newPassword',
        ]);
        $user_id = auth()->id();
        $user = SysUserModel::find($user_id);
        if (! password_verify($validated['oldPassword'], $user->password)) {
            return $this->error(__('user.old_password_error'));
        }
        $user->password = Hash::make($validated['newPassword']);
        $user->save();
        return $this->success('ok');
    }

    /**
     * 重置管理员密码
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'required|exists:sys_user,id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ], [
            'id.required' => '请选择管理员用户！',
            'id.exists' => '管理员用户不存在！',
            'password.required' => '请输入管理员密码！',
            'password.min' => '密码最短为6个字符！',
            'password.max' => '密码最长伟20个字符！',
            'rePassword.required' => '请重复输入密码！',
            'rePassword.same' => '两次输入的密码不同！',
        ]);
        $user = SysUserModel::find($data['id']);
        if (!$user) {
            return $this->error(__('user.user_not_exist'));
        }
        $user->password = Hash::make($data['password']);
        $user->save();
        return $this->success('ok');
    }

    /**
     * 更新用户信息
     * @param int $id 用户ID
     * @param Request $request 请求
     * @return JsonResponse
     */
    public function updateInfo(int $id, Request $request): JsonResponse
    {
        $data = $request->validate([
            'nickname' => [
                'required',
                Rule::unique('sys_user', 'username')->ignore($id)
            ],
            'sex' => 'required|in:0,1',
            'bio' => 'sometimes|max:255',
            'mobile' => [
                'required',
                Rule::unique('sys_user', 'mobile')->ignore($id)
            ],
            'email' => [
                'required',
                Rule::unique('sys_user', 'email')->ignore($id)
            ],
        ], [
            'nickname.required' => '昵称不能为空',
            'sex.required' => '性别不能为空',
            'sex.in' => '性别格式错误',
            'bio.max' => '个人简介最大不能超过255个字符',
            'mobile.required' => '手机号不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式错误'
        ]);
        $model = SysUserModel::find($id);
        if (empty($model)) {
            return $this->error(__('user.user_not_exist'));
        }
        return $this->success($model->update($data));
    }

    /**
     * 更新用户头像
     */
    public function uploadAvatar(): JsonResponse
    {
        $service = new SysFileService();
        $data = $service->upload(FileType::IMAGE, 0); // 使用系统设置中的默认存储
        $user = SysUserModel::find(Auth::id());
        $user->avatar_id = $data['id'];
        $user->save();
        return $this->success($data);
    }

    /**
     * 获取用户的所有权限 KEY
     */
    public function ruleKeys(int $id): array
    {
        if($id == 1) {
            return SysRuleModel::query()
                ->where('status', 1)
                ->pluck('key')
                ->toArray();
        }
        $roles = SysUserModel::with(['roles.rules' => function ($query) {
            $query->where('status', 1); // 只获取启用的权限
        }])->find($id)->roles->toArray();

        return collect($roles)
            ->map(fn ($item) => $item['rules'] )
            ->collapse()
            ->map(fn ($item) => $item['key'] )
            ->unique()
            ->toArray();
    }

}
