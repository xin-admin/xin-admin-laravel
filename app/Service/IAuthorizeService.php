<?php

namespace App\Service;

interface IAuthorizeService
{
    /**
     * 获取 app 权限
     * @return static
     */
    public function app(): static;

    /**
     * 获取 admin 权限
     * @return static
     */
    public function admin(): static;

    /**
     * 获取用户ID
     * @return int
     */
    public function id(): int;

    /**
     * 获取token
     * @return string
     */
    public function token(): string;

    /**
     * 获取token数据
     * @return array
     */
    public function tokenData(): array;

    /**
     * 获取用户类型
     * @return string
     */
    public function userType(): string;

    /**
     * 获取用户信息
     * @return array
     */
    public function userInfo(): array;

    /**
     * 获取用户权限
     * @return array
     */
    public function permission(): array;
}
