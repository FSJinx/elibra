<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holding extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserved_by',
        'accession_id',
        'processed_by',

        'pickup_date',
        'pickup_date_at'
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'picked_up_at' => 'datetime'
    ];
}
