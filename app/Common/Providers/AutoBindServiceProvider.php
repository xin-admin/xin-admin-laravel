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
        'App\\Applications\\Common\\Services\\BaseService',    // 服务基类
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
        $absolutePath = app_path('Services');

        if (!is_dir($absolutePath)) {
            return [];
        }

        $classes = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($absolutePath));

        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);

        foreach ($regex as $file) {
            $filePath = $file[0];
            $relativePath = str_replace([$absolutePath, '.php'], '', $filePath);
            $relativePath = trim($relativePath, DIRECTORY_SEPARATOR);

            $className = str_replace(
                DIRECTORY_SEPARATOR,
                '\\',
                'App\\Services\\' . $relativePath
            );

            if (class_exists($className)) {
                $classes[] = $className;
            }
        }

        return $classes;
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

        if (!str_contains($className, 'Service') && !str_contains($className, 'Repository')) {
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

    /**
     * 获取已绑定的类列表（用于调试）
     */
    public function getBoundClasses(): array
    {
        $boundClasses = [];

        foreach ($this->scanConfig as $directory => $namespace) {
            $classes = $this->scanClasses($directory, $namespace);
            foreach ($classes as $class) {
                if ($this->shouldBind($class)) {
                    $boundClasses[] = $class;
                }
            }
        }

        return $boundClasses;
    }
}