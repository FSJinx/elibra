<?php

namespace App\Models;

use Database\Factories\InventoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    /** @use HasFactory<InventoryFactory> */
    use HasFactory;

    protected $fillable = [
        'inventory_code',
        'status',

        'section_id',
        'librarian_id',

        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function librarian()
    {
        return $this->belongsTo(Librarian::class, 'librarian_id');
    }

    public function section()
    {
        return $this->belongsTo(Sections::class, 'section_id');
    }

    public function lines()
    {
        return $this->hasMany(Inventory_Line::class, 'inventory_id');
    }
}
