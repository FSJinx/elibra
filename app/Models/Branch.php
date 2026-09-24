<?php

namespace App\Models;

use Database\Factories\BranchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class Branch extends Model
{
    /** @use HasFactory<BranchFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'contact_info', 'email', 'email_verified_at', 'opening_hour', 'closing_hour', 'logo_id', 'branch_head_id', 'campus_id'];

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

    public function branch_sections()
    {
        return $this->hasMany(BranchSection::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
