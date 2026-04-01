<?php

namespace Modules\AnnoRoute;

interface AnnoRoute
{

    /**
     * register route
     *
     * @param string $path
     * @return void
     */
    public function register(string $path): void;

}
