<?php

namespace App\Jobs;

use App\Models\Item;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class IndexCatalogItemJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $itemId
    ) {}

    public function handle(): void
    {
        // $item = Item::find($this->itemId);
        $item = Item::with([
            'academic',
            'authors',
            'itemType',
            'itemTypeCategory',
            'branch',
        ])->find($this->itemId);

        if (!$item) {
            Log::warning('Catalog item not found.', [
                'item_id' => $this->itemId,
            ]);

            return;
        }

        Log::info('Catalog item ready for indexing.', [
            'item_id' => $item->id,
            'title' => $item->title,
            'type' => $item->ItemType?->name,
        ]);
    }
}