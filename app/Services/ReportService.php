<?php

namespace App\Services;

use App\Enums\AttachmentType;
use App\Enums\ReportType;
use App\Exports\ArrayExport;
use App\Models\Report;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class ReportService
{
    protected const ATTACHMENT_TYPE = AttachmentType::Report;

    public function __construct(
        protected AttachmentService $attachmentService,
    ) {}

    public function create(ReportType $type, ?array $filters, ?array $data, User $user): Report
    {
        return DB::transaction(function () use ($type, $filters, $data, $user) {
            $prefix = 'REP'.now()->format('Ymd');
            $next = Report::withTrashed()->where('reference', 'like', "$prefix%")->count() + 1;

            // Data to be created
            $reportReference = $prefix.str_pad($next, 4, '0', STR_PAD_LEFT);
            $reportType = $type->value;
            $reportFormat = $filters['format'];
            $reportFilters = $filters;
            $reportData = $this->sanitize($filters['columns'], $data);

            $path = self::ATTACHMENT_TYPE->folder().'/'.$type->value.'/'.$reportReference.'.'.$reportFormat;

            $attachment = $this->toFile($reportReference, $filters['columns'], $reportFormat, $type, $data);
            $file = $this->attachmentService->store(AttachmentType::Report, $attachment);

            $report = [
                'reference' => $reportReference,
                'type' => $reportType,
                'format' => $reportFormat,
                'filters' => $reportFilters,
                'data' => $reportData,
                'library_id' => $user->librarian->library_id,
                'file_id' => $file->id,
                'generator_id' => $user->id,
            ];

            return Report::create($report);
        });
    }

    protected function sanitize(array $columns, array $data): array
    {
        return array_map(fn ($row) => Arr::only($row, $columns), $data);
    }

    protected function toFile(
        string $reference,
        array $columns,
        string $format,
        ReportType $type,
        array $data
    ): UploadedFile {
        $information = [
            'title' => $type->title(),
            'reference' => $reference,

            'headers' => $columns,

            'rows' => $data,
        ];

        $contents = match ($format) {
            'pdf' => Pdf::loadView('reports', $information)->setPaper('folio', 'landscape')->output(),
            'xlsx' => Excel::raw(new ArrayExport($data), ExcelType::XLSX),
            'csv' => Excel::raw(new ArrayExport($data), ExcelType::CSV),
        };

        file_put_contents($tmp = tempnam(sys_get_temp_dir(), 'report_'), $contents);

        return new UploadedFile($tmp, Str::upper($type->value)."$reference.$format", null, null, true);
    }
}
