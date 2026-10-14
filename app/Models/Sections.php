<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Database\Factories\SectionsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sections extends Model
{
    use HasFactory, SoftDeletes, AutoFormatter;

    protected $fillable = ['name', 'library_id', 'librarian_id'];

    protected $formatter =[
        'name' => 'capitalize'
    ];

    public function branchSections()
    {
        return $this->hasMany('App\\Models\\BranchSection', 'section_id');
    }

    public function library()
    {
        return $this->belongsTo(Library::class, 'library_id');
    }

    public function librarian()
    {
        return $this->belongsTo(Librarian::class, 'librarian_id');
    }
}
