<?php

namespace App\Services;

use App\Models\CatalogEmbedding;
use App\Models\CatalogIndex;

class EmbeddingService
{
    public function generate(CatalogIndex  $catalogIndex): array
    {
        $text = $catalogIndex->context;

        //Temporary for testing purpose
        $embedding = [
            0.0123,
            -0.0456,
            0.0789,
        ];

        return CatalogEmbedding::updateOrCreate(
            [
                'catalog_index_id' => $catalogIndex->id,
            ],
            [
                'embedding' => $embedding,
                'model' => 'test',
                'dimensions' => count($embedding),
            ]
        );
    }
}