<?php

namespace App\Models;

use Database\Factories\PatronFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patron extends Model
{
    /** @use HasFactory<PatronFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ebc_number',
        'external_organization',
        'date_joined',
        'account_expiry',
        'user_id',
        'program_id',
        'patron_type_id',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    public function patronType()
    {
        return $this->belongsTo(PatronType::class);
    }
}
