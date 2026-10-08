<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcquisitionLines extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_price',
        'item_id',
        'acquisition_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
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
        return $this->hasMany(Accession::class, 'acquisition_line_id');
    }
}
