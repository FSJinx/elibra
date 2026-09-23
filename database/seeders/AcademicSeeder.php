<?php

namespace Database\Seeders;

use App\Services\AcademicService;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(AcademicService $academicService): void
    {
        collect([
            [
                'title' => 'Development of a Machine Learning-Based Student Performance Prediction System',
                'description' => 'This study explores the application of machine learning techniques in predicting student academic performance using historical academic records and behavioral indicators.',
                'call_number' => '005.133 M23',
                'publication_year' => 2025,
                'item_type_category_id' => 1, // Undergraduate Thesis
                'branch_id' => 1, // University Library
                'keywords' => ['Machine Learning', 'Student Performance', 'Prediction', 'Education Technology'],
            ],
            [
                'title' => 'Cybersecurity Awareness and Safe Computing Practices Among University Students',
                'description' => 'This study examines cybersecurity awareness among university students and evaluates their practices concerning passwords, phishing, malware, and personal data protection.',
                'call_number' => '005.8 C93',
                'publication_year' => 2024,
                'item_type_category_id' => 2, // Graduate Thesis
                'branch_id' => 1, // University Library
                'keywords' => ['Cybersecurity', 'Security Awareness', 'Students', 'Information Security'],
            ],
            [
                'title' => 'IoT-Based Smart Classroom Monitoring and Environmental Control System',
                'description' => 'This study presents an Internet of Things system for monitoring classroom environmental conditions and automatically controlling selected classroom equipment.',
                'call_number' => '621.384 I56',
                'publication_year' => 2025,
                'item_type_category_id' => 1, // Undergraduate Thesis
                'branch_id' => 2, // CCSICT Research Room
                'keywords' => ['IoT', 'Smart Classroom', 'Environmental Monitoring', 'Automation'],
            ],
            [
                'title' => 'Deep Learning Approach for Automated Classification of Philippine Plant Species',
                'description' => 'This study investigates the use of deep learning techniques for automatically identifying and classifying selected Philippine plant species from digital images.',
                'call_number' => '006.31 D44',
                'publication_year' => 2024,
                'item_type_category_id' => 2, // Graduate Thesis
                'branch_id' => 2, // CCSICT Research Room
                'keywords' => ['Deep Learning', 'Plant Classification', 'Computer Vision', 'Philippine Flora'],
            ],
            [
                'title' => 'Digital Financial Literacy and Mobile Payment Adoption Among Rural Entrepreneurs',
                'description' => 'This study investigates the relationship between digital financial literacy and the adoption of mobile payment platforms among entrepreneurs operating in rural communities.',
                'call_number' => '332.024 D57',
                'publication_year' => 2025,
                'item_type_category_id' => 1, // Undergraduate Thesis
                'branch_id' => 3, // Public Library
                'keywords' => ['Financial Literacy', 'Mobile Payments', 'Entrepreneurship', 'Rural Communities'],
            ],
            [
                'title' => 'Community-Based Disaster Preparedness and Flood Risk Reduction Strategies',
                'description' => 'This study assesses disaster preparedness practices and flood risk reduction strategies implemented by communities located in flood-prone areas.',
                'call_number' => '363.349 D37',
                'publication_year' => 2023,
                'item_type_category_id' => 2, // Graduate Thesis
                'branch_id' => 3, // Public Library
                'keywords' => ['Disaster Preparedness', 'Flood Risk', 'Risk Reduction', 'Community Resilience'],
            ],
            [
                'title' => 'Digital Evidence Management System for Improving Criminal Case Documentation',
                'description' => 'This study proposes a digital evidence management system designed to improve the organization, retrieval, and tracking of evidence records used in criminal case documentation.',
                'call_number' => '345.05 D57',
                'publication_year' => 2025,
                'item_type_category_id' => 1, // Undergraduate Thesis
                'branch_id' => 4, // CCJE Library
                'keywords' => ['Digital Evidence', 'Criminal Justice', 'Case Documentation', 'Records Management'],
            ],
            [
                'title' => 'Assessment of Legal Information Access and Research Practices Among Criminal Justice Students',
                'description' => 'This study examines how criminal justice students access, evaluate, and utilize legal information resources for academic research and coursework.',
                'call_number' => '340.072 L44',
                'publication_year' => 2024,
                'item_type_category_id' => 2, // Graduate Thesis
                'branch_id' => 4, // CCJE Library
                'keywords' => ['Legal Research', 'Information Access', 'Criminal Justice', 'Academic Research'],
            ],
        ])->each(function ($item) use ($academicService) {
            $academicService->create([
                ...$item,
                'subtitle' => match ($item['branch_id']) {
                    1 => 'Academic resource for Echague - University Library',
                    2 => 'Academic resource for Echague - CCSICT Research Room',
                    3 => 'Academic resource for Angadanan - Public Library',
                    4 => 'Academic resource for Angadanan - CCJE Library',
                },
                'item_type_id' => 1,
                'language_id' => 1,
            ]);
        });
    }
}
