<?php

namespace App\Models;

use Database\Factories\PatronTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatronType extends Model
{
    /** @use HasFactory<PatronTypeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['key', 'name', 'description'];
}
