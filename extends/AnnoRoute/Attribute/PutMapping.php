<?php

namespace Xin\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class PutMapping extends Mapping
{
    public string $httpMethod = 'PUT';

    public function __construct(
        public string $route = '',
        public string $authorize = '',
        public string | array $middleware = '',
    )
    {
    }
}