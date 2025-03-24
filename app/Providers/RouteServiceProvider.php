<?php

namespace App\Providers;

use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
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
                    if ($attribute->getName() === RequestMapping::class) {
                        $routeBasePath = ($attribute->newInstance())->route;
                        break;
                    }
                }
                foreach ($reflection->getMethods() as $method) {
                    foreach ($method->getAttributes() as $attribute) {
                        $routeType = $attribute->getName();
                        $routeClass = $attribute->newInstance();
                        if($routeType == GetMapping::class) {
                            $routePath = $routeClass->route;
                            $route = Route::get($routeBasePath.$routePath, [$className, $method->getName()]);
                        }
                        if($routeType == PostMapping::class) {
                            $routePath = $routeClass->route;
                            $route = Route::post($routeBasePath.$routePath, [$className, $method->getName()]);
                        }
                        if($routeType == PutMapping::class) {
                            $routePath = $routeClass->route;
                            $route = Route::put($routeBasePath.$routePath, [$className, $method->getName()]);
                        }
                        if($routeType == DeleteMapping::class) {
                            $routePath = $routeClass->route;
                            $route = Route::delete($routeBasePath.$routePath, [$className, $method->getName()]);
                        }
                        if (isset($route) && !empty($routeClass->middleware)) {
                            $route->middleware($routeClass->middleware);
                        }
                    }
                }
            } catch (\ReflectionException $e) {
                echo $e->getMessage().PHP_EOL;
            }

        }
    }
}
