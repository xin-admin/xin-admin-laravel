<?php

return [


    /*
    |--------------------------------------------------------------------------
    | Token 服务配置
    |--------------------------------------------------------------------------
    |
    | 这个文件时XinAdmin token 服务的配置文件，用于设置 token 的储存方式与失效时间
    | 默认储存方式为数据库，数据表为 token 表
    |
    */


    /**
     * token 的储存记录表名称
     */
    'table' => 'token',
    /**
     * 加密方式
     * https://www.php.net/manual/zh/function.hash-algos.php
     */
    'algo' => 'ripemd160',
    /**
     * 密钥
     * 建议使用 32 位字符
     */
    'algoKey' => 'C5LziFeF2lNIOn4cMgZr17x80vHWAjwD',
    /**
     * admin 用户 token 过期时间
     * 默认 10 分钟，单位 秒
     */
    'expire_admin' => 600,
    /**
     * user 用户 token 过期时间
     * 默认 10 分钟，单位 秒
     */
    'expire_user' => 600,
    /**
     * admin-refresh 用户 token 过期时间
     * 默认 1 小时，单位 秒
     */
    'expire_refresh_admin' => 3600,
    /**
     * user-refresh 用户 token 过期时间
     * 默认 1 小时，单位 秒
     */
    'expire_refresh_user' => 3600,
];
