<?php

namespace Xin\Telescope\Console;

use Illuminate\Console\Command;
use Xin\Telescope\Contracts\ClearableRepository;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'telescope:clear')]
class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telescope:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all Telescope data from storage';

    /**
     * Execute the console command.
     *
     * @param ClearableRepository $storage
     * @return void
     */
    public function handle(ClearableRepository $storage): void
    {
        $storage->clear();

        $this->info('Telescope entries cleared!');
    }
}
