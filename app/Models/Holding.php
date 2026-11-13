<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Holding extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserver_id',
        'accession_id',
        'processed_by',
        'expected_pickup_date',
        'pickup_date',

        'status',
        'status_remark'
    ];

    protected $casts = [
        'expected_pickup_date' => 'datetime',
        'pickup_date' => 'datetime',
    ];

    public function reserver(): BelongsTo
    {
        return $this->belongsTo(Patron::class, 'reserver_id');
    }

    public function accession(): BelongsTo
    {
        return $this->belongsTo(Accession::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(Librarian::class, 'processed_by');
    }
}
