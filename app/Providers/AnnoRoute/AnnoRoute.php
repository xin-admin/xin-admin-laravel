<?php

namespace App\Providers\AnnoRoute;

use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Delete;
use App\Providers\AnnoRoute\Crud\Find;
use App\Providers\AnnoRoute\Crud\Query;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\Route\DeleteRoute;
use App\Providers\AnnoRoute\Route\GetRoute;
use App\Providers\AnnoRoute\Route\PostRoute;
use App\Providers\AnnoRoute\Route\PutRoute;
use Closure;
use Exception;
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

    private ?string $repository = null;

    /**
     * register 注册路由
     */
    public function register(string $className): void
    {
        try {
            $this->className = $className;

            $classRef = new ReflectionClass($className);

            try {
                $this->repository = $classRef->getProperty('repository')->getDefaultValue() ?? null;
            } catch (ReflectionException $e) {
                $this->repository = null;
            }

            $classAttr = collect($classRef->getAttributes());
            if($classAttr->isEmpty()) return;

            $classAttrName = $classAttr->map->getName();
            if(! $classAttrName->contains(RequestAttribute::class)) {
                return;
            }
            $requestMapping = $classAttr->first(fn ($item) => $item->getName() == RequestAttribute::class);
            $routeInstance = $requestMapping->newInstance();
            // 默认参数
            $this->routePrefix = $routeInstance->routePrefix ?? '';
            $this->authGuard = $routeInstance->authGuard ?? null;
            $this->abilitiesPrefix = $routeInstance->abilitiesPrefix ?? '';
            $this->middleware = $this->registerMiddleware($routeInstance->middleware);

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

    /**
     * @throws Exception
     */
    private function registerCRUD(Collection $classAttr): void
    {
        $CRUD = [ Create::class, Query::class, Find::class, Update::class, Delete::class ];
        $classAttr->filter(fn ($item) => in_array($item->getName(), $CRUD))->each(function ($item) {
            if(empty($this->repository)) {
                throw new Exception('控制器' . $this->className . '未定义仓储类，但是它使用了CRUD注解！');
            }
            $instance = $item->newInstance();
            $fun = $instance->store($this->repository);
            $this->registerRoute($instance, $fun);
        });
    }

    /**
     * @param array $attributes
     * @param string $method
     */
    private function registerMapping(array $attributes, string $method): void
    {
        $mapping = [ GetRoute::class, PostRoute::class, PutRoute::class, DeleteRoute::class ];

        collect($attributes)->filter(fn ($item) => in_array($item->getName(), $mapping))->each(function ($item) use ($method) {
            $instance = $item->newInstance();
            $this->registerRoute($instance, $method);
        });
    }

    private function registerRoute(BaseAttribute $instance, Closure | string $method): void
    {
        $authorize = $instance->authorize;
        $middleware = [];

        if (!empty($authorize)) {
            if(! empty($this->authGuard) ) {
                $middleware[] = 'auth:' . $this->authGuard;
            } else {
                $middleware[] = 'auth:sys_users';
            }
            if (is_string($authorize) && !empty($this->abilitiesPrefix)) {
                $middleware[] = 'hasPermission:' .  $this->abilitiesPrefix . '.' . $authorize;
            } else if (is_string($authorize)) {
                $middleware[] = 'hasPermission:' . $authorize;
            }
        }

        $middleware = array_merge($middleware, $this->registerMiddleware($instance->middleware), $this->middleware);
        if(is_string($method)) {
            $route = Route::{Str::lower($instance->httpMethod)}(
                $this->routePrefix . $instance->route,
                [$this->className, $method]
            )->middleware(array_unique($middleware));
        } else {
            $route = Route::{Str::lower($instance->httpMethod)}(
                $this->routePrefix . $instance->route,
                $method
            )->middleware(array_unique($middleware));
        }

        if (!empty($instance->where)) {
            $route->where($instance->where);
        }
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
