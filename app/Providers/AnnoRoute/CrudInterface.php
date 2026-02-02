<?php

namespace App\Providers\AnnoRoute;

use Closure;

interface CrudInterface
{

    /**
     * @param string $repository
     * @return Closure
     */
    public function store(string $repository): Closure;

}