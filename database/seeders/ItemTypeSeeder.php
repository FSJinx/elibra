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
            ['slug' => 'thesis', 'name' => 'Thesis',],
            ['slug' => 'book', 'name' => 'book'],
            ['slug' => 'multimedia', 'name' => 'AV Material'],
            ['slug' => 'serials', 'name' => 'serials'],
        ])->each(function ($type) {
            ItemType::create($type);
        });
    }
}
