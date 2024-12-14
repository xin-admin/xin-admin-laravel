<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @property int $id
 * @property string $key
 * @property string $title
 * @property string $describe
 * @property string $values
 * @property string $type
 * @property string $options
 * @property string $props
 * @property int $group_id
 * @property int $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin IdeHelperModel
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
}
