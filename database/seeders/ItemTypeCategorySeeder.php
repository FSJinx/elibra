<?php

namespace Database\Seeders;

use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use Illuminate\Database\Seeder;

class ItemTypeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'academic',
                'categories' => [
                    ['name' => 'capstone project', 'code' => 'cp'],
                    ['name' => 'case study', 'code' => 'cs'],
                    ['name' => 'dissertation', 'code' => 'gt'],
                    ['name' => 'feasibility study', 'code' => 'fs'],
                    ['name' => 'practicum report', 'code' => 'pr'],
                    ['name' => 'project study', 'code' => 'ps'],
                    ['name' => 'research paper', 'code' => 'rp'],
                    ['name' => 'terminal report', 'code' => 'tr'],
                    ['name' => 'thesis', 'code' => 'ut'],
                ],
            ],
            [
                'name' => 'book',
                'categories' => [
                    // General Collections
                    ['name' => 'fiction', 'code' => 'gc'],
                    ['name' => 'non-fiction', 'code' => 'gc'],
                    ['name' => 'novel', 'code' => 'gc'],
                    ['name' => 'textbook', 'code' => 'gc'],

                    // Reference Collections
                    ['name' => 'almanac', 'code' => 'rc'],
                    ['name' => 'atlas', 'code' => 'rc'],
                    ['name' => 'bibliography', 'code' => 'rc'],
                    ['name' => 'dictionary', 'code' => 'rc'],
                    ['name' => 'directory', 'code' => 'rc'],
                    ['name' => 'encyclopedia', 'code' => 'rc'],
                    ['name' => 'thesaurus', 'code' => 'rc'],
                    ['name' => 'yearbook', 'code' => 'rc'],

                    // Filipiniana Collections
                    ['name' => 'filipiniana', 'code' => 'fc'],
                    ['name' => 'reserved', 'code' => 'rc'],
                ],
            ],
            [
                'name' => 'multimedia',
                'categories' => [
                    ['name' => 'audio book', 'code' => 'abm'],
                    ['name' => 'audio recording', 'code' => 'abr'],
                    ['name' => 'cd', 'code' => 'cd'],
                    ['name' => 'dvd', 'code' => 'dvd'],
                    ['name' => 'video', 'code' => 'vid'],
                ],
            ],
            [
                'name' => 'serial',
                'categories' => [
                    ['name' => 'annual reports', 'code' => 'ar'],
                    ['name' => 'journal', 'code' => 'js'],
                    ['name' => 'magazine', 'code' => 'ms'],
                    ['name' => 'newspaper', 'code' => 'np'],
                    ['name' => 'newsletter', 'code' => 'nl'],
                    ['name' => 'periodicals', 'code' => 'p'],
                    ['name' => 'vertical', 'code' => 'v'],
                ],
            ],
        ];

        foreach ($types as $type) {
            $item_type = ItemType::where('name', '=', $type['name'], 'and')->first();

            if ($item_type) {
                foreach ($type['categories'] as $category) {
                    ItemTypeCategory::create([
                        'name' => $category['name'],
                        'code' => $category['code'],
                        'item_type_id' => $item_type['id'],
                    ]);
                }
            }
        }
    }
}
