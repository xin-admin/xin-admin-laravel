<?php

namespace Modules\Common\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Modules\AnnoRoute\Attribute\DeleteRoute;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\PutRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;

class GenerateRouteHelperCommand extends Command
{
    protected $signature = 'route:helper';

    protected $description = 'Scan annotation routes and generate IDE route helper files for Laravel Idea';

    private static array $mapping = [
        GetRoute::class,
        PostRoute::class,
        PutRoute::class,
        DeleteRoute::class,
    ];

    private static array $httpMethodMap = [
        GetRoute::class => 'get',
        PostRoute::class => 'post',
        PutRoute::class => 'put',
        DeleteRoute::class => 'delete',
    ];

    /** @var array<string, list<array{method: string, uri: string, controller: string, action: string, middleware: array}>> */
    private array $controllerRoutes = [];

    public function handle(): int
    {
        $paths = [
            app_path('Http/Controllers'),
            base_path('modules'),
        ];

        $totalRoutes = 0;

        foreach ($paths as $path) {
            if (!is_dir($path)) {
                continue;
            }

            $finder = new Finder();
            $finder->files()->in($path)->name('*Controller.php');

            foreach ($finder as $file) {
                $className = $this->getClassNameFromFile(
                    $file->getRealPath(),
                    $file->getPath()
                );

                if ($className && class_exists($className)) {
                    $routes = $this->scanController($className);
                    if (!empty($routes)) {
                        $this->controllerRoutes[$className] = $routes;
                        $totalRoutes += count($routes);
                    }
                }
            }
        }

        if (empty($this->controllerRoutes)) {
            $this->warn('No annotation routes found.');
            return self::SUCCESS;
        }

        $this->writeHelperFile();
        $this->info("Routes generated to: " . base_path('routes/api.php'));
        $this->info("Found {$totalRoutes} routes.");

        return self::SUCCESS;
    }

    private function scanController(string $className): array
    {
        $routes = [];

        try {
            $classRef = new ReflectionClass($className);
        } catch (ReflectionException) {
            return $routes;
        }

        $classAttrs = collect($classRef->getAttributes());
        if ($classAttrs->isEmpty()) {
            return $routes;
        }

        $hasRequestAttr = $classAttrs->some(
            fn($attr) => $attr->getName() === RequestAttribute::class
        );
        if (!$hasRequestAttr) {
            return $routes;
        }

        $requestAttr = $classAttrs->first(
            fn($attr) => $attr->getName() === RequestAttribute::class
        );
        $requestInstance = $requestAttr->newInstance();
        $routePrefix = $requestInstance->routePrefix ?? '';
        $authGuard = $requestInstance->authGuard ?? null;
        $abilitiesPrefix = $requestInstance->abilitiesPrefix ?? '';
        $classMiddleware = $this->normalizeMiddleware($requestInstance->middleware);

        foreach ($classRef->getMethods() as $method) {
            $attrs = $method->getAttributes();
            if (empty($attrs)) {
                continue;
            }

            $methodName = $method->getName();

            foreach ($attrs as $attr) {
                if (in_array($attr->getName(), self::$mapping)) {
                    $instance = $attr->newInstance();

                    $authMiddleware = $this->buildAuthMiddleware(
                        $instance->authorize,
                        $authGuard,
                        $abilitiesPrefix,
                    );

                    $allMiddleware = array_values(array_unique(array_merge(
                        $authMiddleware,
                        $this->normalizeMiddleware($instance->middleware),
                        $classMiddleware,
                    )));

                    $routes[] = [
                        'method' => self::$httpMethodMap[$attr->getName()],
                        'uri' => $instance->route,
                        'prefix' => trim($routePrefix, '/'),
                        'controller' => $className,
                        'action' => $methodName,
                        'middleware' => $allMiddleware,
                    ];
                }
            }
        }

        return $routes;
    }

    private function buildAuthMiddleware(string|bool $authorize, ?string $authGuard, string $abilitiesPrefix): array
    {
        if (empty($authorize) || $authorize === false) {
            return [];
        }

        $authMiddleware = ['auth:sanctum'];

        if (!empty($authGuard)) {
            $authMiddleware[] = 'authGuard:' . $authGuard;
        } else {
            $authMiddleware[] = 'authGuard';
        }

        if (is_string($authorize) && !empty($abilitiesPrefix)) {
            $authMiddleware[] = 'abilities:' . $abilitiesPrefix . '.' . $authorize;
        } else {
            $authMiddleware[] = 'abilities:' . (is_string($authorize) ? $authorize : '');
        }

        return $authMiddleware;
    }

    private function normalizeMiddleware(string|array $middleware): array
    {
        if (empty($middleware)) {
            return [];
        }
        if (is_array($middleware)) {
            return $middleware;
        }
        return [$middleware];
    }

    private function getClassNameFromFile(string $filePath, string $fileDir): ?string
    {
        $content = file_get_contents($filePath);

        if (!preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatch)) {
            return $this->guessClassNameFromPath($filePath, $fileDir);
        }

        $namespace = trim($namespaceMatch[1]);
        $className = basename($filePath, '.php');

        return $namespace . '\\' . $className;
    }

    private function guessClassNameFromPath(string $filePath, string $fileDir): ?string
    {
        $basePath = base_path();
        $relativePath = str_replace($basePath, '', $fileDir);
        $namespace = str_replace('/', '\\', ltrim($relativePath, '/'));
        $namespace = trim($namespace, '\\');
        $className = basename($filePath, '.php');

        return empty($namespace) ? $className : $namespace . '\\' . $className;
    }

    private function writeHelperFile(): void
    {
        $outputPath = base_path('routes/api.php');

        $lines = [
            '<?php',
            '',
            'use Illuminate\Support\Facades\Route;',
            '',
            'Route::get(\'/\', function () {',
            '    return "Hello, thank you for using XinAdmin. ";',
            '});',
            '',
        ];

        // Each controller gets its own Route::controller group
        foreach ($this->controllerRoutes as $className => $routes) {
            $prefix = $routes[0]['prefix'];

            $shortName = substr(strrchr($className, '\\'), 1);
            $lines[] = "// {$shortName}";

            $chain = "Route::controller({$className}::class)";
            if ($prefix !== '') {
                $chain .= "->prefix('{$prefix}')";
            }
            $chain .= '->group(function () {';
            $lines[] = $chain;

            foreach ($routes as $route) {
                $uri = $route['uri'];
                if ($uri === '') {
                    $uri = '/';
                }

                $middlewareStr = '';
                if (!empty($route['middleware'])) {
                    $middlewareStr = "->middleware(['" . implode("', '", $route['middleware']) . "'])";
                }

                $lines[] = "    Route::{$route['method']}('{$uri}', '{$route['action']}'){$middlewareStr};";
            }

            $lines[] = '});';
            $lines[] = '';
        }

        File::put($outputPath, implode("\n", $lines));
    }
}
