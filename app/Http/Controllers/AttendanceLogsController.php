<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLogs;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceLogsRequest;
use App\Http\Requests\UpdateAttendanceLogsRequest;

class AttendanceLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceLogs $attendanceLogs)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceLogs $attendanceLogs)
    {
        //
    }
}
