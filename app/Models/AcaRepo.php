<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcaRepo extends Model
{
    /** @use HasFactory<\Database\Factories\AcaRepoFactory> */
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
