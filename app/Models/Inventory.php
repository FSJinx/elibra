<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryFactory> */
    use HasFactory;

    protected $fillable = [
        'inventory_code',
        'is_completed',
        'librarian_id',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function librarian()
    {
        return $this->belongsTo(Librarian::class);
    }

    public function lines()
    {
        return $this->hasMany(Inventory_Line::class, 'inventory_id');
    }
}
