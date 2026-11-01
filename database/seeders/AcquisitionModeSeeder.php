<?php

namespace Database\Seeders;

use App\Services\AcquisitionModeService;
use Illuminate\Database\Seeder;

class AcquisitionModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(AcquisitionModeService $a): void
    {
        collect([
            ['name' => 'Gift', 'description' => 'Item received free from an individual or organization, with no exchange of money.', 'has_price' => false],
            ['name' => 'Donation', 'description' => 'Item contributed to the library by a donor, usually for collection development.', 'has_price' => false],
            ['name' => 'Purchase', 'description' => 'Item bought from a vendor, bookstore, or publisher.', 'has_price' => true],
            ['name' => 'Exchange', 'description' => 'Items are acquired through a reciprocal arrangement.', 'has_price' => false],
        ])->each(fn ($mode) => $a->create($mode));
    }
}
