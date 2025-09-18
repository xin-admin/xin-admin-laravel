<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 系统规则模型
 */
class SysRuleModel extends Model
{
    protected $table = 'sys_rule';
    protected $primaryKey = 'id';

    protected $fillable = [
        'parent_id',
        'type',
        'sort',
        'name',
        'path',
        'icon',
        'key',
        'local',
        'status',
        'show'
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
        'show' => 'integer'
    ];

    /**
     * 定义子权限关联
     */
    public function children(): HasMany
    {
        return $this->hasMany(SysRuleModel::class, 'parent_id', 'id')
            ->orderBy('sort');
    }

    /**
     * 定义父权限关联
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(SysRuleModel::class, 'parent_id', 'id');
    }

    /**
     * 角色权限关联中间表
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SysRoleModel::class, 'sys_role_rule', 'rule_id', 'role_id');
    }
}