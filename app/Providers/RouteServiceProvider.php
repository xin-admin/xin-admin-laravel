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
                $filePath = str_replace('\\', '/', $filePath); // 统一使用正斜杠
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
                    }
                }
                foreach ($reflection->getMethods() as $method) {
                    $attributes = $method->getAttributes();
                    foreach ($attributes as $attribute) {
                        // 检查是否是 Route 属性
                        if ($attribute->getName() === 'App\Attribute\route\GetMapping') {
                            $route = $attribute->newInstance();
                            $routePath = $route->getRoute();
                            // 注册路由
                            Route::get($routeBasePath.$routePath, [$className, $method->getName()]);
                        }
                        if ($attribute->getName() === 'App\Attribute\route\PostMapping') {
                            $route = $attribute->newInstance();
                            $routePath = $route->getRoute();
                            // 注册路由
                            Route::post($routeBasePath.$routePath, [$className, $method->getName()]);
                        }
                        if ($attribute->getName() === 'App\Attribute\route\PutMapping') {
                            $route = $attribute->newInstance();
                            $routePath = $route->getRoute();
                            // 注册路由
                            Route::put($routeBasePath.$routePath, [$className, $method->getName()]);
                        }
                        if ($attribute->getName() === 'App\Attribute\route\DeleteMapping') {
                            $route = $attribute->newInstance();
                            $routePath = $route->getRoute();
                            // 注册路由
                            Route::delete($routeBasePath.$routePath, [$className, $method->getName()]);
                        }
                    }
                }
            } catch (\ReflectionException $e) {
            }

        }
    }
}
