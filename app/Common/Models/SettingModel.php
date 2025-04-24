<?php
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * 关联设置
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(SettingGroupModel::class, 'id', 'group_id');
    }

    protected function options(): Attribute
    {
        return Attribute::make(
            get: function($value) {
                if(empty($value)) {
                    return [];
                }
                $data = [];
                $value = explode("\n",$value);
                foreach ($value as $item) {
                    $item = explode('=',$item);
                    if(count($item) < 2) {
                        continue;
                    }
                    $data[] = [
                        'label' => $item[0],
                        'value' => $item[1]
                    ];
                }
                return $data;
            },
        );
    }

    protected function props(): Attribute
    {
        return Attribute::make(
            get: function($value) {
                if(empty($value)) {
                    return [];
                }
                $data = [];
                $value = explode("\n",$value);
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
                return $data;
            },
        );
    }
}
