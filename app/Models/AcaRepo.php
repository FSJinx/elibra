<?php

namespace App\Models;

use Database\Factories\AcaRepoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcaRepo extends Model
{
    /** @use HasFactory<AcaRepoFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'doi',
        'keywords',

        'status',
        'statusRemark',
        'on_catalog',

        'language_id',
        'program_id',
    ];

    protected $casts = [
        'keywords' => 'array',
        'on_catalog' => 'boolean',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function program()
    {
        return $this->belongsTo(Programs::class);
    }
}
