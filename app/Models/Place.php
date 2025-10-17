<?php

namespace App\Models;

use App\Enums\PlaceType;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'google_place_id',
        'name',
        'type',
        'description',
        'address_line_1',
        'address_line_2',
        'city',
        'region',
        'postcode',
        'country',
        'latitude',
        'longitude',
        'phone',
        'website',
        'email',
        'opening_hours',
        'rating',
        'user_ratings_total',
        'tags',
        'is_verified',
        'source',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'opening_hours' => 'json',
            'tags' => 'json',
            'is_verified' => 'boolean',
            'type' => PlaceType::class,
        ];
    }
}
