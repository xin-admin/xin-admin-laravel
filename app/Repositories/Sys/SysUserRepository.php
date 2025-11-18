<?php

namespace App\Repositories\Sys;

use App\Exceptions\RepositoryException;
use App\Models\Sys\SysUserModel;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SysUserRepository extends BaseRepository
{

    protected array $searchField = [
        'id' => '=',
        'username' => 'like',
        'nickname' => 'like',
        'email' => 'like',
        'dept_id' => '=',
        'mobile' => 'like',
    ];

    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];

    protected function model(): Builder
    {
        return SysUserModel::query();
    }

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
        $model = $this->model()->create([
            'username' => $data['username'],
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
            'status' => $data['status'] ?? 1,
            'dept_id' => $data['dept_id'] ?? null,
            'sex' => $data['sex'] ?? 0,
            'remember_token' => Str::random(10),
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
}