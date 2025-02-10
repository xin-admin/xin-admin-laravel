<?php

return [
    'table' => 'token', // mysql or redis
    'algoKey' => 'C5LziFeF2lNIOn4cMgZr17x80vHWAjwD', // 密钥
    'algo' => 'ripemd160', // 加密方式
    'expire_admin' => 600,
    'expire_user' => 600,
    'expire_refresh_admin' => 3600,
    'expire_refresh_user' => 3600,
];
