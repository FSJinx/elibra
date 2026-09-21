<?php

namespace App\Models;

use Database\Factories\AttendanceLogsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLogs extends Model
{
    /** @use HasFactory<AttendanceLogsFactory> */
    use HasFactory;

    protected $fillable = ['status', 'patron_id', 'branch_id', 'section_id'];

    public function patron()
    {
        return $this->belongsTo(Patron::class, 'patron_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function section()
    {
        return $this->belongsTo(Sections::class, 'section_id');
    }
}
