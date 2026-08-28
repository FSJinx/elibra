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
        $languages = [
            ['code' => 'en', 'name' => 'english'],
            ['code' => 'fil', 'name' => 'tagalog'],
            ['code' => 'kor', 'name' => 'korean'],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
