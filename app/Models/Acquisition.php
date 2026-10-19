<?php

namespace App\Models;

use Database\Factories\AcquisitionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acquisition extends Model
{
    /** @use HasFactory<AcquisitionFactory> */
    use HasFactory;

    protected $fillable = [
        'reference',
        'dealer',
        'acquisition_mode',
        'acquisition_date',
        'remarks',

        'receiver_id',
        'acquisition_request_id',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
    ];

    public function receiver()
    {
        // return $this->belongsTo(Librarian::class, 'receiver_id');
        return $this->hasOneThrough(User::class, Librarian::class, 'id', 'id', 'receiver_id', 'user_id');
    }

    public function acquisitionRequest()
    {
        return $this->belongsTo(AcquisitionRequest::class, 'acquisition_request_id');
    }

    public function acquisition_lines()
    {
        return $this->hasOne(AcquisitionLines::class);
    }
}
