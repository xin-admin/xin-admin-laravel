<?php
namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FileGroup
 */
class SysFileGroupModel extends Model
{
    protected $table = 'sys_file_group';

    protected $primaryKey = 'id';

    protected $casts = [
        'sort' => 'int',
    ];

    protected $fillable = [
        'name',
        'parent_id',
        'sort',
        'describe',
    ];
}
