<?php

namespace App\Services;

use App\Models\AcaRepo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AcaRepoService
{
    private const FIELDS = [
        'title', 'category', 'description', 'doi', 'keywords',
        'status', 'statusRemark', 'on_catalog', 'language_id', 'program_id',
    ];

    public function create(array $data): AcaRepo
    {
        return DB::transaction(fn () => AcaRepo::create(Arr::only($data, self::FIELDS)));
    }

    public function update(AcaRepo $acaRepo, array $data): AcaRepo
    {
        DB::transaction(fn () => $acaRepo->update(Arr::only($data, self::FIELDS)));

        return $acaRepo->fresh(['language', 'program']);
    }

    public function delete(AcaRepo $acaRepo): bool
    {
        return DB::transaction(fn () => (bool) $acaRepo->delete());
    }

    public function submitAcaRepo(array $data): AcaRepo
    {
        return $this->create(array_merge($data, [
            'status' => 'pending',
            'on_catalog' => false,
        ]));
    }
}
