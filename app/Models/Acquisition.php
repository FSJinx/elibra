<?php

namespace App\Models;

use Database\Factories\AcquisitionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Acquisition extends Model
{
    /** @use HasFactory<AcquisitionFactory> */
    use HasFactory;

    protected $fillable = [
        'reference',
        'dealer',
        // 'acquisition_mode',
        'acquisition_date',
        'remarks',

        'acquisition_mode_id',
        'receiver_id',
        'acquisition_request_id',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
    ];

    public function receiver()
    {
        return $this->hasOneThrough(User::class, Librarian::class, 'id', 'id', 'receiver_id', 'user_id')->select('users.id', 'users.last_name', 'users.first_name');
    }

    public function acquisition_mode(): BelongsTo
    {
        return $this->belongsTo(AcquisitionMode::class);
    }

    public function acquisition_request()
    {
        return $this->belongsTo(AcquisitionRequest::class, 'acquisition_request_id');
    }

    public function acquisition_lines()
    {
        return $this->hasOne(AcquisitionLines::class);
    }
}
