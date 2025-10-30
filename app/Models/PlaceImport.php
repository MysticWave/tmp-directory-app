<?php

namespace App\Models;

use App\Enums\PlaceImportStatus;
use App\Enums\PlaceImportType;
use App\Enums\ReviewSource;
use App\Services\LobstrioService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PlaceImport extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'place_id',
        'query',
        'squid_id',
        'run_id',
        'status',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'status' => PlaceImportStatus::class,
            'type' => PlaceImportType::class,
        ];
    }

    public function run(): void
    {
        $result = app(LobstrioService::class)->getPlaceData(
            squidId: $this->squid_id,
            query: $this->query,
        );

        $this->update([
            'run_id' => $result['run_id'] ?? null,
            'status' => PlaceImportStatus::IN_PROGRESS,
        ]);
    }

    public function check(): bool
    {
        $result = app(LobstrioService::class)->getRun(runId: $this->run_id);
        if ($result['ended_at'] ?? null) {
            switch ($result['status']) {
                case 'done':
                case 'aborted':
                    $this->update(['status' => PlaceImportStatus::COMPLETED]);
                    $this->processResults();
                    return true;
                case 'error':
                    $this->update(['status' => PlaceImportStatus::FAILED]);
                    return false;
                default:
                    return false;
            }
        }

        return false;
    }

    public function processResults(): void
    {
        $results = $this->getResults();

        try {
            switch ($this->type) {
                case PlaceImportType::PLACE:
                    $data = $results['data'][0] ?? null; //there should not be more than 1 result for place import
                    if ($data) {
                        $place = $this->place ?? new Place();
                        $place->fill([
                            'google_place_id' => $data['place_id'] ?? null,
                            'cid' => $data['cid'] ?? null,
                            'google_url' => $data['url'] ?? null,
                            'name' => $data['name'] ?? null,
                            'type' => $data['category'] ?? null,
                            'description' => $data['description'] ?? null,
                            'address_line_1' => $data['address'] ?? '',
                            'city' => $data['city'] ?? null,
                            'region' => $data['region'] ?? null,
                            'postcode' => $data['zip_code'] ?? null,
                            'country' => $data['country'] ?? '',
                            'latitude' => $data['lat'] ?? null,
                            'longitude' => $data['lng'] ?? null,
                            'phone' => $data['phone'] ?? null,
                            'website' => $data['website'] ?? null,
                            'email' => $data['email'] ?? null,
                            'opening_hours' => explode(
                                ',',
                                $data['opening_hours'] ?? '',
                            ),
                            'rating' => $data['score'] ?? 0,
                            'user_ratings_total' => $data['ratings'] ?? 0,
                            'tags' => [],
                            'is_verified' => false,
                            'source' => ReviewSource::Google,
                        ]);
                        $place->save();

                        if (!$this->place_id) {
                            $this->update(['place_id' => $place->id]);
                        }
                    }

                    break;
                case PlaceImportType::REVIEWS:
                    $page = 1;
                    while (true) {
                        $data = $results['data'] ?? [];
                        $place_id =
                            $this->place_id ??
                            (Place::where(
                                'google_place_id',
                                $data[0]['place_id'] ?? '',
                            )->value('id') ??
                                null);
                        if (!$place_id) {
                            throw new \Exception(
                                'Place ID not found for reviews import.',
                            );
                        }

                        foreach ($data as $reviewData) {
                            $pictureUrls = explode(
                                ',',
                                $reviewData['pictures'] ?? '',
                            );
                            $pictures = $this->saveImages($pictureUrls);

                            Review::updateOrCreate(
                                [
                                    'external_review_id' =>
                                        $reviewData['internal_review_id'],
                                ],
                                [
                                    'place_id' => $place_id,
                                    'source' => ReviewSource::Google,
                                    'external_review_id' =>
                                        $reviewData['internal_review_id'],
                                    'author_name' =>
                                        $reviewData['user_name'] ?? null,
                                    'author_url' =>
                                        $reviewData['user_link'] ?? null,
                                    'author_image_url' =>
                                        $reviewData['user_image_url'] ?? null,
                                    'author_external_id' =>
                                        $reviewData['user_internal_id'] ?? null,
                                    'rating' => $reviewData['score'] ?? 0,
                                    'text' => $reviewData['text'] ?? null,
                                    'original_text' =>
                                        $reviewData['original_text'] ?? null,
                                    'is_translated' =>
                                        $reviewData['text'] !==
                                        $reviewData['original_text'],
                                    'language' => $reviewData['lang'] ?? null,
                                    'likes_count' =>
                                        $reviewData['total_likes'] ?? 0,
                                    'reviews_count' =>
                                        $reviewData['user_reviews_count'] ?? 0,
                                    'published_at' =>
                                        $reviewData['published_at_datetime'] ??
                                        null,
                                    'raw' => $reviewData,
                                    'pictures' => $pictures,
                                ],
                            );
                        }

                        if ($results['page'] >= $results['total_pages']) {
                            break;
                        }

                        $page++;
                        $results = $this->getResults(page: $page);
                    }
                    break;
            }
        } catch (\Exception $e) {
            report($e);
            $this->update(['status' => PlaceImportStatus::FAILED]);
        }

        // cleanup tasks to not duplicate them in future runs
        app(LobstrioService::class)->deleteRunTasks(runId: $this->run_id);
    }

    private function saveImages(array $pictureUrls): array
    {
        $pictures = [];
        foreach ($pictureUrls as $pictureUrl) {
            $pictureUrl = trim($pictureUrl);
            if (empty($pictureUrl)) {
                continue;
            }

            try {
                // get image contents
                $imageContents = file_get_contents($pictureUrl);

                $filename = 'review_' . uniqid() . '.jpg';
                $path = 'public/reviews/' . $filename;

                // save file to storage
                Storage::put($path, $imageContents);

                // store the URL or path as needed
                $pictures[] = Storage::url($path);
            } catch (\Exception $e) {
                report($e);
            }
        }
        return $pictures;
    }

    public function getResults(int $page = 1): array
    {
        return app(LobstrioService::class)->getResults(
            runId: $this->run_id,
            page: $page,
        );
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
