<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['code' => 'en',  'name' => 'english'],
            ['code' => 'fil', 'name' => 'tagalog'],
            ['code' => 'kor', 'name' => 'korean'],
            ['code' => 'tha', 'name' => 'thai'],
            ['code' => 'jap', 'name' => 'japanese'],
            ['code' => 'chinman', 'name' => 'chinese (mandarin)'],
            ['code' => 'chincan', 'name' => 'chinese (cantonese)'],
        ])->each(fn ($lang) => Language::create($lang));
    }
}
