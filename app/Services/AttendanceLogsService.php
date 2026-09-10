<?php

namespace App\Services;

use App\Models\AttendanceLogs;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceLogsService
{
    public function create(array $data): AttendanceLogs
    {
        return DB::transaction(function () use ($data) {
            if (! empty($data['username'])) {
                $user = User::query()->where('username', $data['username'])->first();

                if (! $user || ! $user->patron) {
                    throw ValidationException::withMessages([
                        'username' => ['This username is not registered as a patron.'],
                    ]);
                }

                $data['patron_id'] = $user->patron->id;
                unset($data['username']);
            }

            $attendanceLog = AttendanceLogs::create($data);

            CacheService::invalidate(CacheService::ATTENDANCE_LOGS);

            return $attendanceLog->fresh();
        });
    }

    public function update(AttendanceLogs $attendanceLogs, array $data): AttendanceLogs
    {
        return DB::transaction(function () use ($attendanceLogs, $data) {
            if (! empty($data['username'])) {
                $user = User::query()->where('username', $data['username'])->first();

                if (! $user || ! $user->patron) {
                    throw ValidationException::withMessages([
                        'username' => ['This username is not registered as a patron.'],
                    ]);
                }

                $data['patron_id'] = $user->patron->id;
                unset($data['username']);
            }

            $attendanceLogs->update($data);

            CacheService::invalidate(CacheService::ATTENDANCE_LOGS);

            return $attendanceLogs->fresh();
        });
    }

    public function delete(AttendanceLogs $attendanceLogs): bool
    {
        return DB::transaction(function () use ($attendanceLogs) {
            $deleted = $attendanceLogs->delete();

            CacheService::invalidate(CacheService::ATTENDANCE_LOGS);

            return $deleted;
        });
    }
}

