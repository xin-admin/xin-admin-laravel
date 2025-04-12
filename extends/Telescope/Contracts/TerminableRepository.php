<?php

namespace Xin\Telescope\Contracts;

interface TerminableRepository
{
    /**
     * Perform any clean-up tasks needed after storing Telescope entries.
     */
    public function terminate(): void;
}
