<?php

namespace App\Providers\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class DeleteMapping extends Mapping
{
    public string $httpMethod = 'DELETE';

    public function __construct(
        public string $route = '',
        public string $authorize = '',
        public string | array $middleware = '',
    )
    {
    }
}