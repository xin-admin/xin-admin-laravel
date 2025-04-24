<?php
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class AdminUser
 */
class AdminUserModel extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin_user';

    protected $primaryKey = 'user_id';

    protected $casts = [
        'avatar_id' => 'int',
        'role_id' => 'int',
        'dept_id' => 'int',
        'login_date' => 'datetime',
    ];

    protected $fillable = [
        'username',
        'nickname',
        'avatar_id',
        'sex',
        'email',
        'mobile',
        'status',
        'role_id',
        'dept_id',
        'password',
        'login_ip',
        'login_date',
    ];

    protected $hidden = [
        'password',
        'avatar',
        'role',
        'dept',
    ];

    protected $appends = ['avatar_url', 'dept_name', 'role_name', 'rules'];

    /** 关联用户头像表 */
    public function avatar(): HasOne
    {
        return $this->hasOne(FileModel::class, 'file_id', 'avatar_id');
    }

    /** 头像地址 */
    protected function avatarUrl(): Attribute
    {
        return new Attribute(get: fn () => $this->avatar->preview_url ?? null);
    }

    /** 部门名称 */
    protected function deptName(): Attribute
    {
        return new Attribute(get: fn () => $this->dept->name ?? null);
    }

    /** 角色名称 */
    protected function roleName(): Attribute
    {
        return new Attribute(get: fn () => $this->role->name ?? null);
    }

    /** 权限列表 */
    protected function rules(): Attribute
    {
        return new Attribute(get: fn (): array => $this->role->rules ?? []);
    }

    /** 关联部门表 */
    public function dept(): BelongsTo
    {
        return $this->belongsTo(AdminDeptModel::class, 'dept_id', 'dept_id');
    }

    /** 关联角色表 */
    public function role(): BelongsTo
    {
        return $this->belongsTo(AdminRoleModel::class, 'role_id', 'role_id');
    }

    /** 密码修改器 */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => password_hash($value, PASSWORD_DEFAULT),
        );
    }
}
