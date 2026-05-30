<?php

namespace Modules\SystemAgent\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AnnoRoute\AnnoRoute;

class SystemAgentServiceProvider extends ServiceProvider
{
    public function boot(AnnoRoute $annoRoute): void
    {
        $annoRoute->register(base_path('modules/SystemAgent/Http/Controllers'));
    }
}
