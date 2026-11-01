<?php

namespace App\Console\Commands;

use App\Jobs\IndexCatalogItemJob;
use App\Models\CatalogIndex;
use App\Models\Item;
use Illuminate\Console\Command;
use Meilisearch\Client;

class SetupMeilisearch extends Command
{
    protected $signature = 'meilisearch:setup {--reindex : Rebuild released catalog items using the configured search driver}';

    protected $description = 'Configure the E-Libra Meilisearch catalog index';

    public function handle(): int
    {
        if ($this->option('reindex') && config('scout.driver') !== 'meilisearch') {
            return $this->reindex();
        }

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
            $index = $client->index((new CatalogIndex)->searchableAs());

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
                'library',
                'language',
                'content',
            ]);

            $this->info('✓ Searchable attributes configured');

            // Configure filterable attributes
            $index->updateFilterableAttributes([
                'campus_id',
                'library_id',
                'released',
                'item_type_id',
                'item_type_category_id',
                'department_id',
                'publication_year',
                'language',
            ]);

            $this->info('✓ Filterable attributes configured');

            // Configure sortable attributes
            $index->updateSortableAttributes([
                'title',
                'publication_year',
            ]);

            $this->info('✓ Sortable attributes configured');

            $this->newLine();
            $this->info('Meilisearch setup completed successfully!');

            if ($this->option('reindex')) {
                return $this->reindex();
            }

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('✗ Meilisearch setup failed.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function reindex(): int
    {
        $count = 0;

        Item::where('released', true)->chunkById(100, function ($items) use (&$count) {
            foreach ($items as $item) {
                IndexCatalogItemJob::dispatchSync($item->id);
                $count++;
            }
        });

        CatalogIndex::whereDoesntHave('item', function ($query) {
            $query->where('released', true);
        })->chunkById(100, function ($entries) {
            $entries->each->delete();
        });

        $this->info("Indexed {$count} released items. Draft and deleted items are excluded.");

        return self::SUCCESS;
    }
}

// STEP BY STEP GUIDE TO SETUP MEILISEARCH
// 1. Install Meilisearch on your local machine or server.
// 2. Start the Meilisearch server.
// 3. Configure the Meilisearch host and key in your Laravel .env file. [meron na to]
// 4. Run the command: php artisan meilisearch:setup
// 5. Verify that the setup was successful by checking the console output for success messages.

// Then run the command: [to populate the documents]
// php artisan meilisearch:setup --reindex to build and synchronize released catalog items.
// With a local Scout driver, --reindex does not connect to a Meilisearch server.

// TO SUMMARIZE:
// 1. php artisan meilisearch:setup
// 2. php artisan meilisearch:setup --reindex
// 3. php artisan queue:work
