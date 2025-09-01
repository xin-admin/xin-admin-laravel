<?php

namespace App\Models;

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


    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 定义与部门的归属关系
     */
    public function dept(): BelongsTo
    {
        return $this->belongsTo(SysDeptModel::class, 'dept_id', 'id');
    }

    /**
     * 定义与角色的关联
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SysRoleModel::class, 'sys_user_role', 'user_id', 'role_id');
    }

    /**
     * 定义与登录日志的关联
     */
    public function loginRecords(): HasMany
    {
        return $this->hasMany(SysLoginRecordModel::class, 'user_id', 'id');
    }

}
