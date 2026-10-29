<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Holding extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserved_by',
        'accession_id',
        'processed_by',
        'pickup_date',
        'picked_up_at',
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'picked_up_at' => 'datetime',
    ];

    public function reserver(): BelongsTo
    {
        return $this->belongsTo(Patron::class, 'reserved_by');
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
