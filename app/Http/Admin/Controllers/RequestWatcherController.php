<?php

namespace App\Http\Admin\Controllers;

use App\Http\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\Telescope\EntryType;
use Xin\Telescope\Storage\EntryQueryOptions;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\Telescope\Contracts\EntriesRepository;

#[RequestMapping("/system/watcher/request")]
class RequestWatcherController extends BaseController
{

    // #[Authorize('system.watcher.request.list')]
    #[GetMapping]
    public function list(Request $request, EntriesRepository $storage): JsonResponse
    {
        return $this->success($storage->get(
            EntryType::REQUEST,
            EntryQueryOptions::fromRequest($request)
        )->toArray());
    }


    #[GetMapping("/{id}")]
    public function show(EntriesRepository $storage, $id): JsonResponse
    {
        $entry = $storage->find($id);

        return $this->success([
            'entry' => $entry,
            'batch' => $storage->get(null, EntryQueryOptions::forBatchId($entry->batchId)->limit(-1)),
        ]);
    }


}