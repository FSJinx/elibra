<?php

namespace App\Services;

use App\Models\AcaRepo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AcaRepoService
{
    private const FIELDS = [
        'title',
        'category',
        'description',
        'doi',
        'electronic_file',
        'keywords',

        'status',
        'statusRemark',
        'on_catalog',

        'library_id',
        'submitted_by',
        'language_id',
        'program_id',
    ];

    public function create(array $data): AcaRepo
    {
        $file = $data['electronic_file'] ?? null;
        if ($file instanceof UploadedFile) {
            $data['electronic_file'] = $file->store('aca-repo/files', 'public');
        }

        try {
            return DB::transaction(function () use ($data) {
                return AcaRepo::create(Arr::only($data, self::FIELDS));
            });
        } catch (Throwable $e) {
            if (is_string($data['electronic_file'] ?? null)) {
                Storage::disk('public')->delete($data['electronic_file']);
            }
            throw $e;
        }
    }

    public function update(AcaRepo $acaRepo, array $data): AcaRepo
    {
        $oldFile = $acaRepo->electronic_file;
        $file = $data['electronic_file'] ?? null;
        if ($file instanceof UploadedFile) {
            $data['electronic_file'] = $file->store('aca-repo/files', 'public');
        }

        try {
            DB::transaction(function () use ($acaRepo, $data) {
                $acaRepo->update(Arr::only($data, self::FIELDS));
            });
        } catch (Throwable $e) {
            if (is_string($data['electronic_file'] ?? null) && $data['electronic_file'] !== $oldFile) {
                Storage::disk('public')->delete($data['electronic_file']);
            }
            throw $e;
        }

        if (is_string($data['electronic_file'] ?? null) && $data['electronic_file'] !== $oldFile && $oldFile) {
            Storage::disk('public')->delete($oldFile);
        }

        return $acaRepo->fresh(['language', 'program', 'submitter', 'library']);
    }

    public function delete(AcaRepo $acaRepo): bool
    {
        return DB::transaction(function () use ($acaRepo) {
            return (bool) $acaRepo->delete();
        });
    }

    public function submitAcaRepo(array $data): AcaRepo
    {
        return $this->create(array_merge($data, [
            'status' => 'pending',
            'on_catalog' => false,
        ]));
    }
}
