<?php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Authorize
{
    public function __construct(string $name = ''){

    }
}