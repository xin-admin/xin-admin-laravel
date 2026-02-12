<?php

namespace App\Common\Services\AnnoRoute;

use Closure;

interface CrudInterface
{

    /**
     * @param string $repository
     * @return Closure
     */
    public function store(string $repository): Closure;

}