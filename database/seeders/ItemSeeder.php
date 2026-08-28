<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                // Academic Material Seed
                'title' => 'University Student Information System',
                'subtitle' => 'Manage Students, Faculty, and Academic Records',
                'description' => 'A comprehensive web-based Student Information System designed to streamline enrollment, course management, grading, and student records.',
                'call_number' => '',
                'language' => '',
                'publication_year' => '',

                'item_type_id' => '1', // Academic
                'item_type_category_id' => '9', // Thesis

                'keywords' => ['student information system', 'university', 'enrollment', 'grading'],
                'branch_id' => 1,
            ],

            // [
            // ],
            // [
            //     'title' => 'Library Management System',
            //     'subtitle' => 'Digital Library Services',
            //     'description' => 'Manage books, borrowing, returns, and library members efficiently through a centralized web application.',
            //     'keywords' => 'library, books, borrowing, catalog, management system',
            //     'branch_id' => 1,
            // ],
            // [
            //     'title' => 'Online Learning Platform',
            //     'subtitle' => 'E-learning and Course Management',
            //     'description' => 'A platform for delivering online courses, tracking student progress, and facilitating virtual classrooms.',
            //     'keywords' => 'e-learning, online courses, virtual classroom, education technology',
            //     'branch_id' => 2,
            // ],
            // [
            //     'title' => 'Research Publication Repository',
            //     'subtitle' => 'Archive and Access Academic Research',
            //     'description' => 'A digital repository for storing and accessing research papers, theses, and academic publications.',
            //     'keywords' => 'research repository, academic papers, theses, publications',
            //     'branch_id' => 2,
            // ],
            // [
            //     'title' => 'Campus Event Management System',
            //     'subtitle' => 'Organize and Promote Campus Events',
            //     'description' => 'A system to manage campus events, registrations, and promotions effectively.',
            //     'keywords' => 'event management, campus events, registration system',
            //     'branch_id' => 3,
            // ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
