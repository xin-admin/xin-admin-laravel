<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * 系统角色模型
 */
class SysRoleModel extends Model
{
    protected $table = 'sys_role';

    protected $primaryKey = 'role_id';

    protected $fillable = [
        'name',
        'sort',
        'status',
    ];

    protected function rules(): Attribute
    {
        return Attribute::make(
            get: function (string $value): array {
                if ($value == '*') {
                    return SysRuleModel::query()
                        ->where('status', 1)
                        ->pluck('key')
                        ->toArray();
                } else {
                    return SysRuleModel::query()
                        ->whereIn('id', explode(',', $value))
                        ->where('status', 1)
                        ->pluck('key')
                        ->toArray();
                }
            },
            set: fn (array $value) => implode(',', $value),
        );
    }
}
