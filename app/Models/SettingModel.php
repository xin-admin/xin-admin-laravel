<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Setting
 */
class SettingModel extends Model
{
    protected $table = 'setting';

    protected $casts = [
        'group_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'key',
        'title',
        'describe',
        'values',
        'type',
        'options',
        'props',
        'group_id',
        'sort',
    ];

    /**
     * 关联设置
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(SettingGroupModel::class, 'id', 'group_id');
    }
}
