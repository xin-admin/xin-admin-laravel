<?php

namespace Xin;

use App\Exception\HttpResponseException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class Token
{
    /**
     * 数据表
     */
    protected string $table = 'token';

    /**
     * 数据表句柄
     */
    protected Builder $handler;

    /**
     * 加密方式
     */
    protected string $algo = 'ripemd160';

    /**
     * 密钥
     */
    protected string $algoKey = 'C5LziFeF2lNIOn4cMgZr17x80vHWAjwD';

    /**
     * 设置 Token
     */
    public function set(string $token, string $type, int $user_id, ?int $expire = null): bool
    {
        if (is_null($expire)) {
            $expire = 600;
        }
        $expire_time = $expire !== 0 ? time() + $expire : 0;
        $token = $this->getEncryptedToken($token);
        Db::table($this->table)->insert(['token' => $token, 'type' => $type, 'user_id' => $user_id, 'create_time' => time(), 'expire_time' => $expire_time]);

        // 每隔48小时清理一次过期缓存
        $time = time();
        $lastCacheCleanupTime = Cache::get('last_cache_cleanup_time');
        if (! $lastCacheCleanupTime || $lastCacheCleanupTime < $time - 172800) {
            Cache::set('last_cache_cleanup_time', $time);
            Db::table($this->table)->where('expire_time', '<', time())->where('expire_time', '>', 0)->delete();
        }

        return true;
    }

    public function get(string $token, bool $expirationException = true): array
    {
        $sql = Db::table($this->table)->where('token', $this->getEncryptedToken($token));
        trace($sql->toSql());
        trace($sql->getBindings());
        $data = Db::table($this->table)->where('token', $this->getEncryptedToken($token))->first();
        if (! $data) {
            throw new HttpResponseException(['msg' => 'Invalid Token'], 401);
        }
        // token过期-触发前端刷新token
        if ($data->expire_time && $data->expire_time <= time() && $expirationException) {
            if ($data->type == 'user-refresh' || $data->type == 'admin-refresh') {
                // 刷新 Token 过期重新登录
                throw new HttpResponseException(['msg' => 'Logout'], 401);
            }
            throw new HttpResponseException(['msg' => 'Refresh Token'], 202);
        }
        // 返回data
        $tokenData = [];
        $tokenData['token'] = $token;
        $tokenData['expires_in'] = $this->getExpiredIn($data->expire_time ?? 0);
        $tokenData['user_id'] = $data->user_id;
        $tokenData['type'] = $data->type;

        return $tokenData;
    }

    public function check(string $token, string $type, int $user_id, bool $expirationException = true): bool
    {
        $data = $this->get($token, $expirationException);
        if (! $data || (! $expirationException && $data['expire_time'] && $data['expire_time'] <= time())) {
            return false;
        }

        return $data['type'] == $type && $data['user_id'] == $user_id;
    }

    public function delete(string $token): bool
    {
        Db::table($this->table)->where('token', $this->getEncryptedToken($token))->delete();

        return true;
    }

    public function clear(string $type, int $user_id): bool
    {
        Db::table($this->table)->where('type', $type)->where('user_id', $user_id)->delete();

        return true;
    }

    protected function getEncryptedToken(string $token): string
    {
        return hash_hmac($this->algo, $token, $this->algoKey);
    }

    protected function getExpiredIn(int $exp_time): int
    {
        return $exp_time ? max(0, $exp_time - time()) : 365 * 86400;
    }
}
