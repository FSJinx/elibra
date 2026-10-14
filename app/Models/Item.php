<?php

namespace App\Models;

use App\Traits\AutoFormatter;
use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    /** @use HasFactory<ItemFactory> */
    use AutoFormatter, HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'call_number',
        'electronic_file',
        'keywords',

        'edition',
        'isbn_issn',
        'copyright_year',
        'doi',
        'volume',
        'issue',
        'pages',
        'department_id',

        'released',

        'item_type_id',
        'item_type_category_id',
        'library_id',
        'language_id',
        'cover_media_id',
    ];

    protected $formatter = [
        'keywords' => 'lowercase',
    ];

    protected $casts = [
        'keywords' => 'array',
    ];

    public function authors()
    {
        return $this->belongsToMany(
            Author::class,
            'item_authors',
            'item_id',
            'author_id',
        )->withPivot('authorship_id');
    }

    public function syncAuthors(array $authors): void
    {
        $pivotData = [];

        foreach ($authors as $author) {
            $authorId = is_array($author) ? ($author['id'] ?? null) : $author;

            if ($authorId === null) {
                continue;
            }

            $pivotData[$authorId] = [
                'authorship_id' => is_array($author) ? ($author['authorship_id'] ?? null) : null,
            ];
        }

        $this->authors()->sync($pivotData);
    }

    public function itemPublishers()
    {
        return $this->hasMany(ItemPublisher::class);
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function itemTypeCategory()
    {
        return $this->belongsTo(ItemTypeCategory::class);
    }

    public function library()
    {
        return $this->belongsTo(Library::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function acquisition_lines() {
        return $this->hasMany(AcquisitionLines::class);
    }

    public function coverMedia()
    {
        return $this->belongsTo(Media::class, 'cover_media_id')
            ->where('image_type', Media::ITEM_COVER);
    }

}
