<?php

namespace App\Providers\Telescope\Storage;

use App\Providers\Telescope\Contracts\EntriesRepository;
use App\Providers\Telescope\IncomingEntry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

class StorageRepository implements EntriesRepository
{
    public function get(EntryQueryOptions $options): array
    {
        $fileName = $options->type. '-' . $options->date . '.jsonl';
        $storage = Storage::disk('telescope');
        if (!$storage->exists($fileName)) {
            return [];
        }
        $filePath = storage_path('telescope/' . $fileName);

        $collection = LazyCollection::make(function () use ($filePath) {
            $file = fopen($filePath, 'r');

            while (($line = fgets($file)) !== false) {
                yield json_decode($line, true);
            }
            fclose($file);
        });

        return [
            'data' => $collection->forPage($options->page, $options->limit)->values()->all(),
            'total' => $collection->count(),
            'pageSize' => $options->limit,
            'current' => $options->page,
        ];
    }

    public function store(array|Collection $entries): void
    {
        if ($entries->isEmpty()) {
            return;
        }
        $date = date('Y-m-d');
        $contents = [];
        foreach (config('telescope.watchers') as $key => $watcher) {
            if (! is_string($key)) continue;
            if( $watcher === false ) continue;
            $contents[$key] = [];
        }
        $entries->each(function (IncomingEntry $chunked) use (&$contents) {
            if(empty($chunked->content)) {
                return;
            }
            $contents[$chunked->type][] = $chunked->getContentAsString();
        });
        $storage = Storage::disk('telescope');
        foreach ($contents as $type => $entries) {
            if (empty($entries)) {
                continue;
            }
            $fileName = $type. '-' . $date . '.jsonl';
            // 文件不存在创建文件
            if (!$storage->exists($fileName)) {
                $storage->put($fileName, implode("\n", $entries));
                continue;
            }
            $storage->append($fileName, implode("\n", $entries));
        }
    }

    public function clear(): void
    {
        // TODO: Implement clear() method.
    }
}