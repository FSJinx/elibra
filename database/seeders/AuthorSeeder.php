<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            [
                'first_name' => 'Abraham',
                'middle_name' => null,
                'last_name' => 'Silberschatz',
                'suffix' => 'Jr.',
            ],
            [
                'first_name' => 'Henry',
                'middle_name' => 'F.',
                'last_name' => 'Korth',
                'suffix' => 'Sr.',
            ],
            [
                'first_name' => 'S.',
                'middle_name' => null,
                'last_name' => 'Sudarshan',
                'suffix' => null,
            ],
            [
                'first_name' => 'Ian',
                'middle_name' => null,
                'last_name' => 'Goodfellow',
                'suffix' => null,
            ],
            [
                'first_name' => 'Yoshua',
                'middle_name' => null,
                'last_name' => 'Bengio',
                'suffix' => null,
            ],
            [
                'first_name' => 'Aaron',
                'middle_name' => null,
                'last_name' => 'Courville',
                'suffix' => null,
            ],
            [
                'first_name' => 'Stuart',
                'middle_name' => null,
                'last_name' => 'Russell',
                'suffix' => null,
            ],
            [
                'first_name' => 'Peter',
                'middle_name' => null,
                'last_name' => 'Norvig',
                'suffix' => null,
            ],
            [
                'first_name' => 'Thomas',
                'middle_name' => 'H.',
                'last_name' => 'Davenport',
                'suffix' => null,
            ],
            [
                'first_name' => 'Rajkumar',
                'middle_name' => null,
                'last_name' => 'Buyya',
                'suffix' => null,
            ],
            [
                'first_name' => 'Andrew',
                'middle_name' => 'S.',
                'last_name' => 'Tanenbaum',
                'suffix' => null,
            ],
            [
                'first_name' => 'Herbert',
                'middle_name' => null,
                'last_name' => 'Bos',
                'suffix' => null,
            ],
            [
                'first_name' => 'William',
                'middle_name' => null,
                'last_name' => 'Stallings',
                'suffix' => null,
            ],
            [
                'first_name' => 'John',
                'middle_name' => 'W.',
                'last_name' => 'Creswell',
                'suffix' => null,
            ],
            [
                'first_name' => 'Creswell',
                'middle_name' => 'J.',
                'last_name' => 'David',
                'suffix' => null,
            ],
            [
                'first_name' => 'Robert',
                'middle_name' => 'C.',
                'last_name' => 'Martin',
                'suffix' => null,
            ],
            [
                'first_name' => 'Martin',
                'middle_name' => null,
                'last_name' => 'Fowler',
                'suffix' => null,
            ],
            [
                'first_name' => 'Thomas',
                'middle_name' => 'H.',
                'last_name' => 'Cormen',
                'suffix' => null,
            ],
            [
                'first_name' => 'Charles',
                'middle_name' => 'E.',
                'last_name' => 'Leiserson',
                'suffix' => null,
            ],
            [
                'first_name' => 'Ronald',
                'middle_name' => 'L.',
                'last_name' => 'Rivest',
                'suffix' => 'Sr.',
            ],
        ];

        foreach ($authors as $author) {
            Author::firstOrCreate(
                [
                    'first_name' => $author['first_name'],
                    'middle_name' => $author['middle_name'],
                    'last_name' => $author['last_name'],
                    'suffix' => $author['suffix'],
                ]
            );
        }
    }
}