<?php

namespace App\Http\Controllers;

use App\Attribute\Auth;
use App\Attribute\Monitor;
use App\Trait\RequestJson;
use Illuminate\Support\Facades\Route;
use ReflectionObject;

// 基础控制器
abstract class BaseController
{
    use RequestJson;

    /**
     * 构造方法
     */
    public function __construct()
    {
        // 运行注解
        $ref = new ReflectionObject($this);
        try {
            $action = Route::current()->getActionMethod();
            $attrs = $ref->getMethod($action)->getAttributes();
            foreach ($attrs as $attr) {
                if ($attr->getName() === Auth::class) {
                    $attr->newInstance();
                }
                if ($attr->getName() === Monitor::class) {
                    $attr->newInstance();
                }
            }
        } catch (\ReflectionException $e) {
            $this->throwError('注解验证失败：'.$e->getMessage());
        }
    }

    /**
     * 获取树状列表
     */
    protected function getTreeData(&$list, int $parentId = 0): array
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['pid'] == $parentId) {
                $children = $this->getTreeData($list, $item['id']);
                ! empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }
        return $data;
    }
}
