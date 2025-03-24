<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class AdminDeptModel
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
