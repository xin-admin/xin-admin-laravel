<?php

namespace Xin\AnnoRoute\Attribute;

use Attribute;

#[Attribute]
class Authorize
{
    public function __construct(
        public string $name = ''
    ) {}
}
