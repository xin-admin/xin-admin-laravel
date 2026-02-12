<?php
namespace App\Common\Providers;


use App\Common\Services\AnnoRoute\AnnoRouteService;
use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * 要扫描的控制器目录
     */
    protected array $scanDirectories = [
        'Admin/Controllers',
        'Api/Controllers',
        'Common/Controllers',
    ];

    public function boot(AnnoRouteService $annoRoute): void
    {
        foreach ($this->scanDirectories as $directory) {
            $dir = app_path($directory);
            if (!is_dir($dir)) {
                continue;
            }

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
