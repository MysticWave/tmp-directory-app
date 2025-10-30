<?php

namespace App\Models;

use App\Enums\PlaceImportType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Place extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'google_place_id',
        'cid',
        'google_url',
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
        ];
    }

    public function scrapeReviews(): bool
    {
        try {
            DB::beginTransaction();
            $import = PlaceImport::create([
                'squid_id' => config('services.lobstrio.squids.review_import'),
                'query' =>
                    $this->google_url ??
                    'https://www.google.com/maps?cid=' . $this->cid,
                'type' => PlaceImportType::REVIEWS,
                'place_id' => $this->id,
            ]);
            $import->run();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(
                'Failed to scrape reviews for place ID ' .
                    $this->id .
                    ': ' .
                    $e->getMessage(),
            );
            return false;
        }
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
