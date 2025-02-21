<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 自动注册控制器路由
        $this->registerAttributesRoutes();
    }

    protected function registerAttributesRoutes(): void
    {
        // 获取所有控制器

        $dir = app_path('Http');
        $controllers = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($controllers as $controller) {
            try {
                if (! $controller->isFile()) {
                    continue;
                }
                $filePath = str_replace(app_path('Http'), '', $controller->getPath());
                $filePath = str_replace('/', '\\', $filePath);
                if (! str_contains($controller->getFilename(), 'Controller')) {
                    continue;
                }
                if ($controller->getFilename() === 'BaseController.php') {
                    continue;
                }
                $className = 'App\\Http'.$filePath.'\\'.basename($controller->getFileName(), '.php');
                $reflection = new ReflectionClass($className);
                $routeBasePath = '';
                foreach ($reflection->getAttributes() as $attribute) {
                    if ($attribute->getName() === 'App\Attribute\route\RequestMapping') {
                        $route = $attribute->newInstance();
                        $routeBasePath = $route->getRoute();
                        break;
                    }
                }
                foreach ($reflection->getMethods() as $method) {
                    foreach ($method->getAttributes() as $attribute) {
                        switch ($attribute->getName()) {
                            case 'App\Attribute\route\GetMapping':
                                $route = $attribute->newInstance();
                                $routePath = $route->getRoute();
                                Route::get($routeBasePath.$routePath, [$className, $method->getName()]);
                                break;
                            case 'App\Attribute\route\PostMapping':
                                $route = $attribute->newInstance();
                                $routePath = $route->getRoute();
                                Route::post($routeBasePath.$routePath, [$className, $method->getName()]);
                                break;
                            case 'App\Attribute\route\PutMapping':
                                $route = $attribute->newInstance();
                                $routePath = $route->getRoute();
                                Route::put($routeBasePath.$routePath, [$className, $method->getName()]);
                                break;
                            case 'App\Attribute\route\DeleteMapping':
                                $route = $attribute->newInstance();
                                $routePath = $route->getRoute();
                                Route::delete($routeBasePath.$routePath, [$className, $method->getName()]);
                                break;
                        }
                    }
                }
            } catch (\ReflectionException $e) {
                echo $e->getMessage().PHP_EOL;
            }

        }
    }
}
