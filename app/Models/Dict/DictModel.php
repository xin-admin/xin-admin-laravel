<?php

namespace App\Models\Dict;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 字典模型
 */
class DictModel extends BaseModel
{
    protected $table = 'dict';

    protected $fillable = ['name', 'type', 'code', 'describe'];

    public function dictItems(): HasMany
    {
        return $this->hasMany(DictItemModel::class, 'dict_id', 'id');
    }
}
