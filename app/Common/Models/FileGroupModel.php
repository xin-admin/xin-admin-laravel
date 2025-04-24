<?php
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FileGroup
 */
class FileGroupModel extends Model
{
    protected $table = 'file_group';

    protected $primaryKey = 'group_id';

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
