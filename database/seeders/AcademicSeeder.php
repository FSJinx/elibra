<?php

namespace Database\Seeders;

use App\Models\Academic;
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
                'title' => 'Multi Layered Convolutional Neural Network in Classification of Different Tomato Diseases',
                'subtitle' => 'A Transfer Learning Approach via ShuffleNet V2 and Inception V3',
                'description' => 'Tomato leaf diseases are a major danger to worldwide crop yields, necessitating early and accurate detection for successful management. In this study, we assess and compare two transfer-learned convolutional neural network backbones for categorizing seven typical tomato leaf states.',
                'call_number' => '201a.343',
                'publication_year' => '2025',
                'item_type_id' => 1, // Academic
                'item_type_category_id' => 3, // Dissertation
                'language_id' => 1, // English
                'branch_id' => 2,
                'keywords' => ['Tomato', 'CNN', 'InceptionV3', 'ShuffleNet', 'Deep Learning'],
                'doi' => 'https://doi.org/10.1109/ICSET65917.2025.11283838',
            ],
            [
                'title' => 'Development of an IoT-Based Smart Flood Monitoring and Early Warning System for River Basins',
                'subtitle' => 'Real-time Water Level and Rainfall Telemetry Using LoRaWAN Networks',
                'description' => 'Frequent flash floods in low-lying river basin communities present severe risks to life and infrastructure. This study introduces an automated, solar-powered Internet of Things monitoring node deployed along critical river tributaries.',
                'call_number' => '621.381 0285',
                'publication_year' => '2024',
                'item_type_id' => 1,
                'item_type_category_id' => 9, // Thesis
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['IoT', 'LoRaWAN', 'Flood Warning', 'Telemetry', 'Embedded Systems'],
                'doi' => 'https://doi.org/10.1016/j.iot.2024.100912',
            ],
            [
                'title' => 'Blockchain-Based Decentralized Identity Management Protocol for Academic Credential Verification',
                'subtitle' => 'Eliminating Diploma Fraud through Self-Sovereign Identity Architecture',
                'description' => 'Academic degree fraud poses significant challenges to employers and higher education institutions globally. This study presents an Ethereum-compatible smart contract framework designed to issue, verify, and revoke university credentials.',
                'call_number' => '005.74 C86',
                'publication_year' => '2024',
                'item_type_id' => 1,
                'item_type_category_id' => 7, // Research Paper
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['Blockchain', 'Smart Contracts', 'Credential Verification', 'Decentralized Identity'],
                'doi' => 'https://doi.org/10.1109/TSE.2024.3382910',
            ],
            [
                'title' => 'Optimization of Solar Photovoltaic Cell Efficiency Using Graphene-Based Nanocomposite Coatings',
                'subtitle' => 'Experimental Evaluation Under High Humidity Tropical Climates',
                'description' => 'Photovoltaic module efficiency drops significantly under high temperature and ambient relative humidity. This paper investigates the thermal dispersion and light transmittance performance of graphene oxide thin films applied over polycrystalline silicon cells.',
                'call_number' => '621.312 44',
                'publication_year' => '2023',
                'item_type_id' => 1,
                'item_type_category_id' => 1,
                'language_id' => 1,
                'branch_id' => 1,
                'keywords' => ['Solar Energy', 'Photovoltaics', 'Graphene', 'Nanotechnology', 'Renewable Energy'],
                'doi' => 'https://doi.org/10.1016/j.solmat.2023.112450',
            ],
            [
                'title' => 'An Assessment of Philippine Micro-Enterprise Resilience Post-Pandemic',
                'subtitle' => 'Evaluating Financial Literacy and Digital Payment Adoption in Rural Communities',
                'description' => 'Micro-enterprises constitute over ninety percent of commercial establishments in developing economic zones. This empirical dissertation analyzes the post-pandemic business continuity strategies employed by sari-sari store owners and local vendors.',
                'call_number' => '338.642 P53',
                'publication_year' => '2025',
                'item_type_id' => 1,
                'item_type_category_id' => 4,
                'language_id' => 1,
                'branch_id' => 2,
                'keywords' => ['Microenterprises', 'Financial Literacy', 'Digital Payments', 'Business Resilience'],
                'doi' => 'https://doi.org/10.1080/09585206.2025.2104928',
            ],
        ])->each(fn ($item) => $academicService->create($item));
    }
}
