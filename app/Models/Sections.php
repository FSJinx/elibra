<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Database\Factories\SectionsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sections extends Model
{
    /** @use HasFactory<SectionsFactory> */
    use HasFactory, SoftDeletes, AutoFormatter;

    protected $fillable = ['name'];

    protected $formatter =[
        'name' => 'capitalize'
    ];

    public function branchSections()
    {
        return $this->hasMany(BranchSection::class, 'section_id');
    }
}
