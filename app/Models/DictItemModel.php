<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DictItem
 */
class DictItemModel extends Model
{
    protected $table = 'dict_item';

    protected $casts = [
        'dict_id' => 'int',
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
     * @return BelongsTo
     */
    public function dict(): BelongsTo
    {
        return $this->belongsTo(DictModel::class, 'dict_id', 'id');
    }
}
