<?php

namespace App\Models;

use Database\Factories\CampusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class Campus extends Model
{
    /** @use HasFactory<CampusFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'code', 'address', 'heading', 'status'];

    #[Override]
    protected static function booted()
    {
        static::deleting(function ($campus) {
            $campus->branches()->delete();
        });

        static::restoring(function ($campus) {
            $campus->branches()->withTrashed()->restore();
        });
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
