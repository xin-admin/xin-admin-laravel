<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OnlineTable
 *
 * @property int $id
 * @property string $table_name
 * @property string $data_table
 * @property string $columns
 * @property string $crud_config
 * @property string $table_config
 * @property string $describe
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin IdeHelperModel
 */
class OnlineTableModel extends Model
{
    protected $table = 'online_table';

    protected $fillable = [
        'table_name',
        'data_table',
        'columns',
        'crud_config',
        'table_config',
        'describe',
    ];
}
