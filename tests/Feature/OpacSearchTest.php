<?php

namespace Tests\Feature;

use App\Models\CatalogIndex;
use App\Models\Item;
use App\Models\User;
use App\Services\CatalogIndexService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Tests\TestCase;

class OpacSearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['scout.driver' => 'collection', 'scout.queue' => false]);
        foreach ([
            '1_create_campuses_table.php', '1_create_libraries_table.php',
            '3_create_items_table.php', '3_create_item_authors_table.php',
            '3_create_item_publishers_table.php', '4_create_item_types_table.php',
            '4_create_item_type_categories_table.php', '5_create_authors_table.php',
            '5_create_languages_table.php', '5_create_publishers_table.php',
            '6_create_accessions_table.php', '100_create_catalog_indices_table.php',
        ] as $migration) {
            (require database_path('migrations/'.$migration))->up();
        }
        DB::table('campuses')->insert([['id' => 1, 'name' => 'Campus A'], ['id' => 2, 'name' => 'Campus B']]);
        DB::table('libraries')->insert([['id' => 1, 'name' => 'Main Library', 'campus_id' => 1], ['id' => 2, 'name' => 'Other Library', 'campus_id' => 2]]);
        DB::table('item_types')->insert(['id' => 1, 'slug' => 'book', 'name' => 'Book']);
        DB::table('item_type_categories')->insert(['id' => 1, 'name' => 'Textbook', 'code' => 'TB', 'item_type_id' => 1]);
        DB::table('languages')->insert(['id' => 1, 'code' => 'en', 'name' => 'English']);
    }

    private function item(array $attributes = []): Item
    {
        return Item::create([
            'title' => 'Database Systems', 'description' => 'Relational design and SQL',
            'call_number' => '005.74 D123', 'keywords' => ['research'], 'released' => true,
            'item_type_id' => 1, 'item_type_category_id' => 1, 'library_id' => 1,
            'language_id' => 1, 'pages' => '250', 'isbn_issn' => '978-1234567890',
            ...$attributes,
        ]);
    }

    public function test_guest_and_patron_can_search_released_items_without_an_index(): void
    {
        $item = $this->item();
        $this->item(['title' => 'Private draft', 'released' => false]);
        $deleted = $this->item(['title' => 'Deleted item']);
        $deleted->delete();

        $this->getJson('/api/opac/search')->assertOk()->assertJsonPath('data.results.total', 1)
            ->assertJsonPath('data.results.data.0.item_id', $item->id);
        $this->actingAs(new User(['role' => 'patron']), 'api');
        $this->getJson('/api/opac/search?query=database')->assertOk()
            ->assertJsonPath('data.results.total', 1)
            ->assertJsonPath('data.results.data.0.library', 'Main Library')
            ->assertJsonPath('data.results.data.0.branch_id', 1);
        $this->assertSame(0, CatalogIndex::count());
    }

    public function test_library_alias_campus_and_classification_filters_use_current_tables(): void
    {
        $first = $this->item();
        $second = $this->item(['title' => 'Other campus book', 'library_id' => 2]);
        foreach (['branch_id', 'library_id'] as $filter) {
            $this->getJson('/api/opac/search?'.$filter.'=2')->assertOk()
                ->assertJsonPath('data.results.total', 1)->assertJsonPath('data.results.data.0.item_id', $second->id);
        }
        $this->getJson('/api/opac/search?campus_id=1&item_type_id=1&item_type_category_id=1')->assertOk()
            ->assertJsonPath('data.results.total', 1)->assertJsonPath('data.results.data.0.item_id', $first->id);
        $this->getJson('/api/opac/search?branch_id=999')->assertUnprocessable();
        $this->getJson('/api/opac/search?page=0')->assertUnprocessable();
        $this->getJson('/api/opac/search?sort=unsupported')->assertUnprocessable();
    }

    public function test_keywords_authors_call_numbers_and_synonyms_are_searchable(): void
    {
        $item = $this->item();
        DB::table('authors')->insert(['id' => 1, 'first_name' => 'Jane', 'last_name' => 'Santos']);
        $item->authors()->attach(1);
        foreach (['research', 'Jane Santos', '005.74', 'db'] as $query) {
            $this->getJson('/api/opac/search?'.http_build_query(['query' => $query]))->assertOk()
                ->assertJsonPath('data.results.total', 1)->assertJsonPath('data.results.data.0.item_id', $item->id);
        }
        $this->getJson('/api/opac/search?query=doesnotexist')->assertOk()->assertJsonPath('data.results.total', 0);
        $this->getJson('/api/opac/search?query=%25')->assertOk()->assertJsonPath('data.results.total', 0);
    }

    public function test_publication_year_sorting_filtering_and_pagination(): void
    {
        $first = $this->item(['title' => 'Alpha']);
        $second = $this->item(['title' => 'Zulu']);
        DB::table('item_publishers')->insert([
            ['item_id' => $first->id, 'year_published' => 2020],
            ['item_id' => $second->id, 'year_published' => 2024],
        ]);
        $this->getJson('/api/opac/search?sort=title&order=desc&per_page=1&page=2')->assertOk()
            ->assertJsonPath('data.results.total', 2)->assertJsonPath('data.results.current_page', 2)
            ->assertJsonPath('data.results.data.0.title', 'Alpha');
        $this->getJson('/api/opac/search?sort=publication_year&order=desc')->assertOk()
            ->assertJsonPath('data.results.data.0.publication_year', 2024);
        $this->getJson('/api/opac/search?publication_year=2020')->assertOk()
            ->assertJsonPath('data.results.total', 1)->assertJsonPath('data.results.data.0.item_id', $first->id);
    }

    public function test_details_use_flat_item_fields_publishers_and_real_copies(): void
    {
        $item = $this->item();
        DB::table('publishers')->insert(['id' => 1, 'name' => 'University Press', 'address' => 'Campus']);
        DB::table('item_publishers')->insert(['item_id' => $item->id, 'publisher_id' => 1, 'year_published' => 2025]);
        DB::table('accessions')->insert([
            ['item_id' => $item->id, 'accession_number' => 'TB-001', 'status' => 'available', 'deleted_at' => null],
            ['item_id' => $item->id, 'accession_number' => 'TB-002', 'status' => 'reserved', 'deleted_at' => null],
            ['item_id' => $item->id, 'accession_number' => 'TB-003', 'status' => 'available', 'deleted_at' => now()],
        ]);
        $this->getJson('/api/opac/item/'.$item->id)->assertOk()
            ->assertJsonPath('data.library_id', 1)->assertJsonPath('data.campus', 'Campus A')
            ->assertJsonPath('data.pages', '250')->assertJsonPath('data.isbn_issn', '978-1234567890')
            ->assertJsonPath('data.publisher', 'University Press')->assertJsonPath('data.publication_year', 2025)
            ->assertJsonPath('data.copies_count', 2)->assertJsonPath('data.available_copies', 1)
            ->assertJsonPath('data.status', 'Available')->assertJsonCount(2, 'data.accessions');
        $draft = $this->item(['released' => false]);
        $this->getJson('/api/opac/item/'.$draft->id)->assertNotFound();
        $this->getJson('/api/opac/item/999')->assertNotFound();
        $this->getJson('/api/opac/item/invalid')->assertNotFound();
        $item->delete();
        $this->getJson('/api/opac/item/'.$item->id)->assertNotFound();
    }

    public function test_reindex_is_repeatable_and_excludes_unreleased_items(): void
    {
        $item = $this->item();
        $this->item(['released' => false]);
        $this->artisan('meilisearch:setup', ['--reindex' => true])->assertSuccessful();
        $this->artisan('meilisearch:setup', ['--reindex' => true])->assertSuccessful();
        $this->assertSame(1, CatalogIndex::count());
        $entry = CatalogIndex::first();
        $this->assertSame(1, $entry->library_id);
        $this->assertTrue($entry->toSearchableArray()['released']);
        $item->update(['released' => false]);
        app(CatalogIndexService::class)->index($item);
        $this->assertSame(0, CatalogIndex::count());
        $item->update(['released' => true]);
        app(CatalogIndexService::class)->index($item);
        $this->assertSame(1, CatalogIndex::withTrashed()->count());
        $item->delete();
        $this->artisan('meilisearch:setup', ['--reindex' => true])->assertSuccessful();
        $this->assertSame(0, CatalogIndex::count());
    }

    public function test_meilisearch_uses_library_filters_and_current_item_payload(): void
    {
        $item = $this->item();
        $entry = app(CatalogIndexService::class)->index($item);
        $engine = \Mockery::mock(Engine::class);
        $engine->shouldReceive('paginate')->once()->withArgs(function ($builder, $perPage, $page) {
            $filters = collect($builder->wheres)->pluck('value', 'field')->all();

            return $builder->query === 'database' && $perPage === 10 && $page === 1
                && $filters['released'] === true && $filters['library_id'] === 1
                && $filters['campus_id'] === 1
                && $builder->orders === [['column' => 'title', 'direction' => 'asc']];
        })->andReturn(['total' => 1]);
        $engine->shouldReceive('map')->once()->andReturn(new Collection([$entry]));
        $engine->shouldReceive('getTotalCount')->once()->andReturn(1);
        $this->app->make(EngineManager::class)->extend('meilisearch', fn () => $engine);
        config(['scout.driver' => 'meilisearch']);

        $this->getJson('/api/opac/search?query=database&branch_id=1&campus_id=1&sort=title')->assertOk()
            ->assertJsonPath('data.results.total', 1)
            ->assertJsonPath('data.results.data.0.item_id', $item->id)
            ->assertJsonPath('data.results.data.0.library_id', 1);
    }
}
