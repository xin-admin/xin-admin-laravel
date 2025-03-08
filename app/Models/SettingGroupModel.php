<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SettingGroup
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
