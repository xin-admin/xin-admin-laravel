<?php
namespace Xin\AnnoRoute;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AnnoRoute::class, AnnoRoute::class);
    }

    public function boot(AnnoRoute $annoRoute): void
    {
        // 获取所有控制器
        $dir = app_path('Http');
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
        $filePath = str_replace(app_path('Http'), '', $controller->getPath());
        $filePath = str_replace('/', '\\', $filePath);
        return 'App\\Http'.$filePath.'\\'.basename($controller->getFileName(), '.php');
    }
}
