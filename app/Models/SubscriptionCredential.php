<?php

namespace App\Models;

use Database\Factories\SubscriptionCredentialFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCredential extends Model
{
    /** @use HasFactory<SubscriptionCredentialFactory> */
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'subscription_id',
        'campus_id',
    ];

    // Addes Laravel Encryption (Mutator Method)
    // For Retrieving and Storing Passwords without exposing them in plain text
    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn ($password) => decrypt($password), // Decrypt the password when retrieving it from the database
            set: fn ($password) => encrypt($password), // Encrypt the password before storing it in the database
        );
    }

    protected function username(): Attribute
    {
        return Attribute::make(
            get: fn ($username) => decrypt($username),
            set: fn ($username) => encrypt($username),
        );
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
}
