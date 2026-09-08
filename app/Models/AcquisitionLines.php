<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcquisitionLines extends Model
{
    /** @use HasFactory<\Database\Factories\AcquisitionLinesFactory> */
    use HasFactory;

    protected $fillable = [
        'quantity',
        'unit_price',
        'discount',
        'net_price',
        'item_id',
        'acquisition_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'float',
        'discount' => 'float',
        'net_price' => 'float',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function acquisition()
    {
        return $this->belongsTo(Acquisition::class, 'acquisition_id');
    }
}
