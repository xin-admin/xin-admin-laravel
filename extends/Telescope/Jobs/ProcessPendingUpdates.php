<?php

namespace Xin\Telescope\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Xin\Telescope\Contracts\EntriesRepository;
use Xin\Telescope\EntryUpdate;

class ProcessPendingUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The pending entry updates.
     *
     * @var Collection<int, EntryUpdate>
     */
    public Collection $pendingUpdates;

    /**
     * The number of times the job has been attempted.
     *
     * @var int
     */
    public int $attempt;

    /**
     * Create a new job instance.
     *
     * @param Collection<int, EntryUpdate> $pendingUpdates
     * @param int $attempt
     * @return void
     */
    public function __construct(Collection $pendingUpdates, int $attempt = 0)
    {
        $this->pendingUpdates = $pendingUpdates;
        $this->attempt = $attempt;
    }

    /**
     * Execute the job.
     *
     * @param EntriesRepository $repository
     * @return void
     */
    public function handle(EntriesRepository $repository): void
    {
        $this->attempt++;

        $delay = config('telescope.queue.delay');

        $repository->update($this->pendingUpdates)->whenNotEmpty(
            fn ($pendingUpdates) => static::dispatchIf(
                $this->attempt < 3, $pendingUpdates, $this->attempt
            )->onConnection(
                config('telescope.queue.connection')
            )->onQueue(
                config('telescope.queue.queue')
            )->delay(is_numeric($delay) && $delay > 0 ? now()->addSeconds($delay) : null),
        );
    }
}
