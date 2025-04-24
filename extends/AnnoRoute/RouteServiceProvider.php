<?php
namespace Xin\AnnoRoute;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AnnoRoute::class, AnnoRoute::class);
    }

    public function boot(AnnoRoute $annoRoute): void
    {
        // 获取所有控制器
        $dir = app_path();
        $controllers = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($controllers as $controller) {
            if (! $controller->isFile()) {
                continue;
            }
            if (! str_contains($controller->getFilename(), 'Controller')) {
                continue;
            }
            if ($controller->getFilename() === 'BaseController.php') {
                continue;
            }
            $className = static::getClassName($controller);
            $annoRoute->register($className);
        }
    }

    /**
     * get class name
     */
    private function getClassName($controller): string
    {
        $filePath = str_replace(app_path(), '', $controller->getPath());
        $filePath = str_replace('/', '\\', $filePath);
        return 'App'.$filePath.'\\'.basename($controller->getFileName(), '.php');
    }
}
