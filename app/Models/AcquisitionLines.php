<?php

namespace App\Models;

use Database\Factories\AcquisitionLinesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcquisitionLines extends Model
{
    /** @use HasFactory<AcquisitionLinesFactory> */
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

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function acquisition()
    {
        return $this->belongsTo(Acquisition::class, 'acquisition_id');
    }

    public function accessions()
    {
        return $this->hasMany(
            Accession::class,
            'acquisition_line_id'
        );
    }
}
