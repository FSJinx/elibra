<?php

namespace App\Services;

use App\Enums\Enums\AttachmentEnums;
use App\Models\Report;

class ReportService
{
    public function __construct(
        protected AttachmentService $attachment,
    ) {}

    public function create(?array $data): Report
    {
        $prefix = 'REP'.now()->format('Ymd');
        $next = Report::withTrashed()->where('reference', 'like', "$prefix%")->count() + 1;

        $attachment = $this->attachment->store(AttachmentEnums::CirculationReport, $data['data']);

        return Report::create([
            'reference' => $prefix.str_pad($next, 4, '0', STR_PAD_LEFT),
            'type' => $data['type'],
            'format' => $data['format'],
            'filters' => $data['filters'],
            'data' => $data['data'],
            'file_id' => $data['file_id'],
            'generator_id' => $data['generator_id'],
            'library_id_id' => $data['library_id_id'],
        ]);
    }
}
