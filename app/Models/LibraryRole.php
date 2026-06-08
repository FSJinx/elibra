<?php

namespace App\Models;

use Database\Factories\LibraryRoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryRole extends Model
{
    /** @use HasFactory<LibraryRoleFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function librarian() {
        return $this->hasMany(Librarian::class);
    }
}
