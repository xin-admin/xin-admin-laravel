<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SettingGroup
 *
 * @property int $id
 * @property string $title
 * @property string $key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @mixin IdeHelperModel
 */
class SettingGroupModel extends Model
{
    protected $table = 'setting_group';

    protected $fillable = [
        'title',
        'key',
        'remark',
    ];
}
