<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 系统用户部门模型
 */
class SysDeptModel extends Model
{
    use SoftDeletes;
    protected $table = 'sys_dept';
    protected $primaryKey = 'id';

    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'type',
        'sort',
        'phone',
        'email',
        'status',
        'address',
        'remark'
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
    ];

    protected $hidden = [ 'deleted_at' ];

    /**
     * 定义与用户的关联关系（一个部门有多个用户）
     */
    public function users(): HasMany
    {
        return $this->hasMany(SysUserModel::class, 'dept_id', 'id');
    }

    /**
     * 定义父级部门关联
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(SysDeptModel::class, 'parent_id', 'id');
    }

    /**
     * 定义子部门关联
     */
    public function children(): HasMany
    {
        return $this->hasMany(SysDeptModel::class, 'parent_id', 'id');
    }
}
