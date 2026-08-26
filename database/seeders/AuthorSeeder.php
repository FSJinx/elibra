<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            'Abraham Silberschatz',
            'Henry F. Korth',
            'S. Sudarshan',
            'Ian Goodfellow',
            'Yoshua Bengio',
            'Aaron Courville',
            'Stuart Russell',
            'Peter Norvig',
            'Thomas H. Davenport',
            'Rajkumar Buyya',
            'Andrew S. Tanenbaum',
            'Herbert Bos',
            'William Stallings',
            'John W. Creswell',
            'Creswell J. David',
            'Robert C. Martin',
            'Martin Fowler',
            'Thomas H. Cormen',
            'Charles E. Leiserson',
            'Ronald L. Rivest',
        ];

        foreach ($authors as $name) {
            Author::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}