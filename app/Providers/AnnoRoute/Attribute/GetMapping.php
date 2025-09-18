<?php

namespace App\Providers\AnnoRoute\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class GetMapping extends Mapping
{
    public string $httpMethod = 'GET';

    public function __construct(
        public string $route = '',
        public string $authorize = '',
        public string | array $middleware = '',
    )
    {
    }
}