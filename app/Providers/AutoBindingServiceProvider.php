<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

/**
 * 自动绑定接口与实现类
 */
class AutoBindingServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->autoBind();
    }

    protected function autoBind(): void
    {
        $dir = app_path("Service");
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $interfaceFiles = [];
        $classFiles = [];
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getExtension() === 'php') { // 确保是文件并且是 .php 文件
                // 获取文件在Service 中的路径
                $filePath = str_replace(app_path("Service"), '', $fileInfo->getPath());
                $className = 'App\\Service' . $filePath . "\\" . basename($fileInfo->getFileName(), ".php");
                if (interface_exists($className)) {
                    $interfaceFiles[] = $className;
                    continue;
                }
                if(class_exists($className)) {
                    $classFiles[] = $className;
                }
            }
        }

        foreach ($interfaceFiles as $interface) {
            try {
                $interfaceReflection = new ReflectionClass($interface);
                foreach ($classFiles as $implementation) {
                    $implementationClass = new ReflectionClass($implementation);
                    if($implementationClass instanceof $interfaceReflection) {
                        $this->app->bind($interface, $implementation);
                    }
                }
            } catch (\ReflectionException $e) {
                dd($e);
            }
        }
    }
}