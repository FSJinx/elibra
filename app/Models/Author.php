<?php

namespace App\Models;

use Database\Factories\AuthorFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    /** @use HasFactory<AuthorFactory> */
    use HasFactory, SoftDeletes;

    protected $appends = ['name'];

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
    ];

    protected function name(): Attribute
    {
        $i = fn (?string $s) => collect(preg_split('/[\s-]+/', (string) $s, -1, PREG_SPLIT_NO_EMPTY))->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)).'.')->implode(' ');
        $j = fn (...$p) => implode(' ', array_filter($p));

        return Attribute::get(fn () => (object) [
            'full' => $j($this->first_name, $i($this->middle_name), $this->last_name),
            'formal' => "{$this->last_name}, ".$j($this->first_name, $i($this->middle_name)),
            'author' => "{$this->last_name}, ".$j($i($this->first_name), $i($this->middle_name)),
            'author_reversed' => $j($i($this->first_name), $i($this->middle_name), $this->last_name),
        ]);
    }

    public function items()
    {
        return $this->belongsToMany(
            Item::class,
            'item_authors',
            'author_id',
            'item_id'
        );
    }

    public function authorship()
    {
        return $this->belongsTo(Authorship::class);
    }
}
