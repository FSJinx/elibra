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
        'item_id',
        'acquisition_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function items()
    {
        return $this->belongsTo(Item::class);
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
