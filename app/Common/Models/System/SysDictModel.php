<?php
namespace App\Common\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 字典类型模型
 */
class SysDictModel extends Model
{
    protected $table = 'sys_dict';

    protected $fillable = [
        'name',
        'code',
        'describe',
        'status',
        'sort',
    ];

    protected $casts = [
        'status' => 'integer',
        'sort' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * 关联字典子项
     */
    public function dictItems(): HasMany
    {
        return $this->hasMany(SysDictItemModel::class, 'dict_id', 'id')
            ->orderBy('sort')
            ->orderBy('id');
    }

    /**
     * 获取所有字典及其子项
     * @return array
     */
    public static function getAllDictWithItems(): array
    {
        return static::with('dictItems')
            ->where('status', 0)
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function ($dict) {
                return [
                    'id' => $dict->id,
                    'name' => $dict->name,
                    'code' => $dict->code,
                    'describe' => $dict->describe,
                    'status' => $dict->status,
                    'sort' => $dict->sort,
                    'dict_items' => $dict->dictItems->filter(fn($item) => $item->status === 0)->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'label' => $item->label,
                            'value' => $item->value,
                            'color' => $item->color,
                            'sort' => $item->sort,
                        ];
                    })->values()->toArray(),
                ];
            })->toArray();
    }
}
