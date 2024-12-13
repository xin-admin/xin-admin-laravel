<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 系统部门模型
 */
class SysDeptModel extends Model
{
    protected $table = 'sys_dept';

    protected $primaryKey = 'dept_id';

    protected $fillable = [
        'parent_id',
        'ancestors',
        'dept_name',
        'order_num',
        'leader',
        'phone',
        'email',
        'status',
    ];
}
