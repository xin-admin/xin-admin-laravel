<?php

namespace App\Service\impl;

use App\Exceptions\HttpResponseException;
use App\Models\AdminUserModel;
use App\Service\IAuthorizeService;
use App\Service\ITokenService;
use Illuminate\Http\Request;

class AuthorizeService implements IAuthorizeService
{
    private Request $request;

    private ITokenService $tokenService;

    private string $token;

    private array $tokenData;

    private int $userId;

    private string $userType;

    private array $userInfo;

    public function __construct(Request $request, ITokenService $token)
    {
        $this->request = $request;
        $this->tokenService = $token;
    }

    public function app(): static
    {
        $this->token = $this->request->header('x-user-token');
        $this->tokenData = $this->tokenService->get($this->token);
        if ($this->tokenData['type'] != 'user' || ! empty($this->tokenData['user_id'])) {
            throw new HttpResponseException(['success' => false, 'msg' => '请先登录!'], 401);
        }
        $this->userId = $this->tokenData['user_id'];
        $this->userType = $this->tokenData['type'];

        return $this;
    }

    public function admin(): static
    {
        $this->token = $this->request->header('x-token');
        $this->tokenData = $this->tokenService->get($this->token);
        if ($this->tokenData['type'] != 'admin' || empty($this->tokenData['user_id'])) {
            throw new HttpResponseException(['success' => false, 'msg' => '用户类型错误，请先登录!'], 401);
        }
        $this->userId = $this->tokenData['user_id'];
        $this->userType = $this->tokenData['type'];
        $user = AdminUserModel::find($this->userId);
        if (! $user) {
            throw new HttpResponseException(['success' => false, 'msg' => '用户不存在，请先登录!'], 401);
        }
        if (! $user->status) {
            throw new HttpResponseException(['success' => false, 'msg' => '账户已被禁用!'], 401);
        }
        $this->userInfo = $user->toArray();

        return $this;
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

    public function userType(): string
    {
        return $this->userType;
    }

    public function userInfo(): array
    {
        return $this->userInfo;
    }

    public function permission(): array
    {
        if ($this->userType == 'user') {
            return [];
        }

        return $this->userInfo['rules'];
    }
}
