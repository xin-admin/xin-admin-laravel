<?php

namespace Xin\AnnoRoute;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AnnoRouteService implements AnnoRoute
{

    /**
     * register route
     * @param $path
     * @return void
     */
    public function register($path): void
    {
        $controllers = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
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
            $className = $this->getClassName($controller);
            RouteRegisterService::register($className);
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
