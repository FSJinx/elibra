<?php

namespace App\Models;

use Attribute;
use Database\Factories\InventoryLineFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLine extends Model
{
    /** @use HasFactory<InventoryLineFactory> */
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'accession_id',

        'current_status',
        'current_section_id',
        
        'new_status',
        'new_section_id',

        'verifier_id',
        'verified_at',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function accession()
    {
        return $this->belongsTo(Accession::class, 'accession_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Librarian::class, 'verified_by');
    }
}
