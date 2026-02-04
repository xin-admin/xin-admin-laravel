<?php
namespace App\Providers;


use App\Services\AnnoRoute\AnnoRouteService;
use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteServiceProvider extends ServiceProvider
{

    public function boot(AnnoRouteService $annoRoute): void
    {
        // 获取所有控制器
        $dir = app_path('Controllers');
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
