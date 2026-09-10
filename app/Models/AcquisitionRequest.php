<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcquisitionRequest extends Model
{
    use HasFactory;

    protected $table = 'acquisition_requests';

    protected $fillable = [
        'request_id',
        'requested_by',
        'item_type_id',
        'reviewed_by',

        // Bibliographic information
        'title',
        'author',
        'isbn',
        'publisher',
        'publication_year',
        'edition',

        // Request details
        'subject',
        'quantity',
        'justification',
        'priority',

        // Financial / supplier information
        'estimated_unit_price',
        'estimated_total_price',
        'preferred_supplier',

        // Workflow
        'request_status',
        'procurement_status',
        'is_closed',

        // Review
        'reviewed_at',
        'closed_remarks',
        'remarks',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'quantity' => 'integer',
        'estimated_unit_price' => 'decimal:2',
        'estimated_total_price' => 'decimal:2',
        'is_closed' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
