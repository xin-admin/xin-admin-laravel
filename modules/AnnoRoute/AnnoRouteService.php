<?php

namespace Modules\AnnoRoute;

use Symfony\Component\Finder\Finder;

class AnnoRouteService implements AnnoRoute
{

    /**
     * register route
     * @param string|array $path
     * @return void
     */
    public function register($path): void
    {
        $paths = is_array($path) ? $path : [$path];

        foreach ($paths as $p) {
            $this->registerFromPath($p);
        }
    }

    /**
     * 从指定路径注册路由
     */
    private function registerFromPath(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }


        $finder = new Finder();
        $finder->files()
            ->in($path)
            ->name('*Controller.php');

        foreach ($finder as $controller) {
            $className = $this->getClassNameFromFile(
                $controller->getRealPath(),
                $controller->getPath()
            );
            if ($className && class_exists($className)) {
                RouteRegisterService::register($className);
            }
        }
    }

    /**
     * 从文件路径和所在目录解析类名
     */
    private function getClassNameFromFile(string $filePath, string $fileDir): ?string
    {
        // 读取文件内容获取命名空间
        $content = file_get_contents($filePath);

        if (!preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatch)) {
            // 没有命名空间，尝试使用 PSR-0 规则
            return $this->guessClassNameFromPath($filePath, $fileDir);
        }

        $namespace = trim($namespaceMatch[1]);
        $className = basename($filePath, '.php');

        return $namespace . '\\' . $className;
    }

    /**
     * 当文件没有命名空间时，通过路径猜测类名
     */
    private function guessClassNameFromPath(string $filePath, string $fileDir): ?string
    {
        // 获取相对于项目根目录的路径
        $basePath = base_path();
        $relativePath = str_replace($basePath, '', $fileDir);

        // 将路径转换为命名空间
        $namespace = str_replace('/', '\\', ltrim($relativePath, '/'));

        // 移除开头的反斜杠并转换路径分隔符
        $namespace = trim($namespace, '\\');

        $className = basename($filePath, '.php');

        // 如果命名空间为空，直接返回类名
        if (empty($namespace)) {
            return $className;
        }

        return $namespace . '\\' . $className;
    }
}
