<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * 系统用户模型
 */
class SysUserModel extends User
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    protected $table = 'sys_user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'username',
        'password',
        'nickname',
        'avatar_id',
        'bio',
        'sex',
        'mobile',
        'email',
        'email_verified_at',
        'dept_id',
        'login_ip',
        'login_time',
        'status'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'login_time' => 'datetime',
        'status' => 'integer',
        'sex' => 'integer',
        'avatar_id' => 'integer',
        'dept_id' => 'integer'
    ];

    protected $appends = ['roles_field', 'dept_name'];

    protected $hidden = [
        'dept',
        'password',
        'remember_token',
        'deleted_at'
    ];

    /**
     * 定义与部门的归属关系
     */
    public function dept(): BelongsTo
    {
        return $this->belongsTo(SysDeptModel::class, 'dept_id', 'id');
    }

    /** 部门名称 */
    public function getDeptNameAttribute(): string
    {
        return $this->dept->name ?? '';
    }

    /**
     * 定义与角色的关联
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SysRoleModel::class, 'sys_user_role', 'user_id', 'role_id');
    }

    /**
     * 获取用户角色列表
     */
    public function getRolesFieldAttribute()
    {
        return $this->roles()
            ->select('id as role_id', 'name')
            ->get()
            ->map(function ($role) {
                return [
                    'role_id' => $role->role_id,
                    'name' => $role->name
                ];
            });
    }

    /**
     * 定义与登录日志的关联
     */
    public function loginRecords(): HasMany
    {
        return $this->hasMany(SysLoginRecordModel::class, 'user_id', 'id');
    }


}
