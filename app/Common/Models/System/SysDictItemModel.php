<?php
namespace App\Common\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 字典数据模型
 */
class SysDictItemModel extends Model
{
    protected $table = 'sys_dict_item';

    protected $fillable = [
        'dict_id',
        'label',
        'value',
        'color',
        'status',
        'sort',
    ];

    protected $casts = [
        'dict_id' => 'integer',
        'status' => 'integer',
        'sort' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * 字典项关联字典表
     */
    public function dict(): BelongsTo
    {
        return $this->belongsTo(SysDictModel::class, 'dict_id', 'id');
    }
}
