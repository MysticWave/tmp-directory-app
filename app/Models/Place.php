<?php

namespace App\Models;

use App\Enums\AiOutputStatus;
use App\Enums\PlaceImportTaskType;
use App\Enums\PlaceImportType;
use App\Traits\OrderableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Place extends Model
{
    use OrderableTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'import_id',
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
        'images',
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
            'images' => 'json',
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
                'task_type' => PlaceImportTaskType::URL,
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

    public function aiProcessReviews(Prompt $prompt): bool
    {
        try {
            $input = $prompt->prompt_text;
            $reviews = $this->reviews()
                ->where(
                    fn($query) => $query
                        ->whereNotNull('text')
                        ->orWhereNotNull('original_text'),
                )
                ->get();

            if ($reviews->count() === 0) {
                return false;
            }

            $input .= "\n---\n";
            $input .= "Place Name: $this->name\n";
            $input .= "Place Types: $this->type\n";
            if ($this->description) {
                $input .= "Place Description: $this->description\n";
            }
            $input .= "Address: $this->address_line_1\n";
            $input .= "Country: $this->country\n";

            foreach ($reviews as $review) {
                $content = $review->original_text ?? $review->text;

                $input .= "\n---\n";
                $input .= "Author: $review->author_name\n";
                $input .= "Rating: $review->rating / 5\n";
                $input .= "Content: $content\n";
            }

            AiOutput::create([
                'prompt_id' => $prompt->id,
                'status' => AiOutputStatus::PENDING,
                'outputable_type' => Place::class,
                'outputable_id' => $this->id,
                'input' => $input,
            ]);

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(PlaceImport::class, 'import_id');
    }

    public function scopeQuerySearch(Builder $query): void
    {
        $query
            ->when(request()->input('term'), function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when(request()->input('city'), function ($query, $city) {
                $query->where('city', $city);
            })
            ->when(request()->input('has_reviews'), function (
                $query,
                $hasReviews,
            ) {
                if ($hasReviews == 'true') {
                    $query->whereHas('reviews');
                } else {
                    $query->whereDoesntHave('reviews');
                }
            })
            ->when(request()->input('is_verified'), function (
                $query,
                $isVerified,
            ) {
                if ($isVerified == 'true') {
                    $query->where('is_verified', true);
                } else {
                    $query->where('is_verified', false);
                }
            })
            ->when(request()->input('import_id'), function ($query, $importId) {
                $query->where('import_id', $importId);
            });
    }

    public function aiOutputs(): MorphMany
    {
        return $this->morphMany(AiOutput::class, 'outputable');
    }
}
