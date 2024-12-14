<?php

namespace App\Middleware;

use App\Attribute\AdminController;
use App\Attribute\ApiController;
use App\Attribute\Authorize;
use App\Exceptions\AuthorizeException;
use App\Exceptions\HttpResponseException;
use App\Models\AdminUserModel;
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
        if (! isset($routeAction['controller'])) {
            return $next($request);
        }
        [$controller, $method] = explode('@', $routeAction['controller']);
        // 获取控制器类
        try {
            $reflection = new ReflectionClass($controller);
        } catch (\ReflectionException $e) {
            throw new HttpResponseException(['success' => false, 'msg' => '权限验证失败，未找到控制器']);
        }
        // 权限验证白名单
        try {
            $noPermission = $reflection->getProperty('noPermission')->getDefaultValue();
        } catch (\ReflectionException $e) {
            $noPermission = [];
        }
        // 权限验证白名单
        if (! empty($noPermission) && in_array($method, $noPermission)) {
            return $next($request);
        }
        // 获取方法
        try {
            $methodReflection = $reflection->getMethod($method);
        } catch (\ReflectionException $e) {
            throw new HttpResponseException(['success' => false, 'msg' => '权限验证失败，未找到方法']);
        }
        // 获取方法内容
        $authorize = $methodReflection->getAttributes(Authorize::class);
        if (count($authorize) > 0) {
            $authorize = $authorize[0]->newInstance();
            $authKey = $authorize->name;
        } else {
            $authKey = '';
        }

        // 后台控制器
        if (count($reflection->getAttributes(AdminController::class)) > 0) {
            $token = $request->header('x-token');
            if (empty($token)) {
                throw new HttpResponseException(['success' => false, 'msg' => '请先登录!'], 401);
            }
            $tokenData = (new Token)->get($token);
            if ($tokenData['type'] != 'admin' || ! empty($tokenData['user_id'])) {
                throw new HttpResponseException(['success' => false, 'msg' => '请先登录!'], 401);
            }
            $adminUser = AdminUserModel::find($tokenData['user_id']);
            if (! $adminUser) {
                throw new HttpResponseException(['success' => false, 'msg' => '请先登录!'], 401);
            }
            if (! $adminUser['status']) {
                throw new HttpResponseException(['success' => false, 'msg' => '账户已被禁用!'], 401);
            }
            if (! empty($authKey)) {
                // 权限缓存
                if (! Cache::has('admin_role_'.$adminUser->role_id)) {
                    $role = $adminUser->role;
                    if ($role) {
                        $rules = $role->rules;
                    } else {
                        throw new HttpResponseException(['success' => false, 'msg' => '用户未分批角色组!'], 401);
                    }
                    Cache::add('admin_role_'.$adminUser->role_id, $rules);
                } else {
                    $rules = Cache::get('admin_role_'.$adminUser->role_id);
                }
                $rules = array_map('strtolower', $rules);
                if (! in_array($authKey, $rules)) {
                    throw new AuthorizeException('您没有权限操作！！');
                }
            }

        }
        // Api控制器
        if (count($reflection->getAttributes(ApiController::class)) > 0) {
            $token = $request->header('x-user-token');
            if (empty($token)) {
                throw new HttpResponseException(['success' => false, 'msg' => '请先登录!'], 401);
            }
            $tokenData = (new Token)->get($token);
            if ($tokenData['type'] != 'user' || ! empty($tokenData['user_id'])) {
                throw new HttpResponseException(['success' => false, 'msg' => '请先登录!'], 401);
            }
            $adminUser = AdminUserModel::find($tokenData['user_id']);
            if (! $adminUser) {
                throw new HttpResponseException(['success' => false, 'msg' => '请先登录!'], 401);
            }
            if (! $adminUser['status']) {
                throw new HttpResponseException(['success' => false, 'msg' => '账户已被禁用!'], 401);
            }
        }

        return $next($request);
    }
}
