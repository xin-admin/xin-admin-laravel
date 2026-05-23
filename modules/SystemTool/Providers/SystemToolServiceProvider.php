<?php

namespace Modules\SystemTool\Providers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\AnnoRoute\AnnoRoute;
use Modules\SystemTool\Services\SysConfigService;

class SystemToolServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SysConfigService::class, SysConfigService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(AnnoRoute $annoRoute): void
    {
        // 注册路由
        $annoRoute->register(base_path('modules/SystemTool/Http/Controllers'));

        try {
            DB::connection()->getPDO();
            if (Schema::hasTable('sys_setting_items')) {
                // 刷新系统设置缓存
                SysConfigService::refreshConfig();
            }
        } catch (Exception $e) {

        }
    }
}
