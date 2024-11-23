<?php

namespace App\Middleware;

use App\Attribute\AdminController;
use App\Attribute\ApiController;
use App\Attribute\Authorize;
use App\Exception\AuthorizeException;
use App\Models\Admin\AdminGroupModel;
use App\Models\Admin\AdminModel;
use App\Models\Admin\AdminRuleModel;
use App\Models\User\UserGroupModel;
use App\Models\User\UserModel;
use App\Models\User\UserRuleModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use Xin\Token;

class CheckPermission
{

    public function handle(Request $request, Closure $next)
    {
        $routeAction = $request->route()->getAction();
        if (!isset($routeAction['controller'])) {
            return $next($request);
        }
        [$controller, $method] = explode('@', $routeAction['controller']);
        // 获取控制器类
        try {
            $reflection = new ReflectionClass($controller);
        } catch (\ReflectionException $e) {
            throw new AuthorizeException('权限验证失败，控制器不存在！！');
        }
        // 权限验证白名单
        try {
            $noPermission = $reflection->getProperty('noPermission')->getDefaultValue();
        }catch (\ReflectionException $e) {
            $noPermission = [];
        }
        // 权限验证白名单
        if(!empty($noPermission) && in_array($method, $noPermission)) {
            return $next($request);
        }
        // 获取方法
        try {
            $methodReflection = $reflection->getMethod($method);
        } catch (\ReflectionException $e) {
            throw new AuthorizeException('权限验证失败，' . $e->getMessage());
        }
        // 获取方法内容
        $authorize = $methodReflection->getAttributes(Authorize::class);
        if(count($authorize) > 0) {
            $authorize = $authorize[0]->newInstance();
            $authKey = $authorize->name;
        }else {
            $authKey = '';
        }

        // 后台控制器
        if(count($reflection->getAttributes(AdminController::class)) > 0) {
            $token = $request->header('x-token');
            if(empty($token)) {
                throw new AuthorizeException('请先登录！！');
            }
            $tokenData = (new Token)->get($token);
            if ($tokenData['type'] != 'admin' || ! empty($tokenData['user_id'])) {
                throw new AuthorizeException('请先登录！！');
            }
            $user = AdminModel::query()
                ->where('id', $tokenData['user_id'])
                ->first();
            if (! $user) {
                throw new AuthorizeException('请先登录！！');
            }
            if (! $user['status']) {
                throw new AuthorizeException('账户已被禁用！！');
            }
            if(empty($authKey)) {
                return $next($request);
            }
            // 权限缓存
            if(! Cache::has('admin_group_' . $user['group_id'])) {
                $group = AdminGroupModel::query()->find($user['group_id']);
                $rules = AdminRuleModel::query()->whereIn('id', $group->rules)->pluck('key')->toArray();
                Cache::add('admin_group_' . $user['group_id'], $rules);
            } else {
                $rules = Cache::get('admin_group_' . $user['group_id']);
            }
            $rules = array_map('strtolower', $rules);
            if (! in_array($authKey, $rules)) {
                throw new AuthorizeException('您没有权限操作！！');
            }
        }
        // Api控制器
        if(count($reflection->getAttributes(ApiController::class)) > 0) {
            $token = $request->header('x-user-token');
            if(empty($token)) {
                throw new AuthorizeException('请先登录！！');
            }
            $tokenData = (new Token)->get($token);
            if ($tokenData['type'] != 'user' || ! empty($tokenData['user_id'])) {
                throw new AuthorizeException('请先登录！！');
            }
            $user = UserModel::query()
                ->where('id', $tokenData['user_id'])
                ->first();
            if (! $user) {
                throw new AuthorizeException('请先登录！！');
            }
            if (! $user['status']) {
                throw new AuthorizeException('账户已被禁用！！');
            }
            if(empty($authKey)) {
                return $next($request);
            }
            // 权限缓存
            if(! Cache::has('user_group_' . $user['group_id'])) {
                $group = UserGroupModel::query()->find($user['group_id']);
                $rules = UserRuleModel::query()->whereIn('id', $group->rules)->pluck('key')->toArray();
                Cache::add('user_group_' . $user['group_id'], $rules);
            } else {
                $rules = Cache::get('user_group_' . $user['group_id']);
            }
            $rules = array_map('strtolower', $rules);
            if (! in_array($authKey, $rules)) {
                throw new AuthorizeException('您没有权限操作！！');
            }
        }
        return $next($request);
    }

}