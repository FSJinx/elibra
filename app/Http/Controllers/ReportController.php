<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Models\Report;
use App\Services\QueryService;
use App\Services\ReportService;
use Illuminate\Http\Request;

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
        $report = $this->service->create($request->validated(), $request->user());

        return $this->response('success', 'Report generated successfully.', $report->load('file'), 201);
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
