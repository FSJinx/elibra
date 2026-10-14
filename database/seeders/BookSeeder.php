<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use App\Models\Library;
use App\Services\ItemService;
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

        collect([
            [
                'title' => 'Fundamentals of Database Systems: Design and Implementation',
                'description' => 'A comprehensive textbook covering relational database management systems, SQL querying, indexing techniques, and distributed database architectures.',
                'call_number' => '005.74 F85',
                'keywords' => ['Database Systems', 'SQL', 'Data Modeling', 'Textbook'],
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'description' => 'A classic novel exploring racial injustice and the loss of innocence in the American South through the eyes of young Scout Finch.',
                'call_number' => '813.54 L47t',
                'keywords' => ['Classic Literature', 'Fiction', 'Legal Drama', 'Novel'],
            ],
            [
                'title' => 'Principles of Microeconomics',
                'description' => 'An introductory economics textbook analyzing market supply and demand, consumer behavior, price elasticity, and market structures.',
                'call_number' => '338.5 P95',
                'keywords' => ['Microeconomics', 'Economics', 'Supply and Demand', 'Textbook'],
            ],
            [
                'title' => 'The Great Gatsby',
                'description' => 'A Jazz Age novel examining wealth, obsession, and the decay of the American Dream in 1920s Long Island.',
                'call_number' => '813.52 F55g',
                'keywords' => ['American Literature', 'Fiction', 'Jazz Age', 'Novel'],
            ],
            [
                'title' => 'Human Anatomy and Physiology: A Visual Approach',
                'description' => 'An essential reference textbook detailing the structure and function of the human body systems with full-color diagrams and clinical correlates.',
                'call_number' => '612 H91',
                'keywords' => ['Anatomy', 'Physiology', 'Medicine', 'Textbook'],
            ],
            [
                'title' => '1984',
                'description' => 'A dystopian novel depicting a totalitarian regime governed by Big Brother, where surveillance and thought control are absolute.',
                'call_number' => '823.912 O79n',
                'keywords' => ['Dystopian', 'Science Fiction', 'Totalitarianism', 'Novel'],
            ],
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'description' => 'A practical guide for software engineers on writing readable, maintainable, and efficient code through refactoring and best practices.',
                'call_number' => '005.1 M37c',
                'keywords' => ['Software Engineering', 'Programming', 'Clean Code', 'Non-Fiction'],
            ],
            [
                'title' => 'The Catcher in the Rye',
                'description' => 'A coming-of-age novel following teenager Holden Caulfield as he wanders New York City, dealing with alienation and identity.',
                'call_number' => '813.54 S16c',
                'keywords' => ['Coming-of-Age', 'Fiction', 'Youth Culture', 'Novel'],
            ],
            [
                'title' => 'Organic Chemistry: Structure and Function',
                'description' => 'A foundational chemistry textbook focusing on reaction mechanisms, molecular structure, synthesis, and organic functional groups.',
                'call_number' => '547 O68',
                'keywords' => ['Organic Chemistry', 'Chemical Reactions', 'Science', 'Textbook'],
            ],
            [
                'title' => 'Dune',
                'description' => 'An epic science fiction novel set on the desert planet Arrakis, exploring politics, religion, ecology, and power dynamics.',
                'call_number' => '813.54 H53d',
                'keywords' => ['Science Fiction', 'Epic Fantasy', 'Space Opera', 'Novel'],
            ],
        ])->each(function ($item) use ($itemService, $itemType, $categoryIds, $library) {
            $itemService->create([
                ...$item,
                'subtitle' => "Book material for {$library->name}",
                'item_type_id' => $itemType->id,
                'item_type_category_id' => $categoryIds->random(), // <- Kukuha nang tama at random mula sa Collection
                'library_id' => $library->id,
                'language_id' => 1,
            ]);
        });
    }
}
