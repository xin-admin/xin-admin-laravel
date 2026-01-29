<?php

namespace App\Providers;

use App\Models\Sys\SysAccessToken;
use App\Models\Sys\SysSettingItemsModel;
use App\Observers\SysSettingObserver;
use App\Services\LengthAwarePaginatorService;
use App\Services\MailConfigService;
use App\Services\StorageConfigService;
use App\Services\SysSettingService;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionsHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(SysSettingService::class, SysSettingService::class);

        $this->app->bind('Illuminate\Pagination\LengthAwarePaginator', function ($app, $options) {
            return new LengthAwarePaginatorService(
                $options['items'],
                $options['total'],
                $options['perPage'],
                $options['currentPage'],
                $options['options']
            );
        });

        $this->app->bind(ExceptionsHandler::class, \App\Exceptions\ExceptionsHandler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(SysAccessToken::class);
        
        // 注册系统设置观察者，自动刷新缓存
        SysSettingItemsModel::observe(SysSettingObserver::class);

        try {
            DB::connection()->getPDO();
            if (Schema::hasTable('sys_setting_items')) {
                // 刷新系统设置缓存
                SysSettingService::refreshSettings();
                // 从系统设置初始化邮件配置
                MailConfigService::initFromSettings();
                // 从系统设置初始化存储配置
                StorageConfigService::initFromSettings();
            }
        } catch (Exception $e) {

        }
    }
}
