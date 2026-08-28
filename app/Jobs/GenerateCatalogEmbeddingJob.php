<?php

namespace App\Jobs;

use App\Models\CatalogIndex;
use App\Services\EmbeddingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateCatalogEmbeddingJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $catalogIndexId
    ){}

    /**
     * Execute the job.
     */
    public function handle(EmbeddingService $embeddingService): void
    {
        $catalogIndex = CatalogIndex::find($this->catalogIndexId);

        if(!$catalogIndex) { return; }
       
        $embeddingService->generate($catalogIndex);
    }
}
