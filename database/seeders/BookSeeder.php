<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Authorship;
use App\Models\Campus;
use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use App\Models\Library;
use App\Services\ItemService;
use Database\Factories\BooksFactory;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(ItemService $itemService): void
    {
        // Gamitin ang firstOrFail para mag-throw ng malinaw na exception kapag kulang ang database seeds
        $campus = Campus::where('code', 'ISU-E')->firstOrFail();
        $library = Library::where('campus_id', $campus->id)->firstOrFail();

        $itemType = ItemType::where('slug', 'book')->firstOrFail();

        // Kunin ang lahat ng available category IDs bilang isang Collection
        $categoryIds = ItemTypeCategory::where('item_type_id', $itemType->id)->pluck('id');

        $authorIds = Author::pluck('id');
        $authorshipIds = Authorship::where('item_type_id', $itemType->id)->pluck('id');

        $records = collect(range(1, rand(100, 500)))->map(fn () => BooksFactory::new()->definition())->all();

        collect($records)->each(function ($item) use ($itemService, $itemType, $categoryIds, $library, $authorIds, $authorshipIds) {
            $item = $itemService->create([
                ...$item,
                'subtitle' => "{$itemType->name} for {$library->name}",
                'item_type_id' => $itemType->id,
                'item_type_category_id' => $categoryIds->random(),
                'library_id' => $library->id,
                'language_id' => 1,
                'released' => fake()->boolean(90),
            ]);

            $item->syncAuthors($authorIds->random(rand(1, min(3, $authorIds->count())))->map(fn ($id) => ['id' => $id, 'authorship_id' => $authorshipIds->random()])->all());
        });
    }
}
