<?php

namespace App\Models;

use App\Enums\ReviewSource;
use App\Traits\OrderableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use OrderableTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'place_id',
        'source',
        'external_review_id',
        'author_name',
        'author_url',
        'author_image_url',
        'author_external_id',
        'rating',
        'text',
        'original_text',
        'is_translated',
        'language',
        'likes_count',
        'reviews_count',
        'published_at',
        'raw',
        'pictures',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source' => ReviewSource::class,
            'is_translated' => 'boolean',
            'published_at' => 'datetime',
            'raw' => 'json',
            'pictures' => 'json',
        ];
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
