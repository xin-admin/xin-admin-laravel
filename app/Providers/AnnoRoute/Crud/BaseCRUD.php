<?php

namespace App\Providers\AnnoRoute\Crud;

use App\Providers\AnnoRoute\BaseAttribute;
use App\Support\Trait\RequestJson;
use Closure;

abstract class BaseCRUD extends BaseAttribute
{
    use RequestJson;

    /**
     * @param string $repository
     * @return Closure
     */
    abstract public function store(string $repository): Closure;
}