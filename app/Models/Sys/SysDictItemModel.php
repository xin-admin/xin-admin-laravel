<?php
namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 字典项模型
 */
class SysDictItemModel extends Model
{
    protected $table = 'sys_dict_item';

    protected $casts = [
        'dict_id' => 'integer',
        'switch' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $fillable = [
        'dict_id',
        'label',
        'value',
        'switch',
        'status',
    ];

    /**
     * 字典项关联字典表
     */
    public function dict(): BelongsTo
    {
        return $this->belongsTo(SysDictModel::class, 'dict_id', 'id');
    }
}
