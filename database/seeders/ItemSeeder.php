<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => 'University Student Information System',
                'subtitle' => 'Manage Students, Faculty, and Academic Records',
                'description' => 'A comprehensive web-based Student Information System designed to streamline enrollment, course management, grading, and student records.',
                'keywords' => 'student information system, university, enrollment, grading',
            ],
            [
                'title' => 'Library Management System',
                'subtitle' => 'Digital Library Services',
                'description' => 'Manage books, borrowing, returns, and library members efficiently through a centralized web application.',
                'keywords' => 'library, books, borrowing, catalog, management system',
            ],
            [
                'title' => 'Online Learning Platform',
                'subtitle' => 'E-learning and Course Management',
                'description' => 'A platform for delivering online courses, tracking student progress, and facilitating virtual classrooms.',
                'keywords' => 'e-learning, online courses, virtual classroom, education technology',
            ],
            [
                'title' => 'Research Publication Repository',
                'subtitle' => 'Archive and Access Academic Research',
                'description' => 'A digital repository for storing and accessing research papers, theses, and academic publications.',
                'keywords' => 'research repository, academic papers, theses, publications',
            ],
            [
                'title' => 'Campus Event Management System',
                'subtitle' => 'Organize and Promote Campus Events',
                'description' => 'A system to manage campus events, registrations, and promotions effectively.',
                'keywords' => 'event management, campus events, registration system',
            ],
        ];

        foreach ($items as $item){
            Item::create($item);
        }
    }
}
