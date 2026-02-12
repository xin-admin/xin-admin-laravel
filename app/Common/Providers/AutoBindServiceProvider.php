<?php

namespace App\Common\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class AutoBindServiceProvider extends ServiceProvider
{

    /**
     * 要排除的基类
     */
    protected array $excludeClasses = [
        'App\\Common\\Services\\BaseService',    // 服务基类
    ];

    /**
     * 要扫描的服务目录
     */
    protected array $scanDirectories = [
        'Common/Services',
        'Admin/Services',
        'Api/Services',
    ];

    public function register(): void
    {
        $classes = $this->scanClasses();

        foreach ($classes as $class) {
            if ($this->shouldBind($class)) {
                $this->app->bind($class, $class);
                // 如果需要单例模式，使用：$this->app->singleton($class, $class);
            }
        }
    }

    /**
     * 扫描目录获取所有类
     */
    protected function scanClasses(): array
    {
        $classes = [];

        foreach ($this->scanDirectories as $absolutePath) {
            $path = app_path($absolutePath);
            if (!is_dir($path)) {
                continue;
            }

            $namespace = $this->getNamespaceFromPath($path);

            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
            $regex = new RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);

            foreach ($regex as $file) {
                $filePath = $file[0];
                $relativePath = str_replace([$path, '.php'], '', $filePath);
                $relativePath = trim($relativePath, DIRECTORY_SEPARATOR);

                $className = str_replace(
                    DIRECTORY_SEPARATOR,
                    '\\',
                    $namespace . $relativePath
                );

                if (class_exists($className)) {
                    $classes[] = $className;
                }
            }
        }

        return $classes;
    }

    /**
     * 根据路径获取命名空间
     */
    protected function getNamespaceFromPath(string $path): string
    {
        $basePath = app_path();
        $relativePath = str_replace([$basePath, '\\'], ['', '/'], $path);
        $relativePath = trim($relativePath, '/');

        return 'App\\' . str_replace('/', '\\', $relativePath) . '\\';
    }

    /**
     * 判断是否应该绑定该类
     */
    protected function shouldBind(string $className): bool
    {
        // 排除抽象类
        if ((new \ReflectionClass($className))->isAbstract()) {
            return false;
        }

        if (!str_contains($className, 'Service')) {
            return false;
        }

        // 排除基类
        foreach ($this->excludeClasses as $exclude) {
            if (str_contains($className, $exclude) || class_basename($className) === $exclude) {
                return false;
            }
        }

        return true;
    }
}
