<?php

namespace Database\Seeders;

use App\Services\BookService;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(BookService $bookService): void
    {
        collect([
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'subtitle' => 'How to Structure Readable and Maintainable Codebases',
                'description' => 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees. Every year, countless hours and significant resources are lost because of poorly written code.',
                'call_number' => '005.1 M114c',
                'publication_year' => '2008',
                'item_type_id' => 2, // Book
                'item_type_category_id' => 13, // Reference Book
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['Clean Code', 'Agile', 'Software Architecture', 'Refactoring'],
                'edition' => '1st Edition',
                'isbn_issn' => '978-0132350884',
                'copyright_year' => '2026'
            ],
            [
                'title' => 'Designing Data-Intensive Applications',
                'subtitle' => 'The Big Ideas Behind Reliable, Scalable, and Maintainable Systems',
                'description' => 'Data is at the center of many economic and technological challenges today. This book guides developers through the pros and cons of various technologies for processing and storing data.',
                'call_number' => '005.74 K64d',
                'publication_year' => '2017',
                'item_type_id' => 2,
                'item_type_category_id' => 13,
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['Data Systems', 'Distributed Systems', 'NoSQL', 'Scalability', 'Kafka'],
                'edition' => '1st Edition',
                'isbn_issn' => '978-1449373320',
                'copyright_year' => '2026'
            ],
            [
                'title' => 'Artificial Intelligence: A Modern Approach',
                'subtitle' => 'The Standard Textbook in AI',
                'description' => 'The long-anticipated revision offers the most comprehensive and up-to-date introduction to the theory and practice of artificial intelligence.',
                'call_number' => '006.3 R961a',
                'publication_year' => '2020',
                'item_type_id' => 2,
                'item_type_category_id' => 13, // Textbook
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['Artificial Intelligence', 'Machine Learning', 'Search Algorithms', 'Robotics'],
                'edition' => '4th Edition',
                'isbn_issn' => '978-0134610993',
                'copyright_year' => '2026'
            ],
            [
                'title' => 'Domain-Driven Design: Tackling Complexity in the Heart of Software',
                'subtitle' => 'Aligning Software Code with Business Models',
                'description' => 'Domain-Driven Design provides a systematic approach to technical architectures based on complex domain models and ubiquitous language principles.',
                'call_number' => '005.11 E92d',
                'publication_year' => '2003',
                'item_type_id' => 2,
                'item_type_category_id' => 13,
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['DDD', 'Software Design', 'OOP', 'System Architecture'],
                'edition' => '1st Edition',
                'isbn_issn' => '978-0321125217',
                'copyright_year' => '2026'
            ],
            [
                'title' => 'Modern Operating Systems',
                'subtitle' => 'Concepts, Processes, and Hardware Interfacing',
                'description' => 'Covers modern developments in operating systems design, including memory management, virtualization, containers, multi-core processing, and security mechanisms.',
                'call_number' => '005.43 T161m',
                'publication_year' => '2022',
                'item_type_id' => 2,
                'item_type_category_id' => 13,
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['Operating Systems', 'Linux', 'Kernel', 'Memory Management', 'Virtualization'],
                'edition' => '5th Edition',
                'isbn_issn' => '978-0133591620',
                'copyright_year' => '2026'
            ],
        ])->each(fn ($item) => $bookService->create($item));
    }
}
