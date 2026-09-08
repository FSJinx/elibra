<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accession extends Model
{
    /** @use HasFactory<\Database\Factories\AccessionFactory> */
    use HasFactory;

    protected $fillable = [
        'accession_number',
        'status',
        'remarks',
        'item_id',
        'section_id',
        'acquisition_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function section()
    {
        return $this->belongsTo(Sections::class);
    }

    public function acquisition()
    {
        return $this->belongsTo(Acquisition::class);
    }
}
