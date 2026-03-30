<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SysUserFormRequest;
use App\Models\System\SysUserModel;
use App\Services\Admin\SysLoginRecordService;
use App\Services\Admin\SysUserDeptService;
use App\Services\Admin\SysUserRoleService;
use App\Services\Admin\SysUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\DeleteRoute;
use Xin\AnnoRoute\Route\GetRoute;
use Xin\AnnoRoute\Route\PostRoute;
use Xin\AnnoRoute\Route\PutRoute;

/**
 * 管理员用户控制器
 */
#[RequestAttribute('/system/user', 'system.user')]
class SysUserController extends BaseController
{
    public function __construct(
        protected SysUserService        $service,
        private readonly SysUserModel   $model,
        protected SysUserRoleService    $roleService,
        protected SysUserDeptService    $deptService,
        protected SysLoginRecordService $loginRecordService,
    ) {}

    /** 查询管理员用户列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $pageSize = $params['pageSize'] ?? 10;
        $query = $this->model::query();
        $data = $this->buildSearch($params, $query)
            ->paginate($pageSize)
            ->toArray();
        return $this->success($data);
    }

    /** 创建管理员用户 */
    #[PostRoute(authorize: 'create')]
    public function create(SysUserFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $this->model->create([
            'username' => $validated['username'],
            'nickname' => $validated['nickname'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'] ?? 1,
            'dept_id' => $validated['dept_id'] ?? null,
            'sex' => $validated['sex'] ?? 0
        ]);
        if(empty($user)) {
            return $this->error();
        }
        $user->roles()->sync($data['role_id'] ?? []);
        return $this->success();
    }

    /** 编辑管理员用户 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysUserFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysUserModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->roles()->sync($data['role_id'] ?? []);
        $model->update($validated);
        return $this->success();
    }

    /** 删除管理员用户 */
    #[DeleteRoute(
        route: '/{id}',
        authorize: 'delete',
        where: ['id' => '[0-9]+']
    )]
    public function delete(int $id): JsonResponse
    {
        if($id == 1) {
            $this->error('不能删除系统用户！');
        }
        $user = SysUserModel::find($id);
        if (empty($user)) {
            $this->error('Model not found');
        }
        $user->roles()->detach();
        $user->delete();
        return $this->success();
    }

    /** 重置用户密码 */
    #[PutRoute('/resetPassword', 'resetPassword')]
    public function resetPassword(Request $request): JsonResponse
    {
        return $this->service->resetPassword($request);
    }

    /** 获取用户角色选项栏数据 */
    #[GetRoute('/role', 'role')]
    public function role(): JsonResponse
    {
        return $this->success($this->roleService->getRoleFields());
    }

    /** 获取用户部门选项栏数据 */
    #[GetRoute('/dept', 'dept')]
    public function dept(): JsonResponse
    {
        return $this->success($this->deptService->getDeptField());
    }

    /** 用户登录 */
    #[PostRoute('/login', false, 'login_log')]
    public function login(Request $request, SysUserService $service): JsonResponse
    {
        return $service->login($request);
    }

    /** 退出登录 */
    #[PostRoute('/logout')]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(__('user.logout_success'));
    }

    /** 获取管理员信息 */
    #[GetRoute('/info')]
    public function info(): JsonResponse
    {
        $info = Auth::user();
        $id = Auth::id();
        $access = $this->service->ruleKeys($id);
        return $this->success(compact('access','info'));
    }

    /** 获取菜单信息 */
    #[GetRoute('/menu')]
    public function menu(): JsonResponse
    {
        $id = Auth::id();
        $menus = $this->service->getAdminMenus($id);
        return $this->success(compact('menus'));
    }

    /** 更新管理员信息 */
    #[PutRoute('/updateInfo')]
    public function updateInfo(Request $request): JsonResponse
    {
        return $this->service->updateInfo(Auth::id(), $request);
    }

    /** 修改密码 */
    #[PutRoute('/updatePassword')]
    public function updatePassword(Request $request): JsonResponse
    {
        return $this->service->updatePassword($request);
    }

    /** 上传头像 */
    #[PostRoute('/uploadAvatar')]
    public function uploadAvatar(Request $request): JsonResponse
    {
        $user_id = Auth::id();
        $file = $request->file('file');
        return $this->service->uploadAvatar($file, $user_id);
    }

    /** 获取管理员登录日志 */
    #[GetRoute('/loginRecord')]
    public function loginRecord(): JsonResponse
    {
        $id = Auth::id();
        $data = $this->loginRecordService->getRecordByID($id);
        return $this->success($data);
    }
}
