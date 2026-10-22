<?php

namespace App\Services;

use App\Models\AcaRepo;

class AcaRepoService
{
    public function submitAcaRepo(array $data): AcaRepo
    {
        return AcaRepo::create(array_merge($data, [
            'status' => 'pending',
            'on_catalog' => false,
        ]));
    }
}