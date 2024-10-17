<?php

namespace App\Attribute;

use App\Enum\ShowType;
use App\Exception\HttpResponseException;
use App\Models\Admin\AdminGroupModel;
use App\Models\Admin\AdminModel;
use App\Models\Admin\AdminRuleModel;
use App\Models\User\UserGroupModel;
use App\Models\User\UserModel;
use App\Models\User\UserRuleModel;
use Attribute;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use Xin\Token;

/**
 * 接口权限注解
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Auth
{
    public string $token;

    /**
     * 权限初始化，获取请求用户验证权限
     */
    public function __construct(string $key = '')
    {
        if (! function_exists('app')) {
            return;
        }
        // 获取权限标识
        $rules = self::getRules();
        if (empty($key)) {
            return;
        }
        try {
            // 使用反射机制获取当前控制器的 AuthName
            $currentRoute = Route::current();
            $controller = $currentRoute->getControllerClass();
            $reflection = new ReflectionClass($controller);
            $authName = $reflection->getProperty('authName')->getDefaultValue();
            if (! $authName) {
                $controllerName = class_basename($controller);
                $controllerNameWithoutSuffix = str_replace('Controller', '', $controllerName);
                $authName = str_replace('\\', '.', $controllerNameWithoutSuffix);
            }
        } catch (\ReflectionException $e) {
            self::throwError('获取控制器AuthName失败'.$e->getMessage());
        }
        $authKey = strtolower($authName.'.'.$key);
        // 权限不存在添加权限
        $authList = AdminRuleModel::query()->pluck('key')->toArray();
        $authList = array_map('strtolower', $authList);
        if (! in_array($authKey, $authList)) {
            self::addAuth($key, $authName);
        }
        if (! in_array($authKey, $rules)) {
            $data = [
                'success' => false,
                'msg' => '暂无权限',
                'showType' => ShowType::WARN_NOTIFICATION->value,
                'description' => '请联系管理员获取权限，如果你是管理员请检查权限菜单中是否有本接口的权限！',
            ];
            throw new HttpResponseException($data);
        }
    }

    /**
     * 获取用户ID，未登录抛出错误
     */
    public static function getUserId(): int
    {
        $token = self::getUserToken();
        $tokenData = self::getTokenData($token);
        if ($tokenData['type'] != 'user' || ! isset($tokenData['user_id'])) {
            self::throwError('用户ID不存在！');
        }

        return $tokenData['user_id'];
    }

    /**
     * 获取用户信息（用户端）未登录抛出错误
     */
    public static function getUserInfo(): array
    {
        $user_id = self::getUserId();
        $user = UserModel::query()
            ->where('id', $user_id)
            ->with('avatar')
            ->first();
        if (! $user) {
            self::throwError('用户不存在！');
        }

        return $user->toArray();
    }

    /**
     * 获取管理员ID
     */
    public static function getAdminId(): int
    {
        $token = self::getToken();
        $tokenData = self::getTokenData($token);
        if ($tokenData['type'] != 'admin' || ! isset($tokenData['user_id'])) {
            self::throwError('管理员ID不存在！');
        }

        return $tokenData['user_id'];
    }

    /**
     * 获取管理员信息（管理端）未登录抛出错误
     */
    public static function getAdminInfo(): array
    {
        $user_id = self::getAdminId();
        $user = AdminModel::query()
            ->where('id', $user_id)
            ->with('avatar')
            ->first();
        if (! $user) {
            self::throwError('用户不存在！');
        }

        return $user->toArray();
    }

    /**
     * 获取 Token （用户端）未登录抛出错误
     */
    private static function getUserToken(): string
    {
        $token = request()->header('x-user-token');
        if (! $token) {
            self::throwError('请先登录！');
        }

        return $token;
    }

    /**
     * 获取 Token （管理端） 未登录抛出错误
     */
    private static function getToken(): string
    {
        $token = request()->header('x-token');
        if (! $token) {
            self::throwError('请先登录！');
        }

        return $token;
    }

    /**
     * 获取 Token Data
     */
    private static function getTokenData($token): array
    {
        $tokenData = (new Token)->get($token);
        if (! $tokenData) {
            self::throwError('请先登录！');
        }

        return $tokenData;
    }

    /**
     * 获取权限
     */
    private static function getRules(): array
    {
        $currentRoute = Route::current();
        $appName = $currentRoute->getPrefix();
        if ($appName == 'admin') {
            $token = self::getToken();
        } else {
            $token = self::getUserToken();
        }
        $tokenData = self::getTokenData($token);
        if ($tokenData['type'] == 'user' && $appName == 'api') {
            $userInfo = self::getUserInfo();
            if (! $userInfo['status']) {
                self::throwError('账户已被禁用！');
            }
            $group = UserGroupModel::query()->find($userInfo['group_id']);
            $rules = UserRuleModel::query()->whereIn('id', $group->rules)->pluck('key')->toArray();
            return array_map('strtolower', $rules);
        } elseif ($tokenData['type'] == 'admin' && $appName == 'admin') {
            $adminInfo = self::getAdminInfo();
            if (! $adminInfo['status']) {
                self::throwError('账户已被禁用！');
            }
            $group = AdminGroupModel::query()->find($adminInfo['group_id']);
            $rules = AdminRuleModel::query()->whereIn('id', $group->rules)->pluck('key')->toArray();
            return array_map('strtolower', $rules);
        } else {
            self::throwError('Token 类型错误！');
        }

    }

    /**
     * 权限验证错误
     *
     * @throws HttpResponseException
     */
    private static function throwError(string $msg = ''): void
    {
        throw new HttpResponseException(['success' => false, 'msg' => $msg], 401);
    }

    /**
     * 如果是新写的接口，权限不存在，自动添加按钮/接口权限
     *
     * @throws HttpResponseException
     */
    private static function addAuth(string $key, string $authName): void
    {
        $p_auth = AdminRuleModel::query()->where('key', $authName)->first();
        if (! $p_auth) {
            return;
        }
        AdminRuleModel::query()->create([
            'name' => '新接口',
            'sort' => 0,
            'type' => 2,
            'pid' => $p_auth->id,
            'key' => $authName.'.'.$key,
        ]);
        $data = [
            'success' => false,
            'msg' => '权限更新',
            'showType' => ShowType::WARN_NOTIFICATION->value,
            'description' => '权限自动更新，请刷新页面重试~',
        ];
        throw new HttpResponseException($data);
    }
}
