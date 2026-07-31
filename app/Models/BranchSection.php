<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchSection extends Model
{
    /** @use HasFactory<\Database\Factories\BranchSectionFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['branch_id', 'section_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function section()
    {
        return $this->belongsTo(Sections::class);
    }

    public function sectionHead()
    {
        return $this->belongsTo(Librarian::class, 'section_head_id');
    }
}

/**
 * 
 */