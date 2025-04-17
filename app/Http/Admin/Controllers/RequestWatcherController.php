<?php

namespace App\Http\Admin\Controllers;

use App\Http\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\Telescope\Contracts\EntriesRepository;
use Xin\Telescope\Storage\EntryQueryOptions;

#[RequestMapping("/system/watcher/request")]
class RequestWatcherController extends BaseController
{

    // #[Authorize('system.watcher.request.list')]
    #[GetMapping]
    public function list(Request $request, EntriesRepository $storage): JsonResponse
    {
        return $this->success(
            $storage->get(EntryQueryOptions::fromRequest($request))
        );
    }

}