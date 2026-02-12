<?php

namespace App\Admin\Services;

use App\Common\Enum\FileType;
use App\Common\Exceptions\RepositoryException;
use App\Common\Models\System\SysRuleModel;
use App\Common\Models\System\SysUserModel;
use App\Common\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SysUserService extends BaseService
{
    protected SysUserModel $model;
    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];
    protected array $searchField = [
        'id' => '=',
        'username' => 'like',
        'nickname' => 'like',
        'email' => 'like',
        'dept_id' => '=',
        'mobile' => 'like',
    ];

    protected function rules(): array
    {
        if(!$this->isUpdate()) {
            return [
                'username' => 'required|unique:sys_user,username',
                'nickname' => 'required',
                'sex' => 'in:0,1',
                'mobile' => 'required',
                'email' => 'required|email|unique:sys_user,email',
                'dept_id' => 'required|exists:sys_dept,id',
                'role_id' => 'required|array|exists:sys_role,id',
                'status' => 'required|int|in:1,0',
                'password' => 'required|min:6',
                'rePassword' => 'required|same:password',
            ];
        } else {
            $id = request()->route('id');
            return [
                'username' => [
                    'required',
                    Rule::unique('sys_user', 'username')->ignore($id)
                ],
                'nickname' => 'required',
                'sex' => 'in:0,1',
                'mobile' => 'required',
                'email' => [
                    'required',
                    Rule::unique('sys_user', 'email')->ignore($id)
                ],
                'role_id' => 'required|array|exists:sys_role,id',
                'dept_id' => 'required|exists:sys_dept,id',
                'status' => 'required|int|in:1,0',
            ];
        }
    }

    protected function messages(): array
    {
        return [
            'username.required' => '用户名不能为空',
            'username.unique' => '用户名已存在',
            'nickname.required' => '昵称不能为空',
            'sex.in' => '性别格式错误',
            'mobile.required' => '手机号不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式错误',
            'email.unique' => '邮箱已存在',
            'dept_id.exists' => '部门不存在',
            'role_id.exists' => '角色不存在',
            'status.required' => '状态不能为空',
            'status.int' => '状态值错误',
            'status.in' => '状态格式错误',
            'password.required' => '密码不能为空',
            'password.min' => '密码至少6位',
            'rePassword.required' => '确认密码不能为空',
            'rePassword.same' => '两次密码不一致',
        ];
    }

    /** ---------------- 自定义方法 ----------------------- */
    /** 新增系统用户 */
    public function create(array $data): bool
    {
        $data = $this->validation($data);
        if(empty($data)) {
            throw new RepositoryException('Validation failed: empty data');
        }
        $model = $this->model->create([
            'username' => $data['username'],
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
            'status' => $data['status'] ?? 1,
            'dept_id' => $data['dept_id'] ?? null,
            'sex' => $data['sex'] ?? 0
        ]);
        if(empty($model)) {
            throw new RepositoryException('Create failed');
        }
        $model->roles()->sync($data['role_id'] ?? []);
        return true;
    }

    /** 修改系统用户 */
    public function update(int $id, array $data): bool
    {
        $validated = $this->validation($data);
        $model = SysUserModel::find($id);
        if (empty($model)) {
            throw new RepositoryException('Model not found');
        }
        $model->roles()->sync($data['role_id'] ?? []);
        return $model->update($validated);
    }

    /** 删除系统用户 */
    public function delete(int $id): bool
    {
        if($id == 1) {
            throw new RepositoryException('不能删除系统用户！');
        }
        $user = SysUserModel::find($id);
        if (empty($user)) {
            throw new RepositoryException('Model not found');
        }
        $user->roles()->detach();
        return $user->delete();
    }

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