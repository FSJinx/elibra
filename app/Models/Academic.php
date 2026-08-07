<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Academic extends Model
{
    /** @use HasFactory<\Database\Factories\AcademicFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
            'category',
            'subjects',
            'doi',
            'item_id',
            'department_id'
        ];

    protected $casts = [
        'subjects' => 'array',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
