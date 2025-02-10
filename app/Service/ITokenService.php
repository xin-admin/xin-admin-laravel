<?php

namespace App\Service;

use App\Enum\TokenEnum;

interface ITokenService
{
    /**
     * 设置 Token
     *
     * @param  TokenEnum  $type  类型
     * @param  int  $user_id  用户ID
     * @return ?string 设置成功返回 token
     */
    public function set(int $user_id, TokenEnum $type): ?string;

    /**
     * 获取 Token
     *
     * @param  string  $token  token
     * @param  bool  $expirationException  过期是否抛出异常
     */
    public function get(string $token, bool $expirationException = true): array;

    /**
     * 验证 Token
     *
     * @param  string  $token  token
     * @param  string  $type  类型
     * @param  int  $user_id  用户ID
     * @param  bool  $expirationException  过期是否抛出异常
     * @return bool 删除成功返回 true
     */
    public function check(string $token, string $type, int $user_id, bool $expirationException = true): bool;

    /**
     * 删除 Token
     *
     * @param  string  $token  token
     * @return bool 删除成功返回 true
     */
    public function delete(string $token): bool;

    /**
     * 清除 Token
     *
     * @param  string  $type
     * @param  int  $user_id
     * @return bool
     */
    public function clear(string $type, int $user_id): bool;
}
