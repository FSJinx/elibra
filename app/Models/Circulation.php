<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Circulation extends Model
{
    /** @use HasFactory<\Database\Factories\CirculationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'renewal_count',
        'fine_charged',
        'notes',
        'processed_by',
        'accession_id',
        'patron_id',
        'loan_mode_id',
        'return_received_by',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
        'renewal_count' => 'integer',
        'fine_charged' => 'decimal:2',
    ];

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function patron(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patron_id');
    }

    public function accession(): BelongsTo
    {
        return $this->belongsTo(Accession::class, 'accession_id');
    }

    public function loanMode(): BelongsTo
    {
        return $this->belongsTo(LoanMode::class, 'loan_mode_id');
    }

    public function returnReceivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'return_received_by');
    }
}
