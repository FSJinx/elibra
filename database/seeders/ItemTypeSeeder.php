<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['slug' => 'thesis', 'name' => 'thesis', 'has_electronic_file' => true],
            ['slug' => 'book', 'name' => 'book', 'has_electronic_file' => true],
            ['slug' => 'multimedia', 'name' => 'audio visual', 'has_electronic_file' => true],
            ['slug' => 'serials', 'name' => 'serials', 'has_electronic_file' => false],
        ])->each(function ($type) {
            ItemType::create($type);
        });
    }
}
