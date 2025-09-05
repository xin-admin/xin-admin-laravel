<?php
namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 字典模型
 */
class SysDictModel extends Model
{
    protected $table = 'sys_dict';

    protected $fillable = [
        'name',
        'type',
        'describe',
        'code',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * 关联字典子项
     */
    public function dictItems(): HasMany
    {
        return $this->hasMany(SysDictItemModel::class, 'dict_id', 'id');
    }

    /**
     * 获取字典子项
     * @return array
     */
    public function getDictItems(): array
    {
        return $this->query()
            ->with('dictItems')
            ->get()
            ->map(function ($dict) {
                return [
                    'id' => $dict->id,
                    'name' => $dict->name,
                    'type' => $dict->type,
                    'code' => $dict->code,
                    'describe' => $dict->describe,
                    'dict_items' => $dict->dictItems->map(function ($dictItem) {
                        return [
                            'label' => $dictItem->label,
                            'value' => $dictItem->value,
                            'status' => $dictItem->status,
                        ];
                    }),
                ];
            })->toArray();
    }
}
