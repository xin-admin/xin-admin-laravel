<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 系统角色模型
 */
class SysRoleModel extends Model
{
    protected $table = 'sys_role';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'sort',
        'rules',
        'description',
        'status'
    ];

    protected $casts = [
        'sort' => 'integer',
        'status' => 'integer',
        'rules' => 'array'
    ];

    /**
     * 角色用户关联
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(SysUserModel::class, 'sys_user_role', 'role_id', 'user_id');
    }

    /**
     * 角色权限关联中间表
     */
    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(SysRuleModel::class, 'sys_role_rule', 'role_id', 'rule_id');
    }
}