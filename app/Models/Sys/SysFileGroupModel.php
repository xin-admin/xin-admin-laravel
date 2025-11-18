<?php
namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class FileGroup
 */
class SysFileGroupModel extends Model
{
    protected $table = 'sys_file_group';

    protected $primaryKey = 'id';

    protected $casts = [
        'sort' => 'int',
        'parent_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'parent_id',
        'sort',
        'describe',
    ];

    protected $appends = ['countFiles'];

    /**
     * 获取父级分组
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    /**
     * 获取子级分组
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * 获取分组下的文件
     */
    public function files(): HasMany
    {
        return $this->hasMany(SysFileModel::class, 'group_id', 'id');
    }

    /**
     * 获取分组下的文件数量
     */
    public function getCountFilesAttribute(): int
    {
        return $this->files()->count();
    }
}
