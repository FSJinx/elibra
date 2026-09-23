<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinesTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\FinesTransactionFactory> */
    use HasFactory;

    protected $table = 'fines_transactions';

    protected $fillable = [
        'amount',
        'transaction_type',
        'remarks',
        'patron_id',
        'circulation_id',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function circulation(): BelongsTo
    {
        return $this->belongsTo(Circulation::class, 'circulation_id');
    }

    public function patron(): BelongsTo
    {
        return $this->belongsTo(Patron::class, 'patron_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
