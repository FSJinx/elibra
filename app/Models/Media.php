<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /** @use HasFactory<\Database\Factories\MediaFactory> */
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path', 
        'mime_type', 
        'file_size', 
        'image_type'
    ];

    public const PROFILE = 'profile'; // for user profile pictures
    public const LOGO = 'logo'; // for logo's (campus, branch logo)
    public const SUBSCRIPTION = 'subscription'; // for subscription images (e.g. ebsco, proquest, etc.)
    public const BOOK_COVER = 'book_cover'; // book cover, pwede na alisin wala namang book cover
    public const DOCUMENT = 'document'; // document (scanned documents)
    public const BANNER = 'banner'; // banner? ewan need feedback
    public const OTHER = 'other'; 
    

}
