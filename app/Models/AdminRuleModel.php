<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRule
 *
 * @property int $rule_id
 * @property int $parent_id
 * @property string $type
 * @property int $sort
 * @property string $name
 * @property string|null $path
 * @property string|null $icon
 * @property string $key
 * @property string|null $local
 * @property int $status
 * @property int $show
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin IdeHelperModel
 */
class AdminRuleModel extends Model
{
    protected $table = 'admin_rule';

    protected $primaryKey = 'rule_id';

    protected $casts = [
        'parent_id' => 'int',
        'sort' => 'int',
        'status' => 'int',
        'show' => 'int',
    ];

    protected $fillable = [
        'parent_id',
        'type',
        'sort',
        'name',
        'path',
        'icon',
        'key',
        'local',
        'status',
        'show',
    ];
}
