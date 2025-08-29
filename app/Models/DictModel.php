<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Dict
 */
class DictModel extends Model
{
    protected $table = 'dict';

    protected $fillable = [
        'name',
        'type',
        'describe',
        'code',
    ];

    /**
     * 关联字典子项
     * @return HasMany
     */
    public function dictItems(): HasMany
    {
        return $this->hasMany(DictItemModel::class, 'dict_id', 'id');
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
