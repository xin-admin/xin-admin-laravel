<?php

namespace App\Providers;

use App\Service\SettingService;
use Illuminate\Support\ServiceProvider;

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
        // 应用启动时刷新缓存
        $settingService->refreshSettings();
    }
}