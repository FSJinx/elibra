<?php

namespace App\Console\Commands;

use App\Jobs\TestCatalogJob;
use Illuminate\Console\Command;

class TestCatalogQueue extends Command
{
    protected $signature = 'catalog:test-queue';

    protected $description = 'Test the catalog queue';

    public function handle(): int
    {
        TestCatalogJob::dispatch();

        $this->info('TestCatalogJob dispatched successfully.');

        return self::SUCCESS;
    }
}