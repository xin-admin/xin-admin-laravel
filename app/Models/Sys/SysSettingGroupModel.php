<?php
namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SettingGroup
 */
class SysSettingGroupModel extends Model
{
    protected $table = 'sys_setting_group';

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
        return $this->hasMany(SysSettingModel::class ,'group_id', 'id');
    }

}
