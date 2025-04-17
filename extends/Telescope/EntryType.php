<?php

namespace Xin\Telescope;

class EntryType
{
    public const BATCH = 'batch';
    public const CACHE = 'cache';
    public const EXCEPTION = 'exception';
    public const QUERY = 'query';
    public const REDIS = 'redis';
    public const REQUEST = 'request';
    public const SCHEDULED_TASK = 'schedule';
    public const CLIENT_REQUEST = 'client_request';
}
