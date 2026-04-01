<?php

namespace Modules\AnnoRoute;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\AnnoRoute\Attribute\DeleteRoute;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\PutRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use ReflectionClass;
use ReflectionException;

class RouteRegisterService
{
    /**
     * @var array|string[]
     */
    private static array $mapping = [
        GetRoute::class,
        PostRoute::class,
        PutRoute::class,
        DeleteRoute::class
    ];

    /**
     * register 注册路由
     */
    public static function register(string $className): void
    {
        try {
            $classRef = new ReflectionClass($className);

            $classAttr = collect($classRef->getAttributes());
            if($classAttr->isEmpty()) return;

            $classAttrName = $classAttr->map->getName();
            if(! $classAttrName->contains(RequestAttribute::class)) {
                return;
            }
            $requestMapping = $classAttr->first(fn ($item) => $item->getName() == RequestAttribute::class);
            $routeInstance = $requestMapping->newInstance();
            // 默认参数
            $routePrefix = $routeInstance->routePrefix ?? '';
            $authGuard = $routeInstance->authGuard ?? null;
            $abilitiesPrefix = $routeInstance->abilitiesPrefix ?? '';
            $middleware = self::registerMiddleware($routeInstance->middleware);

            $methods = $classRef->getMethods();

            foreach ($methods as $method) {
                // 方法注解
                $attributes = $method->getAttributes();
                if(empty($attributes)) {
                    continue;
                }
                $methodName = $method->getName();

                foreach ($attributes as $attribute) {
                    if (in_array($attribute->getName(), self::$mapping)) {
                        $instance = $attribute->newInstance();
                        self::registerRoute(
                            $instance,
                            $methodName,
                            $className,
                            $authGuard,
                            $middleware,
                            $routePrefix,
                            $abilitiesPrefix,
                        );
                    }
                }
            }
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 注册路由
     * @param BaseAttribute $instance
     * @param string $method
     * @param string $className
     * @param string|null $authGuard
     * @param array $middleware
     * @param string $routePrefix
     * @param string $abilitiesPrefix
     * @return void
     */
    private static function registerRoute(
        BaseAttribute $instance,
        string $method,
        string $className,
        string $authGuard = null,
        array $middleware = [],
        string $routePrefix = '',
        string $abilitiesPrefix = ''
    ): void
    {
        $authorize = $instance->authorize;

        $authMiddleware = [];
        if (!empty($authorize)) {
            $authMiddleware[] = 'auth:sanctum';
            if(! empty($authGuard) ) {
                $authMiddleware[] = 'authGuard:' . $authGuard;
            } else {
                $authMiddleware[] = 'authGuard';
            }
            if (is_string($authorize) && !empty($abilitiesPrefix)) {
                $authMiddleware[] = 'abilities:' .  $abilitiesPrefix . '.' . $authorize;
            } else {
                $authMiddleware[] = 'abilities:' . $authorize;
            }
        }

        $middleware = array_merge($authMiddleware, self::registerMiddleware($instance->middleware), $middleware);
        $route = Route::{Str::lower($instance->httpMethod)}(
            $routePrefix . $instance->route,
            [$className, $method]
        )->middleware(array_unique($middleware));

        if (!empty($instance->where)) {
            $route->where($instance->where);
        }
    }

    /**
     * 获取中间件
     * @param $middleware
     * @return string[]
     */
    private static function registerMiddleware($middleware): array
    {
        if(empty($middleware)) {
            return [];
        }
        if(is_array($middleware)) {
            return $middleware;
        }
        if (is_string($middleware)) {
            return [$middleware];
        }
        return [];
    }
}
