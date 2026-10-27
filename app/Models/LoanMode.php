<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Database\Factories\LoanModeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanMode extends Model
{
    /** @use HasFactory<LoanModeFactory> */
    use AutoFormatter, HasFactory;

    protected $fillable = [
        'slug', 'name', 'description',
    ];

    protected $formatter = [
        'name' => 'capitalize',
    ];

    public function circulations(): HasMany
    {
        return $this->hasMany(Circulation::class);
    }
}
