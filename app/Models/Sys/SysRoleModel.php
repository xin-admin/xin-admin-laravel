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
        'status' => 'integer'
    ];

    protected $appends = ['countUser', 'ruleIds'];

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

    /** 用户总数 */
    public function getCountUserAttribute(): int
    {
        return $this->users()->count();
    }

    /** 拥有的权限ID */
    public function getRuleIdsAttribute(): array
    {
        if(!empty($this->id) && $this->id == 1) {
            return SysRuleModel::query()->pluck('id')->toArray();
        }
        return $this->rules()->pluck('id')->toArray();
    }
}
