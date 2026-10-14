<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use App\Models\Library;
use App\Services\ItemService;
use Illuminate\Database\Seeder;

class SerialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(ItemService $itemService): void
    {
        // Gamitin ang firstOrFail para mag-throw ng malinaw na exception kapag kulang ang database seeds
        $campus = Campus::where('code', 'ISU-E')->firstOrFail();
        $library = Library::where('campus_id', $campus->id)->firstOrFail();

        $itemType = ItemType::where('slug', 'serials')->firstOrFail();

        // Kunin ang lahat ng available category IDs bilang isang Collection
        $categoryIds = ItemTypeCategory::where('item_type_id', $itemType->id)->pluck('id');

        collect([
            [
                'title' => 'ACM Transactions on Database Systems',
                'description' => 'A quarterly peer-reviewed scientific journal covering database system architecture, query processing, data models, and storage engines.',
                'call_number' => '005.7405 A25',
                'keywords' => ['Database Systems', 'Computer Science', 'Academic Journal', 'Serial'],
            ],
            [
                'title' => 'Philippine Journal of Science',
                'description' => 'A multidisciplinary peer-reviewed publication of the Department of Science and Technology (DOST) featuring research in natural sciences, engineering, and technology.',
                'call_number' => '505 P55',
                'keywords' => ['Science and Technology', 'Philippine Research', 'Peer-Reviewed', 'Serial'],
            ],
            [
                'title' => 'National Geographic Magazine',
                'description' => 'A monthly periodical specializing in geography, cartography, world culture, environmental science, and photojournalism.',
                'call_number' => '910.5 N21',
                'keywords' => ['Geography', 'Culture', 'Environment', 'Magazine'],
            ],
            [
                'title' => 'IEEE Software',
                'description' => 'A bimonthly professional magazine focusing on software engineering practices, software architecture, agile methodologies, and emerging developer trends.',
                'call_number' => '005.105 I22',
                'keywords' => ['Software Engineering', 'Programming', 'IEEE', 'Periodical'],
            ],
            [
                'title' => 'Journal of Educational Psychology',
                'description' => 'A quarterly academic journal published by the American Psychological Association covering empirical research on learning, instruction, and cognitive development.',
                'call_number' => '370.1505 J82',
                'keywords' => ['Educational Psychology', 'Pedagogy', 'Learning Methods', 'Academic Journal'],
            ],
            [
                'title' => 'The Economist',
                'description' => 'A weekly news publication focusing on global economics, international relations, business trends, finance, and political developments.',
                'call_number' => '330.05 E19',
                'keywords' => ['Economics', 'Global Politics', 'Business', 'News Magazine'],
            ],
            [
                'title' => 'Harvard Business Review',
                'description' => 'A bimonthly management magazine providing research-based insights on strategic leadership, organizational behavior, innovation, and corporate strategy.',
                'call_number' => '658.005 H26',
                'keywords' => ['Business Management', 'Leadership', 'Strategy', 'Periodical'],
            ],
            [
                'title' => 'Computers & Education',
                'description' => 'An international peer-reviewed journal highlighting technological integration in learning, e-learning platforms, and digital educational tools.',
                'call_number' => '371.33405 C73',
                'keywords' => ['Educational Technology', 'E-Learning', 'EdTech', 'Academic Journal'],
            ],
            [
                'title' => 'Time Magazine',
                'description' => 'A news magazine offering reporting, analysis, and commentary on national and international news, politics, health, technology, and culture.',
                'call_number' => '051 T58',
                'keywords' => ['Current Events', 'World News', 'Culture', 'Weekly Magazine'],
            ],
            [
                'title' => 'IEEE Transactions on Pattern Analysis and Machine Intelligence',
                'description' => 'A monthly academic journal publishing foundational research in computer vision, pattern recognition, machine learning, and artificial intelligence.',
                'call_number' => '006.3105 I34',
                'keywords' => ['Machine Learning', 'Computer Vision', 'Artificial Intelligence', 'Academic Journal'],
            ],
        ])->each(function ($item) use ($itemService, $itemType, $categoryIds, $library) {
            $itemService->create([
                ...$item,
                'subtitle' => "Serials resource for {$library->name}",
                'item_type_id' => $itemType->id,
                'item_type_category_id' => $categoryIds->random(), // <- Kukuha nang tama at random mula sa Collection
                'library_id' => $library->id,
                'language_id' => 1,
            ]);
        });
    }
}
