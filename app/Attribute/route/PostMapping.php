<?php

namespace App\Attribute\route;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class PostMapping
{
    public mixed $route;

    public function __construct($route = '')
    {
        $this->route = $route;
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}