<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SysUserFormRequest;
use App\Models\System\SysDeptModel;
use App\Models\System\SysRoleModel;
use App\Models\System\SysUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Xin\AnnoRoute\Attribute\DeleteRoute;
use Xin\AnnoRoute\Attribute\GetRoute;
use Xin\AnnoRoute\Attribute\PostRoute;
use Xin\AnnoRoute\Attribute\PutRoute;
use Xin\AnnoRoute\Attribute\RequestAttribute;

/**
 * 管理员用户控制器
 */
#[RequestAttribute('/system/user', 'system.user')]
class SysUserController extends BaseController
{

    /** 查询管理员用户列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $pageSize = $params['pageSize'] ?? 10;
        $query = SysUserModel::query();
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
        $user = SysUserModel::create([
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

    /** 获取用户角色选项栏数据 */
    #[GetRoute('/role', 'role')]
    public function role(): JsonResponse
    {
        $data = SysRoleModel::where('status', 1)
            ->get(['id as role_id', 'name'])
            ->toArray();
        return $this->success($data);
    }

    /** 获取用户部门选项栏数据 */
    #[GetRoute('/dept', 'dept')]
    public function dept(): JsonResponse
    {
        $field = SysDeptModel::where('status', 0)
            ->select(['id as dept_id', 'name', 'parent_id'])
            ->get()
            ->toArray();
        $data = $this->buildTree($field);

        return $this->success($data);
    }

    private function buildTree(array $items, $parentId = 0): array
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildTree($items, $item['dept_id']);
                $node = [
                    'dept_id' => $item['dept_id'],
                    'name' => $item['name'],
                    'children' => $children
                ];
                $tree[] = $node;
            }
        }
        return $tree;
    }
}
