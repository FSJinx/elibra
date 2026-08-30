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
            0.089,
            -0.0101,
            0.0123, 
        ];

        return CatalogEmbedding::updateOrCreate(
            [
                'catalog_index_id' => $catalogIndex->id,
            ],
            [
                'embedding' => $embedding,
                'model' => 'sample',
                'dimensions' => count($embedding),
            ]
        );
    }
}