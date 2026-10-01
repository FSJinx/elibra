<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class Library extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'phone', 'email', 'email_verified_at', 'website', 'opening_hour', 'closing_hour', 'heading', 'logo_id', 'branch_head_id', 'campus_id'];

    #[Override]
    protected static function booted()
    {
        return parent::booted();
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function librarian()
    {
        return $this->hasMany(Librarian::class);
    }

    public function sections()
    {
        return $this->hasMany(Sections::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
