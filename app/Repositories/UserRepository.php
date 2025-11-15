<?php

namespace App\Repositories;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends BaseRepository
{
    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'user_id'];

    protected function rules(): array
    {
        return [
            'username' => 'required|string|max:20|unique:user,username',
            'nickname' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:50|unique:user,email',
        ];
    }

    protected function messages(): array
    {
        return [
            'username.required' => '用户名不能为空',
            'username.string' => '用户名必须是字符串',
            'username.max' => '用户名不能超过20个字符',
            'username.unique' => '用户名已存在',

            'nickname.string' => '昵称必须是字符串',
            'nickname.max' => '昵称不能超过20个字符',

            'email.email' => '邮箱格式不正确',
            'email.max' => '邮箱不能超过50个字符',
            'email.unique' => '邮箱已被注册',
        ];
    }

    protected function model(): Builder
    {
        return UserModel::query();
    }
}