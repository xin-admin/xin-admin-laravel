<?php


namespace App\Models;

/**
 * 在线开发记录模型
 */
class OnlineTableModel extends BaseModel
{
    protected $primaryKey = 'id';

    protected $table = 'online_table';

    protected $fillable = ['table_name', 'columns', 'crud_config', 'table_config', 'describe'];
}
