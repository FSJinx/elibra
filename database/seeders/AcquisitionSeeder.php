<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Librarian;
use App\Models\Library;
use App\Services\AcquisitionService;
use Illuminate\Database\Seeder;

class AcquisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(AcquisitionService $acquisition): void
    {
        $acquisition_modes = ['purchase', 'donation', 'gift', 'exchange'];

        $campus = Campus::where('code', 'ISU-E')->pluck('id')->first();
        $library = Library::where('campus_id', $campus)->pluck('id')->first();
        $receivers = Librarian::where('library_id', $library)->pluck('id');

        collect([
            ['dealer' => 'National Book Store'],
            ['dealer' => 'Fully Booked'],
            ['dealer' => 'Rex Bookstore'],
            ['dealer' => 'Phoenix Publishing House'],
            ['dealer' => 'Barnes & Noble'],
            ['dealer' => 'Book Depository'],
            ['dealer' => 'Ingram Content Group'],
            ['dealer' => 'Baker & Taylor'],
            ['dealer' => 'Follett'],
            ['dealer' => 'Powell\'s Books'],
            ['dealer' => 'Blackwell\'s'],
            ['dealer' => 'Kinokuniya'],
            ['dealer' => 'Scholastic'],
            ['dealer' => 'Gale Cengage'],
            ['dealer' => 'EBSCO'],
        ])->each(function ($data) use ($acquisition, $acquisition_modes, $receivers) {
            $acquisition->create([
                ...$data,
                'acquisition_date' => fake()->dateTimeBetween('2025-01-01')->format('Y-m-d'),
                'acquisition_mode' => fake()->randomElement($acquisition_modes),
                'receiver_id' => $receivers->random(),

            ]);
        });
    }
}
