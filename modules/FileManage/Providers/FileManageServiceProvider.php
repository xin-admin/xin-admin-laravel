<?php

namespace Modules\FileManage\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AnnoRoute\AnnoRoute;

class FileManageServiceProvider extends ServiceProvider
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
        $annoRoute->register(base_path('modules/FileManage/Http/Controllers'));
    }

}
