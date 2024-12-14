<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AdminUser
 *
 * @property int $user_id
 * @property string $username
 * @property string $nickname
 * @property int $avatar_id
 * @property string $sex
 * @property string $email
 * @property string $mobile
 * @property string $status
 * @property int $role_id
 * @property int $dept_id
 * @property string $password
 * @property string|null $login_ip
 * @property Carbon|null $login_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @mixin IdeHelperModel
 */
class AdminUserModel extends Model
{
    use SoftDeletes;

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

    /**
     * 关联用户头像表
     */
    public function avatar(): HasOne
    {
        return $this->hasOne(FileModel::class, 'file_id', 'avatar_id');
    }

    // 头像地址
    protected function avatarUrl(): Attribute
    {
        return new Attribute(get: fn () => $this->avatar->preview_url ?? null);
    }

    // 部门名称
    protected function deptName(): Attribute
    {
        return new Attribute(get: fn () => $this->dept->name ?? null);
    }

    // 角色名称
    protected function roleName(): Attribute
    {
        return new Attribute(get: fn () => $this->role->name ?? null);
    }

    // 权限列表
    protected function rules(): Attribute
    {
        return new Attribute(get: fn (): array => $this->role->rules ?? []);
    }

    /**
     * 关联部门表
     */
    public function dept(): BelongsTo
    {
        return $this->belongsTo(AdminDeptModel::class, 'dept_id', 'dept_id');
    }

    /**
     * 关联角色表
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(AdminRoleModel::class, 'role_id', 'role_id');
    }
}
