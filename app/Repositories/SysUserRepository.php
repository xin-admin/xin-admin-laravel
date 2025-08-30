<?php

namespace App\Repositories;

use App\Models\AdminUserModel;

class SysUserRepository extends BaseRepository
{

    /** @var string 模型 */
    protected string $model = AdminUserModel::class;

    /** @var array|string[] 验证规则 */
    protected array $validation = [
        'username' => 'required',
        'nickname' => 'required',
        'sex' => 'required',
        'mobile' => 'required',
        'email' => 'required|email',
        'avatar_id' => 'required|exists:file,file_id',
        'role_id' => 'required|exists:admin_role,role_id',
        'dept_id' => 'required|exists:admin_dept,dept_id',
        'status' => 'required|in:1,0',
        'password' => 'required',
        'rePassword' => 'required|same:password',
    ];

    /** @var array 验证消息 */
    protected array $messages = [];

    /** @var array|string[] 搜索字段 */
    protected array $searchField = [
        'dept_id' => '='
    ];

    /** @var array|string[] 快速搜索字段 */
    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'user_id'];

    /** 以下可以自定义储存方法 */

}