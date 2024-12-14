<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class AdminDept
 *
 * @property int $dept_id
 * @property int|null $parent_id
 * @property string|null $ancestors
 * @property string $name
 * @property int $sort
 * @property string|null $leader
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin IdeHelperModel
 */
class AdminDeptModel extends Model
{
    protected $table = 'admin_dept';

    protected $primaryKey = 'dept_id';

    protected $casts = [
        'parent_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'parent_id',
        'ancestors',
        'name',
        'sort',
        'leader',
        'phone',
        'email',
        'status',
    ];

    /**
     * 关联用户
     */
    public function users(): HasMany
    {
        return $this->hasMany(AdminUserModel::class, 'dept_id', 'dept_id');
    }
}
