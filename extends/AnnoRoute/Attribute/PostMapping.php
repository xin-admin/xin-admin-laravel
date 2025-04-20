<?php

namespace Xin\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class PostMapping extends Mapping
{
    public string $httpMethod = 'POST';

    public function __construct(
        public string $route = '',
        public string $authorize = '',
        public string | array $middleware = '',
    )
    {
    }
}