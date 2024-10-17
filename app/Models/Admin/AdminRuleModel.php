<?php
namespace App\Models\Admin;

use App\Models\BaseModel;

/**
 * 管理员权限模型
 */
class AdminRuleModel extends BaseModel
{
    protected $table = 'admin_rule';

    protected $fillable = ['pid', 'type', 'sort', 'name', 'path', 'icon', 'key', 'locale', 'status', 'show'];

}
