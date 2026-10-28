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
        'electronic_file',
        'keywords',

        'status',
        'statusRemark',
        'on_catalog',

        'library_id',
        'submitted_by',
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

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function library()
    {
        return $this->belongsTo(Library::class);
    }
}
