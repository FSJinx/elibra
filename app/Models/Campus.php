<?php

namespace App\Models;

use Database\Factories\CampusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
{
    /** @use HasFactory<CampusFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'code', 'address', 'heading', 'status'];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

}
    