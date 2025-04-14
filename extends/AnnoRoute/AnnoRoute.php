<?php

namespace Xin\AnnoRoute;

use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionException;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

class AnnoRoute
{

    /**
     * the class name
     */
    private string $className;

    /**
     * the route base path
     */
    private string $basePath = '';

    /**
     * no permission method
     */
    private array $noPermission = [];

    /**
     * @var array|string[]
     */
    private array $routeClass = [
        PutMapping::class => 'put',
        GetMapping::class => 'get',
        PostMapping::class => 'post',
        DeleteMapping::class => 'delete',
    ];

    /**
     * @var array
     */
    private array $middleware = [];

    /**
     * register
     */
    public function register(string $className): void
    {
        try {
            $this->className = $className;

            $reflection = new ReflectionClass($className);

            $this->registerBaseData($reflection);

            // 循环所有的方法
            foreach ($reflection->getMethods() as $method) {
                // 方法注解
                $attributes = $method->getAttributes();

                if(empty($attributes)) continue;

                $methodName = $method->getName();

                $this->registerRoute($attributes , $methodName);

            }
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param array $attributes
     * @param $method
     */
    private function registerRoute(array $attributes, $method): void
    {
        // 获取所有注解类名
        $attributeClassNames = array_map(fn ($item) => $item->getName(), $attributes);

        $middleware = $this->middleware;

        // 遍历所有注解
        foreach ($attributes as $attribute) {
            $attributeClass = $attribute->getName();

            // 检查是否是路由注解 (Get/Post/Put/Delete Mapping)
            if (!array_key_exists($attributeClass, $this->routeClass)) {
                continue;
            }

            $routeInstance = $attribute->newInstance();
            $routePath = $routeInstance->route;

            // 添加基础认证中间件（如果不在白名单中）
            if (empty($this->noPermission) || !in_array($method, $this->noPermission)) {
                $middleware[] = 'auth:sanctum';
                // 添加令牌能力认证中间件
                if (in_array(Authorize::class, $attributeClassNames)) {
                    $authorizeIndex = array_search(Authorize::class, $attributeClassNames);
                    $authInstance = $attributes[$authorizeIndex]->newInstance();
                    $middleware[] = 'abilities:' . $authInstance->name;
                }
            }

            // 添加自定义路由中间件
            $routeMiddleware = $routeInstance->middleware ?? [];
            $middleware = array_merge($middleware, $this->registerMiddleware($routeMiddleware));

            // 注册路由
            Route::{$this->routeClass[$attributeClass]}(
                $this->basePath . $routePath,
                [$this->className, $method]
            )->middleware(array_unique($middleware));
        }
    }

    /**
     * set base path
     * @param ReflectionClass $class
     * @throws ReflectionException
     * @return void
     */
    private function registerBaseData(ReflectionClass $class): void
    {
        $attributes = $class->getAttributes();
        foreach ($attributes as $attribute) {
            $attributeClass = $attribute->getName();
            if ($attributeClass == RequestMapping::class) {
                $routeInstance = $attribute->newInstance();
                $this->basePath = $routeInstance->route ?? '';
                $middleware = $routeInstance->middleware ?? [];
                $this->middleware = $this->registerMiddleware($middleware);
            }
        }
        // 不需要权限校验
        $this->noPermission = $class->getProperty('noPermission')->getDefaultValue();
    }

    /**
     * 注册中间件
     * @param array|string $middleware
     * @return string[]
     */
    private function registerMiddleware(array | string $middleware): array
    {
        if(empty($middleware)) {
            return [];
        }
        if(is_array($middleware)) {
            return $middleware;
        }
        return [$middleware];
    }
}