<?php
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRule
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
