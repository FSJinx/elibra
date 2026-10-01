<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patron extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // 
        // 
        /**
         * EBC      -   Electronic Borrowers Card
         * Format   -   Patron Type + Year of Registration + Nth number of patron
         * Sample   -   SP202601
         */
        'ebc_number', 


        'external_organization',
        'date_joined',
        'account_expiry',
        'remarks',
        'user_id',
        'patron_type_id',
        'program_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function patronType()
    {
        return $this->belongsTo(PatronType::class, 'patron_type_id');
    }

    public function program()
    {
        return $this->belongsTo(Programs::class, 'program_id')->withTrashed();
    }
}
