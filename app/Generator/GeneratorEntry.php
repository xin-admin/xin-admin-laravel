<?php

namespace App\Generator;

use Illuminate\Support\Str;

class GeneratorEntry
{
    /**
     * The name of the entry.
     * 这是生成的名称，用作控制器、模型、请求的前缀
     *
     * @var string
     */
    private string $name;

    /**
     * The module of the entry.
     * 模块名称，如：Admin
     *
     * @var string
     */
    private string $module;

    /**
     * The path of the entry.
     * 文件路径，用于生成文件的二级路径，如模块为Admin，生成的名称为 user，路径为：admin/user，
     * 生成的控制器路径：  app/Admin/Controllers/Admin/User/UserController.php
     * 生成的模型路径：    app/Admin/Models/Admin/User/UserModel.php
     * 生成的表单请求路径： app/Admin/Requests/Admin/User/UserRequest.php
     *
     * @var string
     */
    private string $path;

    /**
     * 控制器路由的前缀
     *
     * @var string|mixed
     */
    private string $routePrefix;

    /**
     * 权限能力的前缀
     *
     * @var string|mixed
     */
    private string $abilitiesPrefix;

    /**
     * 前端路由地址，将根据路由地址自动生成文件地址，再依托UmiJs的约定路由前端自动生成此路由。
     *
     * @var string|mixed
     */
    private string $pageRoute;

    /**
     * 是否为文件
     * 如果是文件，则将路由的最后一个地址作为前端页面文件名称
     * 如果不是文件将创建路由文件夹。并将页面文件名作为 index.tsx
     *
     * @var bool
     */
    private bool $page_is_file = false;

    /**
     * 需要生成的CRUD路由
     * @var array|true[]
     */
    private array $crud = [
        'find' => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'query' => true,
    ];

    /**
     * Create a new gen entry instance.
     *
     * @param  array  $content
     * @return void
     */
    public function __construct(array $content)
    {
        $this->name = $content['name'];
        $this->module = $content['module'];
        $this->path = $this->toFilePath($content['path']);
        $this->routePrefix = $content['routePrefix'];
        $this->abilitiesPrefix = $content['abilitiesPrefix'];
        $this->pageRoute = $content['page']['route'];
        $this->page_is_file = $content['page']['is_file'] ?? false;
    }

    /**
     * Create a new entry instance.
     *
     * @param  mixed  ...$arguments
     * @return static
     */
    public static function make(...$arguments): static
    {
        return new static(...$arguments);
    }

    /**
     * 格式化用户输入路径，转换为文件路径
     *
     * @param string $path
     * @return string
     */
    private function toFilePath(string $path): string
    {
        // 统一替换分隔符为正斜杠
        $normalized = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);

        // 按目录分割并处理每个部分
        $parts = array_map(function($part) {
            return Str::studly($part); // 转为大驼峰
        }, explode(DIRECTORY_SEPARATOR, $normalized));

        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    /**
     * 获取模块路径
     *
     * @return string
     */
    public function modulePath(): string
    {
        return app_path($this->module);
    }

    /**
     * 获取控制器名称
     * @return string
     */
    public function controllerName(): string
    {
        return Str::studly($this->name) . 'Controller';
    }

    /**
     * 获取控制器文件名
     * @return string
     */
    public function controllerFileName(): string
    {
        return $this->controllerName() . '.php';
    }

    /**
     * 获取控制器路径
     * @return string
     */
    public function controllerPath(): string
    {
        if(empty($this->path)) {
            $path = DIRECTORY_SEPARATOR . 'Controllers';
        } else {
            $path = DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $this->path;
        }
        return $this->modulePath() . $path ;
    }

    /**
     * 获取控制器完整路径
     * @return string
     */
    public function controllerPathname(): string
    {
        return $this->controllerPath() . DIRECTORY_SEPARATOR . $this->controllerFileName();
    }

    /**
     * 获取控制器命名空间
     * @return string
     */
    public function controllerNamespace(): string
    {
        $path = Str::after($this->controllerPath(), app_path());
        return 'App' . str_replace(DIRECTORY_SEPARATOR, '\\', $path);
    }

    /**
     * 获取模型名称
     * @return string
     */
    public function modelName(): string
    {
        return $this->name . 'Model';
    }

    /**
     * 获取模型文件名称
     * @return string
     */
    public function modelFileName(): string
    {
        return $this->modelName() . '.php';
    }

    /**
     * 获取模型路径
     * @return string
     */
    public function modelPath(): string
    {
        if(empty($this->path)) {
            $path = DIRECTORY_SEPARATOR . 'Models';
        } else {
            $path = DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . $this->path;
        }
        return $this->modulePath() . $path;
    }

    /**
     * 获取模型完整路径
     * @return string
     */
    public function modelPathname(): string
    {
        return $this->modelPath() . DIRECTORY_SEPARATOR . $this->modelFileName();
    }

