<?php

namespace App\Console\Commands;

use App\Models\CatalogIndex;
use Illuminate\Console\Command;
use Meilisearch\Client;

class SetupMeilisearch extends Command
{
    protected $signature = 'meilisearch:setup';

    protected $description = 'Configure the E-Libra Meilisearch catalog index';

    public function handle(): int
    {
        $this->info('Setting up Meilisearch...');

        try {
            $client = new Client(
                config('scout.meilisearch.host'),
                config('scout.meilisearch.key')
            );

            // Check connection
            $client->health();

            $this->info('✓ Connected to Meilisearch');

            // Get/create catalog index
            $index = $client->index('catalog_indices');

            // Configure searchable attributes
            $index->updateSearchableAttributes([
                'title',
                'subtitle',
                'description',
                'call_number',
                'keywords',
                'authors',
                'item_type',
                'item_type_category',
                'campus',
                'branch',
                'language',
                'content',
            ]);

            $this->info('✓ Searchable attributes configured');

            // Configure filterable attributes
            $index->updateFilterableAttributes([
                'campus_id',
                'branch_id',
                'item_type_id',
                'item_type_category_id',
                'department_id',
                'publication_year',
                'language',
            ]);

            $this->info('✓ Filterable attributes configured');

            // Configure sortable attributes
            $index->updateSortableAttributes([
                'publication_year',
            ]);

            $this->info('✓ Sortable attributes configured');

            $this->newLine();
            $this->info('Meilisearch setup completed successfully!');

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('✗ Meilisearch setup failed.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}

// STEP BY STEP GUIDE TO SETUP MEILISEARCH
// 1. Install Meilisearch on your local machine or server.
// 2. Start the Meilisearch server.
// 3. Configure the Meilisearch host and key in your Laravel .env file. 
// 4. Run the command: php artisan meilisearch:setup
// 5. Verify that the setup was successful by checking the console output for success messages.

// Then run the command: [to populate the documents] 
// php artisan scout:import "App\Models\CatalogIndex" to import the existing catalog data into Meilisearch.

// TO SUMMARIZE:
// 1. php artisan meilisearch:setup
// 2. php artisan scout:import "App\Models\CatalogIndex"
// 3. php artisan queue:work 