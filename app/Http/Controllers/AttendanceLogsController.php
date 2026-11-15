<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceLogsRequest;
use App\Http\Requests\UpdateAttendanceLogsRequest;
use App\Models\AttendanceLogs;
use App\Services\AttendanceLogsService;
use App\Services\QueryService;
use Illuminate\Http\Request;

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
