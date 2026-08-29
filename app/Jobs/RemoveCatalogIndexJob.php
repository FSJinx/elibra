<?php

namespace App\Jobs;

use App\Models\CatalogIndex;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RemoveCatalogIndexJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $itemId
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        CatalogIndex::where('item_id', $this->itemId)->delete();
    }
}
