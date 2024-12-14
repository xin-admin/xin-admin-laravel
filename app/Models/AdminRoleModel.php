<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class AdminRole
 *
 * @property int $role_id
 * @property string $name
 * @property int $sort
 * @property string|null $rules
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin IdeHelperModel
 */
class AdminRoleModel extends Model
{
    protected $table = 'admin_role';

    protected $primaryKey = 'role_id';

    protected $casts = [
        'sort' => 'int',
    ];

    protected $fillable = [
        'name',
        'sort',
        'rules',
        'status',
    ];


    /**
     * 权限列表
     */
    protected function rules(): Attribute
    {
        return Attribute::make(
            get: function (string $value): array {
                if ($value == '*') {
                    return AdminRuleModel::query()
                        ->where('status', 1)
                        ->pluck('key')
                        ->toArray();
                } else {
                    return AdminRuleModel::query()
                        ->whereIn('id', explode(',', $value))
                        ->where('status', 1)
                        ->pluck('key')
                        ->toArray();
                }
            },
            set: fn (array $value) => implode(',', $value),
        );
    }

    /**
     * 关联用户
     */
    public function users(): HasMany
    {
        return $this->hasMany(AdminUserModel::class, 'role_id', 'role_id');
    }
}
