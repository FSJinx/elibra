<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Services\QueryService;
use App\Services\ReportQueryService;
use App\Services\ReportService;
use App\Enums\ReportType;
use App\Models\Acquisition;
use App\Models\AttendanceLogs;
use App\Models\Holding;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $service,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $data = Report::query()
            ->with(['file', 'generator'])
            ->when($request->user()?->librarian?->library_id, fn ($query, $libraryId) => $query->where('library_id', $libraryId))
            ->when($request->input('type'), fn ($query, $type) => $query->where('type', $type))
            ->when($request->input('query'), fn ($query, $value) => $query->where('reference', 'like', "%{$value}%"))
            ->with(['file', 'generator'])
            ->orderBy($filters['sort'], $filters['order'])
            ->paginate($filters['per_page'], ['*'], 'page', $filters['page']);

        return $this->response(data: $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        $type = $request->input('type');
        if (! $type) {
            throw ValidationException::withMessages(['type' => ['Report type is required.']]);
        }
        return $this->generate($request, $type);
    }

    public function generate(StoreReportRequest $request, string $type)
    {
        $reportType = ReportType::tryFrom($type);
        if (! $reportType) {
            throw ValidationException::withMessages(['type' => ['Unsupported report type.']]);
        }

        $user = $request->user();
        $libraryId = $user->librarian?->library_id;
        if (! $libraryId) {
            return $this->response('error', 'Only a library staff account can generate library reports.', null, 403);
        }

        $filters = ReportQueryService::filters($reportType, $request);
        $data = match ($reportType) {
            ReportType::Attendance => $this->attendanceData($libraryId, $filters),
            ReportType::Reservation => $this->reservationData($libraryId, $filters),
            ReportType::Item => $this->itemData($libraryId, $filters),
            ReportType::Acquisition => $this->acquisitionData($libraryId, $filters),
            default => null,
        };

        if ($data === null) {
            throw ValidationException::withMessages(['type' => ['This report type uses an existing dedicated generator.']]);
        }
        if (empty($data)) {
            return $this->response('error', 'No data is available for this report.');
        }

        $report = $this->service->create($reportType, $filters, $data, $user);
        return $this->response('success', 'Report generated successfully.', $report->load('file'), 201);
    }

    private function attendanceData(int $libraryId, array $filters): array
    {
        return AttendanceLogs::query()
            ->where('library_id', $libraryId)
            ->whereHas('patron.user')
            ->when($filters['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['created_to'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->with(['patron.user', 'patron.patronType', 'section', 'library'])
            ->latest('created_at')
            ->get()
            ->map(fn (AttendanceLogs $log) => [
                'id' => $log->id,
                'patron' => $log->patron?->user?->name->full ?? 'Unknown patron',
                'patron_type' => $log->patron?->patronType?->name,
                'username' => $log->patron?->user?->username,
                'status' => $log->status,
                'section' => $log->section?->name ?? 'General Entrance',
                'library' => $log->library?->name,
                'created_at' => $log->created_at?->toDateTimeString(),
            ])
            ->all();
    }

    private function reservationData(int $libraryId, array $filters): array
    {
        return Holding::query()
            ->whereHas('accession.item', fn ($q) => $q->where('library_id', $libraryId))
            ->when($filters['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['created_to'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->with(['reserver.user', 'accession.item'])
            ->latest('created_at')
            ->get()
            ->map(fn (Holding $holding) => [
                'id' => $holding->id,
                'accession_number' => $holding->accession?->accession_number,
                'title' => $holding->accession?->item?->title,
                'patron' => $holding->reserver?->user?->name->full ?? 'Unknown patron',
                'status' => $holding->status,
                'expected_pickup_date' => $holding->expected_pickup_date?->toDateTimeString(),
                'pickup_date' => $holding->pickup_date?->toDateTimeString(),
                'created_at' => $holding->created_at?->toDateTimeString(),
            ])
            ->all();
    }

    private function itemData(int $libraryId, array $filters): array
    {
        return Item::query()
            ->where('library_id', $libraryId)
            ->when($filters['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($filters['created_to'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->with(['itemType', 'itemTypeCategory', 'library'])
            ->orderBy('title')
            ->get()
            ->map(fn (Item $item) => [
                'id' => $item->id,
                'title' => $item->title,
                'call_number' => $item->call_number,
                'item_type' => $item->itemType?->name,
                'category' => $item->itemTypeCategory?->name,
                'isbn_issn' => $item->isbn_issn,
                'publication_year' => $item->publication_year,
                'library' => $item->library?->name,
                'created_at' => $item->created_at?->toDateString(),
            ])
            ->all();
    }


    private function acquisitionData(int $libraryId, array $filters): array
    {
        return Acquisition::query()
            ->whereHas('acquisition_lines.item', fn ($q) => $q->where('library_id', $libraryId))
            ->when($filters['created_from'], fn ($q, $date) => $q->whereDate('acquisition_date', '>=', $date))
            ->when($filters['created_to'], fn ($q, $date) => $q->whereDate('acquisition_date', '<=', $date))
            ->with(['acquisition_mode', 'receiver'])
            ->latest('acquisition_date')
            ->get()
            ->map(fn (Acquisition $acquisition) => [
                'id' => $acquisition->id,
                'reference' => $acquisition->reference,
                'dealer' => $acquisition->dealer,
                'acquisition_date' => $acquisition->acquisition_date?->toDateString(),
                'acquisition_mode' => $acquisition->acquisition_mode?->name,
                'receiver' => $acquisition->receiver?->name->full,
                'remarks' => $acquisition->remarks,
                'created_at' => $acquisition->created_at?->toDateTimeString(),
            ])
            ->all();
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
