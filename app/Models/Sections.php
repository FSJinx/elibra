<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sections extends Model
{
    use AutoFormatter, HasFactory, SoftDeletes;

    protected $fillable = ['name', 'library_id', 'librarian_id'];

    protected $formatter = [
        'name' => 'capitalize',
    ];

    public function library()
    {
        return $this->belongsTo(Library::class, 'library_id');
    }

    public function librarian()
    {
        return $this->belongsTo(Librarian::class, 'librarian_id');
    }
}
