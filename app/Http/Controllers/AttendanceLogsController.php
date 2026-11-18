<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceLogsRequest;
use App\Http\Requests\UpdateAttendanceLogsRequest;
use App\Models\AttendanceLogs;
use App\Services\AttendanceLogsService;
use App\Services\QueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceLogsController extends Controller
{
    protected AttendanceLogsService $attendanceLogsService;

    public function __construct(AttendanceLogsService $attendanceLogsService)
    {
        $this->attendanceLogsService = $attendanceLogsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = QueryService::filters($request);

        $filters = array_merge($filters, [
            'patron_id' => $request->input('patron_id', null),
            'library_id' => $request->input('library_id', null),
            'section_id' => $request->input('section_id', null),
        ]);

        $data = AttendanceLogs::query()
            ->when($filters['patron_id'], fn ($q, $p) => $q->where('patron_id', $p))
            ->with(['patron', 'library', 'section'])
            ->paginate(
                $filters['per_page'],
                ['*'],
                'page',
                $filters['page']
            );

        return $this->response(data: $data);
    }

    public function analytics(Request $request)
    {
        $libraryId = $request->integer('library_id');
        $sectionId = $request->integer('section_id') ?: null;
        $date = now()->toDateString();

        $base = AttendanceLogs::query()
            ->where('library_id', $libraryId)
            ->when($sectionId, fn ($query) => $query->where('section_id', $sectionId))
            ->whereDate('created_at', $date);

        $scopeLabel = $sectionId
            ? \App\Models\Sections::query()->whereKey($sectionId)->where('library_id', $libraryId)->value('name') ?? 'Library Section'
            : 'General / Overall';

        $hourly = [];
        for ($hour = 7; $hour <= 21; $hour++) {
            $male = (clone $base)
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->whereHas('patron.user', fn ($query) => $query->where('sex', 'male'))
                ->count();

            $female = (clone $base)
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->whereHas('patron.user', fn ($query) => $query->where('sex', 'female'))
                ->count();

            $hourly[] = [
                'hour' => $hour,
                'label' => now()->setTime($hour, 0)->format('g A'),
                'male' => $male,
                'female' => $female,
                'total' => $male + $female,
            ];
        }

        $statusBreakdown = (clone $base)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(fn ($row) => ['status' => $row->status, 'total' => (int) $row->total])
            ->values();

        $sectionBreakdown = (clone $base)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(fn ($row) => ['name' => $row->status === 'in' ? 'Checked in' : 'Checked out', 'total' => (int) $row->total])
            ->values();

        $topPatrons = (clone $base)
            ->select('patron_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('patron_id')
            ->groupBy('patron_id')
            ->orderByDesc('total')
            ->with(['patron.user:id,last_name,first_name,username', 'patron.patronType:id,name'])
            ->limit(8)
            ->get()
            ->map(fn (AttendanceLogs $log) => [
                'name' => $log->patron?->user?->name->full ?? 'Unknown patron',
                'username' => $log->patron?->user?->username,
                'patron_type' => $log->patron?->patronType?->name,
                'total' => (int) $log->total,
            ])
            ->values();

        return $this->response(data: [
            'date' => $date,
            'scope_label' => $scopeLabel,
            'total' => (clone $base)->count(),
            'checked_in' => (clone $base)->where('status', 'in')->count(),
            'checked_out' => (clone $base)->where('status', 'out')->count(),
            'hourly' => $hourly,
            'statuses' => $statusBreakdown,
            'sections' => $sectionBreakdown,
            'top_patrons' => $topPatrons,
        ]);
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
    public function store(StoreAttendanceLogsRequest $request)
    {
        $attendanceLog = $this->attendanceLogsService->create($request->validated());

        return $this->response(
            'success',
            'Attendance log successfully created.',
            $attendanceLog->toArray(),
            201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceLogs $attendanceLogs)
    {
        return $attendanceLogs->load(['patron', 'branch', 'section']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceLogs $attendanceLogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceLogsRequest $request, AttendanceLogs $attendanceLogs)
    {
        $attendanceLog = $this->attendanceLogsService->update($attendanceLogs, $request->validated());

        return $this->response(
            'success',
            'Attendance log successfully updated.',
            $attendanceLog->toArray(),
            200,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceLogs $attendanceLogs)
    {
        $deleted = $this->attendanceLogsService->delete($attendanceLogs);

        if (! $deleted) {
            return $this->response(
                'error',
                'Attendance log could not be deleted.',
                null,
                500,
            );
        }

        return $this->response(
            'success',
            'Attendance log deleted successfully.',
            null,
            200,
        );
    }
}
