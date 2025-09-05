<?php

namespace App\Repositories\Sys;

use App\Models\Sys\SysUserModel;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class SysUserRepository extends Repository
{
    protected array $validation = [
        'username' => 'required|unique:sys_user,username',
        'nickname' => 'required',
        'sex' => 'required|in:0,1',
        'mobile' => 'required',
        'email' => 'required|email|unique:sys_user,email',
        'avatar_id' => 'required|integer',
        'dept_id' => 'required|exists:sys_dept,id',
        'status' => 'required|in:1,0',
        'password' => 'required|min:6',
        'rePassword' => 'required|same:password',
    ];

    protected array $messages = [
        'username.required' => '用户名不能为空',
        'username.unique' => '用户名已存在',
        'nickname.required' => '昵称不能为空',
        'sex.required' => '性别不能为空',
        'sex.in' => '性别格式错误',
        'mobile.required' => '手机号不能为空',
        'email.required' => '邮箱不能为空',
        'email.email' => '邮箱格式错误',
        'email.unique' => '邮箱已存在',
        'avatar_id.required' => '头像不能为空',
        'avatar_id.integer' => '头像格式错误',
        'dept_id.required' => '部门不能为空',
        'dept_id.exists' => '部门不存在',
        'status.required' => '状态不能为空',
        'status.in' => '状态格式错误',
        'password.required' => '密码不能为空',
        'password.min' => '密码至少6位',
        'rePassword.required' => '确认密码不能为空',
        'rePassword.same' => '两次密码不一致',
    ];

    protected array $searchField = [
        'dept_id' => '=',
        'status' => '=',
        'sex' => '='
    ];

    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];

    protected function model(): Builder
    {
        return SysUserModel::query();
    }

    /** ---------------- 自定义方法 ----------------------- */

    /** 获取系统活跃用户总数 */
    public function getActiveUsersCount(): int
    {
        return $this->model()->where('status', 1)->count();
    }

    /** 通过部门获取用户总数 */
    public function getDeptUserCount($deptId): int
    {
        return $this->model()->where('dept_id', $deptId)->count();
    }

    /**
     * 获取用户的所有权限 KEY
     */
    public function ruleKeys($id): array
    {
        return $this->model()->find($id)
            ->roles()->with('rules')->get()
            ->pluck('rules')
            ->flatten()
            ->pluck('key')
            ->unique()
            ->values()->toArray();
    }

    /**
     * 获取用户所有的权限
     * @param $id
     * @return array
     */
    public function rules($id): array
    {
        return $this->model()->find($id)
            ->roles()->with('rules')->get()
            ->pluck('rules')
            ->flatten()
            ->unique('id')
            ->toArray();
    }

}