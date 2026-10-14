<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory_Line extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryLineFactory> */
    use HasFactory;

    protected $table = 'inventory__lines';

        protected $fillable = [
            'inventory_id',
            'accession_id',
            'verified_by',
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
