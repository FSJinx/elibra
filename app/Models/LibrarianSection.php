<?php

namespace App\Models;

use Database\Factories\LibrarianSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibrarianSection extends Model
{
    /** @use HasFactory<LibrarianSectionFactory> */
    use HasFactory;

    protected $fillable = [
        'section_id', // 
        'librarian_id',
        'role', // head, staff
    ];

    public function librarian() {
        return $this->hasMany(Librarian::class);
    }
}


/**
 * Librarian Id = Christian
 * Section = General
 * Role = head, staff
 * 
 * Librarian Id= Christian
 * Section = Filipiniana
 * Role = staff
 * 
 * Librarian Id = Merel
 * Section = Filipiniana
 * Role = head
 *
 */