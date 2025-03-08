<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
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
