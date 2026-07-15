<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campuses = [
            ['name' => 'Main Campus',    'code' => 'ISU-E',    'address' => 'San Fabian, Echague, Isabela'],
            // ['name' => 'Cabagan Campus',    'code' => 'ISU-C',    'address' => 'Garita, Cabagan, Isabela'],
            // ['name' => 'Cauayan Campus',    'code' => 'ISU-CC',    'address' => 'Dacanay St., Cauayan City, Isabela'],
            // ['name' => 'Ilagan Campus',    'code' => 'ISU-I',    'address' => 'Calamagui 2nd, Ilagan City, Isabela'],
            // ['name' => 'Angadanan Campus',    'code' => 'ISU-AC',    'address' => 'Angadanan, Isabela'],
            // ['name' => 'Roxas Campus',    'code' => 'ISU-R',    'address' => 'Rang-ayan, Roxas, Isabela'],
            // ['name' => 'Jones Campus',    'code' => 'ISU-J',    'address' => 'Barangay 1, Jones, Isabela'],
            // ['name' => 'San Mateo Campus',    'code' => 'ISU-SM',    'address' => 'National Highway, San Andres, San Mateo, Isabela'],
            // ['name' => 'San Mariano Campus',    'code' => null,    'address' => 'San Mariano, Isabela'],
            // ['name' => 'Santiago Campus',    'code' => null,    'address' => 'Santiago, Isabela'],
            // ['name' => 'Palanan Extension',    'code' => null,    'address' => 'Palanan, Isabela'],
        ];
        foreach ($campuses as $campus) {
            Campus::create($campus);
        }
    }
}
