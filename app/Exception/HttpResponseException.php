<?php


namespace App\Exception;

use RuntimeException;

class HttpResponseException extends RuntimeException
{
    private array $resData = [];

    public function __construct(array $data, int $code = 200)
    {
        $this->resData = $data;
        parent::__construct($data['msg'] ?? '', $code);
    }

    public function getResData(): array
    {
        return $this->resData;
    }
}
