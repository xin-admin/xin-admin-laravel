<?php

namespace App\Enum;

enum TokenEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case REFRESH_ADMIN = 'admin-refresh';
    case REFRESH_USER = 'user-refresh';

    public function expire(): int
    {
        return match ($this) {
            TokenEnum::ADMIN => config('token.expire_admin'),
            TokenEnum::USER => config('token.expire_user'),
            TokenEnum::REFRESH_ADMIN => config('token.expire_refresh_admin'),
            TokenEnum::REFRESH_USER => config('token.expire_refresh_user'),
        };
    }
}
