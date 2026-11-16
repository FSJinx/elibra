<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference',
        'type',
        'format',
        'filters',
        'data',
        'file_id',
        'generator_id',
        'library_id',
    ];

    protected $casts = [
        'filters' => 'array',
        'data' => 'array',
    ];

    public function file()
    {
        return $this->belongsTo(Attachment::class, 'file_id');
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generator_id');
    }
}
