<?php

namespace Xin\AnnoRoute;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

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
                // 权限验证白名单
                $noPermission = $reflection->getProperty('noPermission')->getDefaultValue();
                // 循环所有的方法
                foreach ($reflection->getMethods() as $method) {
                    // 当前注册的路由
                    $route = null;
                    // $routeClass
                    $routeClass = null;
                    // 注解
                    $attributes = $method->getAttributes();
                    // 方法名称
                    $methodName = $method->getName();
                    foreach ($attributes as $attribute) {
                        // 注解类名称
                        $attributeType = $attribute->getName();
                        if($attributeType == GetMapping::class) {
                            $routeClass = $attribute->newInstance();
                            $routePath = $routeClass->route;
                            $route = Route::get($routeBasePath.$routePath, [$className, $methodName]);
                        }
                        if($attributeType == PostMapping::class) {
                            $routeClass = $attribute->newInstance();
                            $routePath = $routeClass->route;
                            $route = Route::post($routeBasePath.$routePath, [$className, $methodName]);
                        }
                        if($attributeType == PutMapping::class) {
                            $routeClass = $attribute->newInstance();
                            $routePath = $routeClass->route;
                            $route = Route::put($routeBasePath.$routePath, [$className, $methodName]);
                        }
                        if($attributeType == DeleteMapping::class) {
                            $routeClass = $attribute->newInstance();
                            $routePath = $routeClass->route;
                            $route = Route::delete($routeBasePath.$routePath, [$className, $methodName]);
                        }
                        if(!empty($route)) {
                            $middleware = [];
                            if (empty($noPermission) || !is_array($noPermission) || !in_array($methodName, $noPermission)) {
                                $middleware[] = 'auth:sanctum';
                            }
                            if($attributeType == Authorize::class ) {
                                $authClass = $attribute->newInstance();
                                $permission = $authClass->name;
                                $middleware[] = 'abilities:' . $permission;
                            }
                            if (!empty($routeClass->middleware)) {
                                $middleware[] = $routeClass->middleware;
                            }
                            $route->middleware($middleware);
                        }
                    }
                }
            } catch (\ReflectionException $e) {
                echo $e->getMessage().PHP_EOL;
            }

        }
    }
}
