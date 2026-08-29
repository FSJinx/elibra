<?php

namespace Database\Seeders;

use App\Services\SerialService;
use Illuminate\Database\Seeder;

class SerialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(SerialService $serialService): void
    {
        collect([
            [
                'title' => 'IEEE Transactions on Pattern Analysis and Machine Intelligence',
                'subtitle' => 'Special Issue on Foundation Vision Models',
                'description' => 'PAMI is a leading publication featuring research in computer vision, image processing, pattern recognition, and machine learning techniques for signal analysis.',
                'call_number' => '006.4 I22',
                'publication_year' => '2025',
                'item_type_id' => 4, // Serials
                'item_type_category_id' => 4, // Journal
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['Pattern Recognition', 'Machine Learning', 'Computer Vision', 'PAMI'],
                'isbn_issn' => '0162-8828',
                'volume' => '47',
                'issue' => '03',
                'pages' => '142-189',
                'doi' => 'https://doi.org/10.1109/TPAMI.2025.1092811',
            ],
            [
                'title' => 'Journal of Systems and Software Architecture',
                'subtitle' => 'Volume 198: Cloud Native and Microservice Resilience',
                'description' => 'A monthly peer-reviewed scientific journal covering software engineering methodologies, distributed system scalability, and formal verification frameworks.',
                'call_number' => '005.1 J82',
                'publication_year' => '2024',
                'item_type_id' => 4,
                'item_type_category_id' => 4,
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['Software Engineering', 'Microservices', 'Distributed Systems', 'Cloud Architecture'],
                'isbn_issn' => '0164-1212',
                'volume' => '198',
                'issue' => '01',
                'pages' => '12-45',
                'doi' => 'https://doi.org/10.1016/j.jss.2024.111802',
            ],
            [
                'title' => 'Communications of the ACM',
                'subtitle' => 'Computing Trends and Quantum Encryption Horizons',
                'description' => 'The premier monthly print and online publication for the global computing community, presenting news, opinions, and computing breakthroughs.',
                'call_number' => '004 C73',
                'publication_year' => '2025',
                'item_type_id' => 4,
                'item_type_category_id' => 5, // Magazine
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['ACM', 'Quantum Computing', 'Cybersecurity', 'Computer Science Trends'],
                'isbn_issn' => '0001-0782',
                'volume' => '68',
                'issue' => '02',
                'pages' => '50-88',
                'doi' => 'https://doi.org/10.1145/3698001',
            ],
            [
                'title' => 'Journal of Renewable and Sustainable Energy Reviews',
                'subtitle' => 'Quarterly Review on Clean Grid Transformations',
                'description' => 'An international journal disseminating key synthesis papers regarding smart grid infrastructures, energy storage, and climate mitigation strategies.',
                'call_number' => '333.79 J82',
                'publication_year' => '2024',
                'item_type_id' => 4,
                'item_type_category_id' => 4,
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['Sustainable Energy', 'Smart Grid', 'Clean Energy', 'Wind Power'],
                'isbn_issn' => '1364-0321',
                'volume' => '189',
                'issue' => '04',
                'pages' => '201-230',
                'doi' => 'https://doi.org/10.1016/j.rser.2024.113901',
            ],
            [
                'title' => 'International Journal of Educational Technology in Higher Education',
                'subtitle' => 'AI Assistants in Distance Learning Ecosystems',
                'description' => 'Open-access academic journal focused on the integration of digital pedagogies, learning analytics, and generative tools in tertiary education.',
                'call_number' => '371.33 I61',
                'publication_year' => '2025',
                'item_type_id' => 4,
                'item_type_category_id' => 4,
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['EdTech', 'Higher Education', 'Generative AI', 'Learning Analytics'],
                'isbn_issn' => '2365-9440',
                'volume' => '22',
                'issue' => '01',
                'pages' => '1-24',
                'doi' => 'https://doi.org/10.1186/s41239-025-00411-x',
            ],
        ])->each(fn ($item) => $serialService->create($item));
    }
}
