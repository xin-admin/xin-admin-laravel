<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class AdminRole
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
        'description',
        'status',
    ];

    /**
     * 权限列表
     */
    protected function rules(): Attribute
    {
        return Attribute::make(
            get: function ($value): array {
                if (empty($value)) {
                    return [];
                }
                if ($value == '*') {
                    return AdminRuleModel::query()
                        ->where('status', 1)
                        ->pluck('key')
                        ->toArray();
                } else {
                    return AdminRuleModel::query()
                        ->whereIn('rule_id', explode(',', $value))
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
