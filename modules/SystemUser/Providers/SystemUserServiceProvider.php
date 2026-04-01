<?php

namespace Modules\SystemUser\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Modules\AnnoRoute\AnnoRoute;
use Modules\SystemUser\Models\SysAccessToken;

class SystemUserServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //...
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(AnnoRoute $annoRoute): void
    {
        // 注册路由
        $annoRoute->register(base_path('modules/SystemUser/Http/Controllers'));

        //
        Sanctum::usePersonalAccessTokenModel(SysAccessToken::class);
    }



}
