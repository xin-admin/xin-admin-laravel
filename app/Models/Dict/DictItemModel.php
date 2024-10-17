<?php

namespace App\Models\Dict;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 字典项模型
 */
class DictItemModel extends BaseModel
{
    protected $table = 'dict_item';

    protected $fillable = ['dict_id', 'label', 'status', 'switch', 'value'];

    public function dict(): BelongsTo
    {
        return $this->belongsTo(DictModel::class, 'dict_id', 'id');
    }
}
