<?php

namespace App\Service;

interface IAuthorizeService
{
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
}
