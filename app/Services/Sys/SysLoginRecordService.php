<?php

namespace App\Services\Sys;

use App\Models\Sys\SysLoginRecordModel;
use App\Services\BaseService;

class SysLoginRecordService extends BaseService
{
    /**
     * 通过用户ID获取记录
     */
    public function getRecordByID(int $id): array
    {
        return SysLoginRecordModel::where('user_id', $id)
            ->limit(10)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
}