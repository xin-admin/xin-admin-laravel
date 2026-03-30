<?php

namespace App\Providers;

use App\Models\System\SysAccessToken;
use App\Services\SysSettingService;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionsHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Xin\AnnoRoute\AnnoRoute;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SysSettingService::class, SysSettingService::class);
        $this->app->bind(ExceptionsHandler::class, \App\Exceptions\ExceptionsHandler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(AnnoRoute $annoRoute): void
    {
        // 注册路由
        $annoRoute->register(app_path('Http/Controllers'));

        Sanctum::usePersonalAccessTokenModel(SysAccessToken::class);

        try {
            DB::connection()->getPDO();
            if (Schema::hasTable('sys_setting_items')) {
                // 刷新系统设置缓存
                SysSettingService::refreshSettings();
            }
        } catch (Exception $e) {

        }
    }
}
