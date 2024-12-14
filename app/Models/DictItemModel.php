<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DictItem
 *
 * @property int $id
 * @property int $dict_id
 * @property string $label
 * @property string $value
 * @property string $switch
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin IdeHelperModel
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
