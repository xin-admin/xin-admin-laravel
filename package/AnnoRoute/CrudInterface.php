<?php

namespace Xin\AnnoRoute;

use Closure;

interface CrudInterface
{

    /**
     * @param string $repository
     * @return Closure
     */
    public function store(string $repository): Closure;

}
