<?php

namespace App\Observers;

use App\Models\Sys\SysSettingItemsModel;
use App\Services\SysSettingService;
use Illuminate\Support\Facades\Log;

/**
 * 系统设置观察者
 * 监听设置项的变更，自动刷新缓存
 */
class SysSettingObserver
{
    /**
     * 设置项创建后
     */
    public function created(SysSettingItemsModel $setting): void
    {
        $this->refreshCache('created', $setting);
    }

    /**
     * 设置项更新后
     */
    public function updated(SysSettingItemsModel $setting): void
    {
        $this->refreshCache('updated', $setting);
    }

    /**
     * 设置项删除后
     */
    public function deleted(SysSettingItemsModel $setting): void
    {
        $this->refreshCache('deleted', $setting);
    }

    /**
     * 刷新缓存
     */
    private function refreshCache(string $action, SysSettingItemsModel $setting): void
    {
        try {
            SysSettingService::refreshSettings();
            Log::info("系统配置缓存已自动刷新", [
                'action' => $action,
                'setting_id' => $setting->id,
                'key' => $setting->key,
            ]);
        } catch (\Throwable $e) {
            Log::error("系统配置缓存刷新失败", [
                'action' => $action,
                'setting_id' => $setting->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
