<?php

namespace App\Providers;

use App\Models\System\SysAccessToken;
use App\Services\BaseService;
use App\Services\LengthAwarePaginatorService;
use App\Services\SysSettingService;
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

        $this->app->bind(ExceptionsHandler::class, \App\Exceptions\ExceptionsHandler::class);

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
