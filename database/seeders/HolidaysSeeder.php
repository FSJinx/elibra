<?php

namespace Database\Seeders;

use App\Models\Holidays;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
 public function run(): void
    {
        $holidays = [
            // Regular Holidays
            [
                'date' => '2026-01-01',
                'name' => "New Year's Day",
            ],
            [
                'date' => '2026-03-20',
                'name' => "Eid'l Fitr (Feast of Ramadhan)",
            ],
            [
                'date' => '2026-04-02',
                'name' => 'Maundy Thursday',
            ],
            [
                'date' => '2026-04-03',
                'name' => 'Good Friday',
            ],
            [
                'date' => '2026-04-09',
                'name' => 'Araw ng Kagitingan (Day of Valor)',
            ],
            [
                'date' => '2026-05-01',
                'name' => 'Labor Day',
            ],
            [
                'date' => '2026-05-27',
                'name' => "Eid'l Adha (Feast of Sacrifice)",
            ],
            [
                'date' => '2026-06-12',
                'name' => 'Independence Day',
            ],
            [
                'date' => '2026-08-31',
                'name' => 'National Heroes Day',
            ],
            [
                'date' => '2026-11-30',
                'name' => 'Bonifacio Day',
            ],
            [
                'date' => '2026-12-25',
                'name' => 'Christmas Day',
            ],
            [
                'date' => '2026-12-30',
                'name' => 'Rizal Day',
            ],

            // Special Non-Working Holidays
            [
                'date' => '2026-02-17',
                'name' => 'Chinese New Year',
            ],
            [
                'date' => '2026-04-04',
                'name' => 'Black Saturday',
            ],
            [
                'date' => '2026-08-21',
                'name' => 'Ninoy Aquino Day',
            ],
            [
                'date' => '2026-11-01',
                'name' => "All Saints' Day",
            ],
            [
                'date' => '2026-11-02',
                'name' => "All Souls' Day",
            ],
            [
                'date' => '2026-12-08',
                'name' => 'Feast of the Immaculate Conception of Mary',
            ],
            [
                'date' => '2026-12-24',
                'name' => 'Christmas Eve',
            ],
            [
                'date' => '2026-12-31',
                'name' => 'Last Day of the Year',
            ],
        ];

        foreach ($holidays as $holiday) {
            Holidays::updateOrCreate(
                [
                    'date' => $holiday['date'],
                ],
                [
                    'name' => $holiday['name'],
                ]
            );
        }
    }
}
