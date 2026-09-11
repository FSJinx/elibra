<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatronTypeLoanPolicy extends Model
{
    /** @use HasFactory<\Database\Factories\PatronTypeLoanPolicyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'description',
        'can_reserve',
        'reservation_limit',
        'loan_period_days',
        'max_items',
        'max_renewals',
        'fine_per_due',
        'grace_period',
        'notes',
        'patron_type_id',
        'loan_mode_id',
    ];

    protected $casts = [
        'can_reserve' => 'boolean',
        'reservation_limit' => 'integer',
        'loan_period_days' => 'integer',
        'max_items' => 'integer',
        'max_renewals' => 'integer',
        'fine_per_due' => 'decimal:2',
        'grace_period' => 'integer',
        'patron_type_id' => 'integer',
        'loan_mode_id' => 'integer',
    ];

    public function patronType(): BelongsTo
    {
        return $this->belongsTo(PatronType::class, 'patron_type_id');
    }

    public function loanMode(): BelongsTo
    {
        return $this->belongsTo(LoanMode::class, 'loan_mode_id');
    }
}
