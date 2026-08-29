<?php

namespace App\Jobs;

use App\Services\CatalogIndexService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Item;
use Illuminate\Support\Facades\Log;

class IndexCatalogItemJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $itemId
    ) {}

    public function handle(CatalogIndexService $indexService): void
    {
        $item = Item::find($this->itemId);

        if (!$item) {
            Log::warning('Catalog item not found.', [
                'item_id' => $this->itemId,
            ]);

            return;
        }

        $indexService->index($item);
    }
}