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
        $item_types = [
            ['name' => 'academic'],
            ['name' => 'book'],
            ['name' => 'multimedia'],
            ['name' => 'serial'],
        ];

        foreach ($item_types as $item_type) {
            ItemType::create($item_type);
        }
    }
}
