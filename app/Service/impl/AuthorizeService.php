<?php

namespace App\Service\impl;

use App\Service\IAuthorizeService;
use Xin\Token;

class AuthorizeService implements IAuthorizeService
{
    private string $token;

    private array $tokenData;

    private int $userId;

    private string $userName;

    public function __construct()
    {
        $request = app('request');
        $this->token = $request->header('x-token');
        $this->tokenData = (new Token)->get($this->token);
    }

    public function id(): int
    {
        return $this->userId;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function tokenData(): array
    {
        return $this->tokenData;
    }
}
