<?php

namespace App\Models;

use Database\Factories\SectionsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sections extends Model
{
    /** @use HasFactory<SectionsFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'section_head_id',
        'branch_id',
    ];
}
