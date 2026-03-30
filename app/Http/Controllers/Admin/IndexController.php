<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AdminUserUpdateRequest;
use App\Models\System\SysLoginRecordModel;
use App\Models\System\SysRuleModel;
use App\Models\System\SysUserModel;
use App\Services\Admin\SysFileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Xin\AnnoRoute\Attribute\GetRoute;
use Xin\AnnoRoute\Attribute\PostRoute;
use Xin\AnnoRoute\Attribute\PutRoute;
use Xin\AnnoRoute\Attribute\RequestAttribute;

#[RequestAttribute]
class IndexController extends BaseController
{

    /** 获取首页信息 */
    #[GetRoute('/index', false)]
    public function index(): JsonResponse
    {
        $web_setting = setting('web');
        return $this->success($web_setting);
    }

    /** 用户登录 */
    #[PostRoute('/admin/login', false, 'login_log')]
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|min:3|alphaDash',
            'password' => 'required|min:4|alphaDash',
        ]);

        if (Auth::guard('sys_users')->attempt($credentials)) {
            $userID = auth()->id();
            $user = SysUserModel::find($userID);
            $access = $user->access();
            if($request->input('remember', false)) {
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

            SysUserModel::query()->where('id', $userID)->update([
                'login_time' => now(),
                'login_ip' => $request->ip(),
            ]);
            return $this->success($response, __('user.login_success'));
        }
        return $this->error(__('user.login_error'));
    }

    /** 退出登录 */
    #[PostRoute('/admin/logout')]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(__('user.logout_success'));
    }

    /** 获取管理员信息 */
    #[GetRoute('/admin/info')]
    public function info(): JsonResponse
    {
        $info = Auth::user();
        $access = $info->access();
        return $this->success(compact('access','info'));
    }

    /** 获取菜单信息 */
    #[GetRoute('/admin/menu')]
    public function menu(): JsonResponse
    {
        $id = Auth::id();
        if($id == 1) {
            $menus = SysRuleModel::query()
                ->where('status', 1)
                ->whereIn('type', ['menu','route'])
                ->get()
                ->toArray();
        } else {
            $roles = SysUserModel::with(['roles.rules' => function ($query) {
                $query->where('status', 1)->whereIn('type', ['menu','route']);
            }])->find($id)->roles->toArray();

            $menus = collect($roles)
                ->map(fn ($item) => $item['rules'])
                ->collapse()
                ->map(fn ($item) => collect($item)->forget(['pivot', 'updated_at', 'created_at', 'status']) )
                ->unique('id')
                ->toArray();
        }
        $menus = getTreeData($menus);
        return $this->success(compact('menus'));
    }

    /** 更新管理员信息 */
    #[PutRoute('/admin/updateInfo')]
    public function updateInfo(AdminUserUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = auth()->id();
        $model = SysUserModel::find($id);
        if (empty($model)) {
            return $this->error(__('user.user_not_exist'));
        }
        return $this->success($model->update($data));
    }

    /** 修改密码 */
    #[PutRoute('/admin/updatePassword')]
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

    /** 上传头像 */
    #[PostRoute('/admin/uploadAvatar')]
    public function uploadAvatar(Request $request): JsonResponse
    {
        $user_id = Auth::id();
        $file = $request->file('file');
        $service = new SysFileService();
        $data = $service->upload($file, 2, 20, $user_id);
        $user = SysUserModel::find($user_id);
        $user->avatar_id = $data['id'];
        $user->save();
        return $this->success($data);
    }

    /** 获取管理员登录日志 */
    #[GetRoute('/admin/loginRecord')]
    public function loginRecord(): JsonResponse
    {
        $id = Auth::id();
        $data = SysLoginRecordModel::where('user_id', $id)
            ->limit(10)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        return $this->success($data);
    }

}
