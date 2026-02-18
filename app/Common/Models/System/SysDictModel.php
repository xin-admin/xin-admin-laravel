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
        'render_type',
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
     * 根据字典编码获取字典项
     * @param string $code
     * @return array
     */
    public static function getItemsByCode(string $code): array
    {
        $dict = static::where('code', $code)
            ->where('status', 0)
            ->first();

        if (!$dict) {
            return [];
        }

        return $dict->dictItems()
            ->where('status', 0)
            ->get()
            ->map(function ($item) use ($dict) {
                return [
                    'label' => $item->label,
                    'value' => $item->value,
                    'color' => $item->color,
                    'is_default' => $item->is_default,
                    'render_type' => $dict->render_type,
                ];
            })
            ->toArray();
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
                    'render_type' => $dict->render_type,
                    'describe' => $dict->describe,
                    'status' => $dict->status,
                    'sort' => $dict->sort,
                    'dict_items' => $dict->dictItems->filter(fn($item) => $item->status === 0)->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'label' => $item->label,
                            'value' => $item->value,
                            'color' => $item->color,
                            'is_default' => $item->is_default,
                            'sort' => $item->sort,
                        ];
                    })->values()->toArray(),
                ];
            })->toArray();
    }
}