    /**
     * 获取模型命名空间
     * @return string
     */
    public function modelNamespace(): string
    {
        $path = Str::after($this->modelPath(), app_path());
        return 'App' . str_replace(DIRECTORY_SEPARATOR, '\\', $path);
    }

    /**
     * 获取表单请求名称
     * @return string
     */
    public function requestName(): string
    {
        return $this->name . 'Request';
    }

    /**
     * 获取表单请求文件名
     * @return string
     */
    public function requestFileName(): string
    {
        return $this->requestName() . '.php';
    }

    /**
     * 获取表单请求路径
     * @return string
     */
    public function requestPath(): string
    {
        if(empty($this->path)) {
            $path = DIRECTORY_SEPARATOR . 'Requests';
        } else {
            $path = DIRECTORY_SEPARATOR . 'Requests' . DIRECTORY_SEPARATOR . $this->path;
        }
        return $this->modulePath() . $path ;
    }

    /**
     * 获取表单请求完整路径
     * @return string
     */
    public function requestPathname(): string
    {
        return $this->requestPath() . DIRECTORY_SEPARATOR . $this->requestFileName();
    }

    /**
     * 获取表单请求命名空间
     * @return string
     */
    public function requestNamespace(): string
    {
        $path = Str::after($this->requestPath(), app_path());
        return 'App' . str_replace(DIRECTORY_SEPARATOR, '\\', $path);
    }

    /**
     * 获取前端类型接口名称
     * @return string
     */
    public function domainName(): string
    {
        return 'I' . $this->name;
    }

    /**
     * 获取前端类型接口文件名
     * @return string
     */
    public function domainFileName(): string
    {
        return 'i' . $this->name . '.ts';
    }

    /**
     * 获取前端类型接口路径
     * @return string
     */
    public function domainPath(): string
    {
        return web_path('src' . DIRECTORY_SEPARATOR . 'domain');
    }

    /**
     * 获取前端类型接口完整路径
     * @return string
     */
    public function domainPathname(): string
    {
        return $this->domainPath() . DIRECTORY_SEPARATOR . $this->domainFileName();
    }

    /**
     * 格式化前端页面路由
     * @return string
     */
    public function pageRoute(): string
    {
        return str_replace(['\\', '/', DIRECTORY_SEPARATOR], '/', $this->pageRoute);
    }

    /**
     * 获取前端页面文件名
     * @return string
     */
    public function pageFileName(): string
    {
        if($this->page_is_file) {
            $filePathArr = array_filter(explode('/', $this->pageRoute()));
            // 获取最后一个元素
            $fileName = array_pop($filePathArr);
            return Str::studly($fileName) . '.tsx';
        } else {
            return 'index.tsx';
        }
    }

    /**
     * 获取前端页面文件路径
     * @return string
     */
    public function pagePath(): string
    {
        $filePathArr = array_filter(explode('/', $this->pageRoute()));
        if($this->page_is_file) {
            // 删除最后一个元素
            array_pop($filePathArr);
        }
        $filePathArr = array_map(fn ($item) => Str::studly($item), $filePathArr);
        $path = implode(DIRECTORY_SEPARATOR, $filePathArr);
        return web_path('src' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * 获取前端页面文件完整路径
     * @return string
     */
    public function pagePathname(): string
    {
        return $this->pagePath() . DIRECTORY_SEPARATOR . $this->pageFileName();
    }

    public function toArray(): array
    {
        return [
            'controllerName' => $this->controllerName(),
            'controllerPath' => $this->controllerPath(),
            'controllerFileName' => $this->controllerFileName(),
            'controllerPathname' => $this->controllerPathname(),
            'controllerNamespace' => $this->controllerNamespace(),
            'modelName' => $this->modelName(),
            'modelPath' => $this->modelPath(),
            'modelFileName' => $this->modelFileName(),
            'modelPathname' => $this->modelPathname(),
            'modelNamespace' => $this->modelNamespace(),
            'requestName' => $this->requestName(),
            'requestPath' => $this->requestPath(),
            'requestFileName' => $this->requestFileName(),
            'requestPathname' => $this->requestPathname(),
            'requestNamespace' => $this->requestNamespace(),
            'domainName' => $this->domainName(),
            'domainPath' => $this->domainPath(),
            'domainFileName' => $this->domainFileName(),
            'domainPathname' => $this->domainPathname(),
            'pageRoute' => $this->pageRoute(),
            'pagePath' => $this->pagePath(),
            'pagePathname' => $this->pagePathname(),
            'pageFileName' => $this->pageFileName(),
        ];
    }

    public function toFiles(): array
    {
        return [
            'domainFile' => $this->domainPathname(),
            'controllerFile' => $this->controllerPathname(),
            'modelFile' => $this->modelPathname(),
            'requestFile' => $this->requestPathname(),
            'pageFile' => $this->pagePathname(),
        ];
    }

}