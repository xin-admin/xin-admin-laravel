<?php

namespace App\Models\User;

use App\Models\BaseModel;

/**
 * 用户权限模型
 */
class UserRuleModel extends BaseModel
{
    protected $table = 'user_rule';

    protected $fillable = ['pid', 'type', 'sort', 'name', 'path', 'icon', 'key', 'locale', 'status', 'show'];
}
