<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'link', 'thumbnail_id'];

    //relationship with media
    public function media()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    //relationship with subscription credentials
    public function subscriptionCredentials()
    {
        return $this->hasMany(SubscriptionCredential::class);
    }  
}
