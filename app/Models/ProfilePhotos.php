<?php

namespace App\Models;

use Database\Factories\ProfilePhotosFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilePhotos extends Model
{
    /** @use HasFactory<ProfilePhotosFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'original_name',
        'stored_name',
        'user_id',
    ];
}
