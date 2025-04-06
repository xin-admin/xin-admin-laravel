<?php

namespace App\Providers;

use App\Service\SettingService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class SettingCacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 注册服务
        $this->app->singleton(SettingService::class, function () {
            return new SettingService();
        });
    }

    public function boot(SettingService $settingService): void
    {
        // 表存在时的逻辑
        if (Schema::hasTable('setting_group') && Schema::hasTable('setting')) {
            // 应用启动时刷新缓存
            $settingService->refreshSettings();
        }
    }
}