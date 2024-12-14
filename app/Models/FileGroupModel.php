<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FileGroup
 *
 * @property int $group_id
 * @property string $name
 * @property int $parent_id
 * @property int $sort
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin IdeHelperModel
 */
class FileGroupModel extends Model
{
    protected $table = 'file_group';

    protected $primaryKey = 'group_id';

    protected $casts = [
        'parent_id' => 'int',
        'sort' => 'int',
    ];

    protected $fillable = [
        'name',
        'parent_id',
        'sort',
    ];
}
