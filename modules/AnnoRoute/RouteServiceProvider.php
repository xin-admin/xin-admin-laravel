<?php
namespace Modules\AnnoRoute;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AnnoRoute::class, AnnoRouteService::class);
    }
}
