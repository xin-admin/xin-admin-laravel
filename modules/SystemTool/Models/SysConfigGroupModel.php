<?php
namespace Modules\SystemTool\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SettingGroup
 */
class SysConfigGroupModel extends Model
{
    protected $table = 'sys_config_group';

    protected $fillable = [
        'title',
        'key',
        'remark',
    ];

    /**
     * 关联设置项目
     * @return HasMany
     */
    public function settings(): HasMany
    {
        return $this->hasMany(SysConfigItemsModel::class ,'group_id', 'id');
    }

}
