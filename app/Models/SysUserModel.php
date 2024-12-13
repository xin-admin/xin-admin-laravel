<?php

namespace App\Models;

use App\Models\File\FileModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 系统用户模型
 */
class SysUserModel extends Model
{
    use SoftDeletes;

    protected $table = 'sys_user';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'nickname',
        'avatar_id',
        'sex',
        'email',
        'mobile',
        'role_id',
        'dept_id',
        'login_ip',
        'login_date',
    ];

    protected $hidden = ['password', 'avatar'];

    protected $appends = ['rules', 'avatar_url'];

    /**
     * 关联用户头像表
     */
    public function avatar(): HasOne
    {
        return $this->hasOne(FileModel::class, 'file_id', 'avatar_id');
    }

    /**
     * 头像地址
     */
    protected function avatarUrl(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->avatar->preview_url ?? null;
            },
        );
    }

    /**
     * 关联部门表
     */
    public function dept(): HasOne
    {
        return $this->hasOne(SysDeptModel::class, 'dept_id', 'dept_id');
    }

    /**
     * 关联角色表
     */
    public function role(): HasOne
    {
        return $this->hasOne(SysRoleModel::class, 'role_id', 'role_id');
    }
}
