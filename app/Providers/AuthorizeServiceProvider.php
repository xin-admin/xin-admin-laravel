<?php

namespace App\Providers;

use App\Attribute\AdminController;
use App\Attribute\AppController;
use App\Attribute\Authorize;
use App\Exceptions\AuthorizeException;
use App\Http\BaseController;
use App\Service\IAuthorizeService;
use App\Service\impl\AuthorizeService;
use App\Service\impl\TokenService;
use App\Service\ITokenService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class AuthorizeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ITokenService::class, TokenService::class);
        $this->app->singleton(IAuthorizeService::class, function ($app) {
            return new AuthorizeService($app['request'], $app->make(ITokenService::class));
        });
        $this->app->resolving(BaseController::class, function ($object) {
            $this->registerResolving($object);
        });
    }

    private function registerResolving($object): void
    {
        $route = Route::current();
        if (! $route) {
            return;
        }
        $actionName = $route->getActionName();
        if (! $actionName) {
            return;
        }
        [$controller, $method] = explode('@', $actionName);
        try {
            $reflection = new ReflectionClass($controller);
            $noPermission = $reflection->getProperty('noPermission')->getDefaultValue();
            if (! empty($noPermission) && in_array($method, $noPermission)) {
                return;
            }
            $attrs = $reflection->getAttributes();
            foreach ($attrs as $attr) {
                if ($attr->getName() === AdminController::class) {
                    $methodAttr = $reflection->getMethod($method)->getAttributes(Authorize::class);
                    if (count($methodAttr) <= 0) {
                        $this->auth();
                    } else {
                        $this->auth('admin', $methodAttr[0]->getArguments()[0]);
                    }
                }
                if ($attr->getName() === AppController::class) {
                    $this->auth('user');
                }
            }
        } catch (\ReflectionException $e) {
        }
    }

    private function auth(string $type = 'admin', string $auth = ''): void
    {
        if ($type === 'admin') {
            $rules = customAuth('admin')->permission();
            if (empty($auth)) {
                return;
            }
            $rules = array_map('strtolower', $rules);
            if (! in_array(strtolower($auth), $rules)) {
                throw new AuthorizeException('您没有权限操作！！');
            }
        } else {
            customAuth('user');
        }
    }
}
