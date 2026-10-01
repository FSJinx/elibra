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
        'acquisition_id',
        'dealer',
        'acquisition_mode',
        'acquisition_date',
        'remarks',

        'receiver_user_id',
        'acquisition_request_id',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
    ];

    public function receiver()
    {
        return $this->belongsTo(Librarian::class, 'receiver_user_id');
    }

    public function acquisitionRequest()
    {
        return $this->belongsTo(AcquisitionRequest::class, 'acquisition_request_id');
    }
}
