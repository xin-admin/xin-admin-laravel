<?php

namespace Xin\Telescope\Storage;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use Xin\Telescope\Contracts\EntriesRepository;
use Xin\Telescope\EntryType;
use Xin\Telescope\IncomingEntry;

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

        $contents = [
            EntryType::BATCH => [],
            EntryType::CACHE => [],
            EntryType::EXCEPTION => [],
            EntryType::QUERY => [],
            EntryType::REDIS => [],
            EntryType::REQUEST => [],
            EntryType::SCHEDULED_TASK => [],
            EntryType::CLIENT_REQUEST => [],
        ];
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