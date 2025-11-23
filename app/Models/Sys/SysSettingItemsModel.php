<?php
namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Setting
 */
class SysSettingItemsModel extends Model
{
    protected $table = 'sys_setting_items';

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

    protected $appends = ['options_json', 'props_json'];

    /**
     * 关联设置
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(SysSettingGroupModel::class, 'id', 'group_id');
    }

    public function getOptionsJsonAttribute(): string
    {
        if(empty($this->options)) {
            return "{}";
        }
        $data = [];
        $value = explode("\n", $this->options);
        foreach ($value as $item) {
            $item = explode('=',$item);
            if(count($item) < 2) {
                continue;
            }
            $data[] = [
                'label' => $item[1],
                'value' => $item[0]
            ];
        }
        return json_encode($data);
    }

    public function getPropsJsonAttribute(): string
    {
        if(empty($this->props)) {
            return "{}";
        }
        $data = [];
        $value = explode("\n",$this->props);
        foreach ($value as $item) {
            $item = explode('=',$item);
            if(count($item) < 2) {
                continue;
            }
            if($item[1] === 'false') {
                $data[$item[0]] = false;
            }elseif ($item[1] === 'true') {
                $data[$item[0]] = true;
            }else {
                $data[$item[0]] = $item[1];
            }
        }
        return json_encode($data);
    }
}
