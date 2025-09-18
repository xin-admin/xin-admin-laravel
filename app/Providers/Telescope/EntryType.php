<?php

namespace App\Providers\Telescope;

class EntryType
{
    public const CACHE = 'cache';
    public const QUERY = 'query';
    public const REDIS = 'redis';
    public const REQUEST = 'request';
    public const AUTH = 'auth';
}
