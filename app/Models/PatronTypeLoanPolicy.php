<?php

namespace App\Models;

use Database\Factories\PatronTypeLoanPolicyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatronTypeLoanPolicy extends Model
{
    /** @use HasFactory<PatronTypeLoanPolicyFactory> */
    use HasFactory;

    protected $fillable = [
        'loan_period_days',

        'max_items',
        'max_renewals',
        'fine_per_due', 
        'grace_period',
        'can_reserve',
        'reservation_limit',

        'notes',

        'patron_type_id',
        'loan_mode_id',
    ];

    protected $casts = [
        'loan_period_days' => 'integer',
        'max_items' => 'integer',
        'max_renewals' => 'integer',
        'fine_per_due' => 'decimal:2',
        'grace_period' => 'integer',
        'can_reserve' => 'boolean',
        'reservation_limit' => 'integer',

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
