<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 系统权限模型
 */
class SysRuleModel extends Model
{
    protected $table = 'sys_rule';

    protected $primaryKey = 'rule_id';

    protected $fillable = [
        'parent_id',
        'type',
        'sort',
        'name',
        'path',
        'icon',
        'key',
        'locale',
        'status',
        'show',
    ];

}
