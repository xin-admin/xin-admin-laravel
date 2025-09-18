<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\Telescope\Contracts\EntriesRepository;
use App\Providers\Telescope\Storage\EntryQueryOptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[RequestMapping('/system/watcher', 'system.watcher')]
class SysWatcherController extends BaseController
{
    #[GetMapping('/request', 'request')]
    public function request(Request $request, EntriesRepository $storage): JsonResponse
    {
        $options = EntryQueryOptions::fromRequest($request);
        $options->type('request');
        return $this->success($storage->get($options));
    }

    #[GetMapping('/auth', 'auth')]
    public function auth(Request $request, EntriesRepository $storage): JsonResponse
    {
        $options = EntryQueryOptions::fromRequest($request);
        $options->type('auth');
        return $this->success($storage->get($options));
    }

    #[GetMapping('/query', 'query')]
    public function queryLog(Request $request, EntriesRepository $storage): JsonResponse
    {
        $options = EntryQueryOptions::fromRequest($request);
        $options->type('query');
        return $this->success($storage->get($options));
    }

    #[GetMapping('/cache', 'cache')]
    public function cache(Request $request, EntriesRepository $storage): JsonResponse
    {
        $options = EntryQueryOptions::fromRequest($request);
        $options->type('cache');
        return $this->success($storage->get($options));
    }

    #[GetMapping('/redis', 'redis')]
    public function redis(Request $request, EntriesRepository $storage): JsonResponse
    {
        $options = EntryQueryOptions::fromRequest($request);
        $options->type('redis');
        return $this->success($storage->get($options));
    }

}