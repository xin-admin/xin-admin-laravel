<?php

namespace App\Exceptions;

use App\Enum\ShowType;
use RuntimeException;

class HttpResponseException extends RuntimeException
{
    /**
     * 响应消息
     */
    private string $msg;

    /**
     * 响应类型
     */
    private ShowType $showType;

    /**
     * 响应数据
     */
    private array $data;

    /**
     * 响应状态
     */
    private bool $success;

    public function __construct(array $data, int $code = 200)
    {
        $this->msg = $data['msg'] ?? '';
        $this->success = $data['success'] ?? true;
        if (empty($data['showType']) && $this->success) {
            $this->showType = ShowType::SUCCESS_MESSAGE;
        } elseif (empty($data['showType']) && ! $this->success) {
            $this->showType = ShowType::ERROR_MESSAGE;
        } else {
            $this->showType = $data['showType'];
        }
        $this->data = $data['data'] ?? [];
        parent::__construct($data['msg'] ?? '', $code);
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'success' => $this->success,
            'msg' => $this->msg,
            'showType' => $this->showType->value,
        ];
    }
}
