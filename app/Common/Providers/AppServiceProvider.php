<?php

namespace App\Common\Providers;

use App\Common\Models\System\SysAccessToken;
use App\Common\Models\System\SysSettingItemsModel;
use App\Common\Observers\SysSettingObserver;
use App\Common\Services\BaseService;
use App\Common\Services\LengthAwarePaginatorService;
use App\Common\Services\MailConfigService;
use App\Common\Services\StorageConfigService;
use App\Common\Services\SysSettingService;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionsHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use ReflectionClass;

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

        $this->app->bind(ExceptionsHandler::class, \App\Common\Exceptions\ExceptionsHandler::class);

        $this->app->resolving(function ($object, $app) {
            if ($object instanceof BaseService) {
                $this->injectProperties($object, $app);
            }
        });
    }

    protected function injectProperties(object $object, $app): void
    {
        $reflection = new ReflectionClass($object);

        if (!$reflection->hasProperty('model')) {
            return;
        }

        $property = $reflection->getProperty('model');
        $type = $property->getType();

        if ($type && !$type->isBuiltin()) {
            $className = $type->getName();
            $property->setValue($object, $app->make($className));
        }
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
