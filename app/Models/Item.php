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
        'publication_year',
        'keywords',
        'electronic_file',
        'item_type_id',
        'item_type_category_id',
        'branch_id',
        'language_id',
    ];

    protected $formatter = [
        'keywords' => 'lowercase',
    ];

    protected $casts = [
        'keywords' => 'array',
    ];

    public function book()
    {
        return $this->hasOne(Book::class);
    }

    public function academic()
    {
        return $this->hasOne(Academic::class);
    }

    public function serial()
    {
        return $this->hasOne(Serial::class);
    }

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

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function itemTypeCategory()
    {
        return $this->belongsTo(ItemTypeCategory::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function acquisition_lines() {
        return $this->hasMany(AcquisitionLines::class);
    }

}
