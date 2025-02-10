<?php

namespace App\Attribute;

use Attribute;

#[Attribute]
class Authorize
{
    public function __construct(string $name = '') {}
}
