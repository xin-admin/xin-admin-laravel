<?php

namespace App\Providers\AnnoRoute;

use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\DeleteMapping;
use App\Providers\AnnoRoute\Attribute\Find;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\Mapping;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class AnnoRoute
{
    /**
     * 当前请求控制器类名
     * The current requested controller class
     *
     * @var string
     */
    private string $className;

    /**
     * 当前请求中默认的中间件
     * The default middleware of the current request
     *
     * @var array
     */
    private array $middleware = [];

    /**
     * 当前请求路由的前缀
     * The prefix of the currently requested routing path
     *
     * @var string
     */
    private string $routePrefix = '';

    /**
     * 当前请求路由的权限能力前缀
     * The routing abilities prefix of the current request
     *
     * @var string
     */
    private string $abilitiesPrefix = '';

    /**
     * 当前请求路由的 Authentication Guards
     * The Authentication Guards of the current request
     * @var ?string
     */
    private ?string $authGuard = null;

    /**
     * 不需要权限校验的方法
     * Methods not permission verification
     */
    private array $noPermission = [];

    /**
     * register
     */
    public function register(string $className): void
    {
        try {
            $this->className = $className;

            $classRef = new ReflectionClass($className);

            $classAttr = collect($classRef->getAttributes());
            if($classAttr->isEmpty()) return;

            $classAttrName = $classAttr->map->getName();
            if(! $classAttrName->contains(RequestMapping::class)) {
                return;
            }
            $requestMapping = $classAttr->first(fn ($item) => $item->getName() == RequestMapping::class);
            $routeInstance = $requestMapping->newInstance();
            // 默认参数
            $this->routePrefix = $routeInstance->routePrefix ?? '';
            $this->authGuard = $routeInstance->authGuard ?? null;
            $this->abilitiesPrefix = $routeInstance->abilitiesPrefix ?? '';
            $this->middleware = $this->registerMiddleware($routeInstance->middleware);
            // 不需要权限校验
            try {
                $this->noPermission = $classRef->getProperty('noPermission')->getDefaultValue();
            } catch (ReflectionException) {
                $this->noPermission = [];
            }

            $this->registerCRUD($classAttr);

            collect($classRef->getMethods())->each(function ($method) {
                // 方法注解
                $attributes = $method->getAttributes();

                if(!empty($attributes)) {
                    $methodName = $method->getName();

                    $this->registerMapping($attributes , $methodName);
                }
            });
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }
    }

    private function registerCRUD(Collection $classAttr): void
    {
        $CRUD = [ Create::class, Query::class, Find::class, Update::class, Delete::class ];
        $classAttr->filter(fn ($item) => in_array($item->getName(), $CRUD))->each(function ($item) {
            $instance = $item->newInstance();
            $this->registerRoute($instance, $instance->handlerMethod);
        });
    }

    private function registerRoute(Mapping $instance, $method): void
    {
        $authorize = $instance->authorize;
        $middleware = [];

        if (empty($this->noPermission) || !in_array($method, $this->noPermission)) {
            $middleware[] = 'auth:sanctum';
            if(! empty($this->authGuard) ) {
                $middleware[] = 'authGuard:' . $this->authGuard;
            } else {
                $middleware[] = 'authGuard';
            }
            if (!empty($this->abilitiesPrefix) && !empty($authorize)) {
                $middleware[] = 'abilities:' .  $this->abilitiesPrefix . '.' . $authorize;
            }
        }

        $middleware = array_merge($middleware, $this->registerMiddleware($instance->middleware), $this->middleware);
        $route = Route::{Str::lower($instance->httpMethod)}(
            $this->routePrefix . $instance->route,
            [$this->className, $method]
        )->middleware(array_unique($middleware));

        // 应用路由参数约束
        if (!empty($instance->where)) {
            $route->where($instance->where);
        }
    }

    /**
     * @param array $attributes
     * @param $method
     */
    private function registerMapping(array $attributes, $method): void
    {
        $mapping = [ GetMapping::class, PostMapping::class, PutMapping::class, DeleteMapping::class ];

        collect($attributes)->filter(fn ($item) => in_array($item->getName(), $mapping))->each(function ($item) use ($method) {
            $instance = $item->newInstance();
            $this->registerRoute($instance, $method);
        });
    }

    /**
     * 注册中间件
     * @param $middleware
     * @return string[]
     */
    private function registerMiddleware($middleware): array
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