<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SettingGroup
 *
 * @property int $id
 * @property int $pid
 * @property string $title
 * @property string $key
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin IdeHelperModel
 */
class SettingGroupModel extends Model
{
    protected $table = 'setting_group';

    protected $casts = [
        'pid' => 'int',
    ];

    protected $fillable = [
        'pid',
        'title',
        'key',
        'type',
    ];

    public function setting(): HasMany
    {
        return $this->hasMany(SettingModel::class, 'group_id');
    }
}
