<?php

namespace App\Http\Admin\Controllers;

use App\Http\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\Telescope\Contracts\EntriesRepository;
use Xin\Telescope\Storage\EntryQueryOptions;

#[RequestMapping('/system/watcher', 'system.watcher')]
class WatcherController extends BaseController
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