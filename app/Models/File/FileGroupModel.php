<?php
namespace App\Models\File;

use App\Models\BaseModel;

/**
 * 文件分组模型
 */
class FileGroupModel extends BaseModel
{
    protected $table = 'file_group';

    protected $primaryKey = 'group_id';
}
